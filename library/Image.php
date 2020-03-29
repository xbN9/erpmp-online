<?php
use Yaf\Application;

//TODO log
class Image
{
    //原始图片
    private $origin_file;
    //原图地址
    private $origin_path;
    //原图类型
    private $origin_type;
    //原图宽度
    private $origin_width;
    //原图名字
    private $origin_height;
    //原图名字
    private $origin_name;
    //支持水印的图片类型
    private $waterMarkType = ['png','jpeg','jpg','gif'];
    //水印图片(如果使用水印图片)
    private $waterBaseImg;
    private $water_file;
    //水印大小
    private $water_width = 400;
    private $water_height = 120;
    //水印字体来源
    private $water_font;
    //水印文字内容
    private $water_text;
    //水印字体大小
    private $water_font_size;
    private $water_font_x;
    private $water_font_y;
    //水印方式
    private $img_config;
 
    public function __construct($file)
    {
        if (empty($file) && file_exists($file)) {
            return false;
        }
        $this->img_config = Application::app()->getConfig()->toArray();
        if (!isset($this->img_config['image'])) {
            return false;
        }
        $this->getFileInfo($file);
    }

    protected function getFileInfo($file)
    {
        $this->origin_file = $file;
        $this->origin_type = $this->getFileExt($this->origin_file);
        $this->origin_path = $this->getFileExt($this->origin_file, 2);
        $this->origin_name = $this->getFileExt($this->origin_file, 3);

        $origin_info = $this->getImageInfo($this->origin_file);
        $this->origin_width = $origin_info['width'];
        $this->origin_height = $origin_info['height'];
        $this->origin_type_id = $origin_info['type_id'];
    }

    //水印
    public function waterMarkImage($file, $type = true)
    {
        $this->getFileInfo($file);
        //是否使用图片水印,图片水印地址
        if ($type ==false) {
            $this->waterBaseImg = $this->img_config['image']['water_path'];
            $img_base_info = $this->getImageInfo($this->waterBaseImg);
            $this->water_width  = $img_base_info['width'];
            $this->water_height = $img_base_info['height'];
        } else {
            $this->water_width  = ceil($this->origin_width/2);
            $this->water_height = ceil($this->origin_height/8);
            $this->water_font = $this->img_config['image']['water_font'];
            $this->water_text = $this->img_config['image']['water_text'];
            //$this->water_font_size= $this->img_config['image']['water_font_size'];
            $this->water_font_size= round($this->origin_width/20);
            $this->water_font_x = round($this->water_width/10);
            $this->water_font_y = round($this->water_height/2)+10;
        }

        //检测上传文件类型
        if (!in_array($this->origin_type, $this->waterMarkType)) {
            return false;
        }

        $sourceHandle = $this->judgeType($this->origin_type, $this->origin_file);
        if (!$sourceHandle) {
            return false;
        }

        //设置水印图像的位置和大小
        $dst_x = $this->origin_width - $this->water_width + $this->water_font_x - strlen($this->water_text);
        $dst_y = $this->origin_height - $this->water_height;

        if ($type==false) {
            //使用图片做水印
            $stamp  = $this->judgeType('png', $this->waterBaseImg);
            if (!$stamp) {
                return false;
            }
        } else {
            $stamp = imagecreatetruecolor($this->water_width, $this->water_height);
            //水印背景颜色
            $background_color =imagecolorallocatealpha($stamp, 255, 255, 255, 1);
            //设置背景颜色透明
            imagealphablending($stamp, false);
            imagefilledrectangle($stamp, 0, 0, $this->water_width, $this->water_height, $background_color);
            imagecolortransparent($stamp, $background_color) ;
            //水印文字颜色
            $text_color = imagecolorallocatealpha($stamp, 255, 56, 147, 1);
            imagettftext($stamp, $this->water_font_size, 1, $this->water_font_x, $this->water_font_y, $text_color, $this->water_font, $this->water_text);
        }
        $flag = imagecopymerge($sourceHandle, $stamp, $dst_x, $dst_y, 0, 0, $this->water_width, $this->water_height, 50);
        if ($flag) {
            $this->water_file = $this->origin_path.'/'.time().'_'.$this->origin_width.'_water.'.$this->origin_type;
            if ($this->saveImage($this->origin_type, $sourceHandle, $this->water_file)) {
                imagedestroy($sourceHandle);
                imagedestroy($stamp);
            }
            return true;
        } else {
            return false;
        }
    }

    //创建压缩
    public function createTinyImage(array $params, $type = true)
    {
        if (empty($params)) {
            echo 'no params';
            exit(1);
        }

        if (!isset($params['width']) || !isset($params['height'])) {
            if (empty($params['width']) || empty($params['height'])) {
                echo 'width or height is 0';
                exit(1);
            }
            echo 'not found width or height';
            exit(1);
        }

        if (!isset($params['x']) || !isset($params['y'])) {
            echo 'no cut dot';
            exit(1);
        }

        if (!isset($params['rotate'])) {
            echo 'no rotate';
            exit(1);
        }

        if (!isset($params['scale_x']) || !isset($params['scale_y'])) {
            echo 'no scale';
            exit(1);
        }
    
        $width = $params['width'];
        $height = $params['height'];
        $x =  $params['x'];
        $y =  $params['y'];
        //翻转角度
        $rotate = $params['rotate'];
        //左右翻转
        $scale_x = $params['scale_x'];
        //上下翻转
        $scale_y = $params['scale_y'];

        if ($width > $this->origin_width) {
            $width = $this->origin_width;
        }
        if ($height > $this->origin_height) {
            $height = $this->origin_height;
        }
        return $this->tinyImage($width, $height, $x, $y, $rotate, $scale_x, $scale_y);
    }
 
    /**
     * [tinyImage  创建略缩图]
     *
     * @param [mixed] $width [剪切图宽度]
     * @param [mixed] $height [剪切图高度]
     * @param [mixed] $x [剪切原点x]
     * @param [mixed] $y [剪切原点y]
     * @param [int] $rotate [翻转角度]
     * @param [int] $scale_x [x方向翻转]
     * @param [int] $scale_y [y方向翻转]
     *
     * @access private
     * @return void
     */
    private function tinyImage($width, $height, $src_x = 0, $src_y = 0, $rotate = 0, $scale_x = 1, $scale_y = 1)
    {
        $tinyImage = imagecreatetruecolor($width, $height);
        $originHandle = $this->judgeType($this->origin_type, $this->origin_file);
        if (!$originHandle || ! $tinyImage) {
            return false;
        }

        //背景旋转
        if (!empty($rotate)) {
            $originHandle = imagerotate($originHandle, $rotate, 0);
        }

        if (function_exists('imagecopyresampled')) {
            //$src_x==0,$src_y==0整体压缩
            imagecopyresampled($tinyImage, $originHandle, 0, 0, $src_x, $src_y, $width+$src_x, $height+$src_y, $this->origin_width, $this->origin_height);
        } else {
                imagecopyresized($tinyImage, $originHandle, 0, 0, $src_x, $src_y, $width+$src_x, $height+$src_y, $this->origin_width, $this->origin_height);
        }

        //旋转
        if ($scale_x ==-1 || $scale_y==-1) {
            if ($scale_x==-1 && $scale_y ==-1) {
                imageflip($tinyImage, IMG_FLIP_BOTH);
            } elseif ($scale_x ==-1) {
                imageflip($tinyImage, IMG_FLIP_HORIZONTAL);
            } elseif ($scale_y==-1) {
                imageflip($tinyImage, IMG_FLIP_VERTICAL);
            }
        }
        $newPic = $this->origin_path.'/'.time().'_'.$width.'.'.$this->origin_type;
        if ($this->saveImage($this->origin_type, $tinyImage, $newPic)) {
            imagedestroy($tinyImage);
            imagedestroy($originHandle);
            return $newPic;
        } else {
            return false;
        }
    }

    private function getFileExt($file, $options = 1)
    {
    
        if (!file_exists($file)) {
            return false;
        }
        switch ($options) {
            case 1:
                return pathinfo($file, PATHINFO_EXTENSION);
            break;
            case 2:
                return pathinfo($file, PATHINFO_DIRNAME);
            break;
            case 3:
                return pathinfo($file, PATHINFO_FILENAME);
            break;
        }
    }

    private function getImageInfo($img)
    {
        $img_info = [];
        if (is_file($img)) {
            $linfo  = getimagesize($img);
            $img_info['width']   = $linfo[0];
            $img_info['height']  = $linfo[1];
            $img_info['type_id'] = $linfo[2];
        }
        return $img_info;
    }

    //保存图片
    private function saveImage($type, $image, $url)
    {
        if ($type == 'jpeg' || $type=='jpg') {
            if (imagejpeg($image, $url)) {
                return true;
            }
        } elseif ($type =='png') {
            if (imagepng($image, $url)) {
                return true;
            }
        } elseif ($type=='gif') {
            if (imagegif($image, $url)) {
                return true;
            }
        }
        return false;
    }

    private function judgeType($type, $source)
    {
        if ($type=='gif') {
            return imagecreatefromgif($source);//gif
        } elseif ($type=='jpg' || $type=='jpeg') {
            return @imagecreatefromjpeg($source);//jpg,jpeg
        } elseif ($type=='png') {
             return imagecreatefrompng($source);//png
        } else {
             return false;
        }
    }
}
