<?php
use Yaf\Controller_Abstract;
use Yaf\Registry;
use Yaf\Session;
use Yaf\Dispatcher;
use Yaf\Application;

class UeditorController extends CommonController
{
    private $upload_url;
    private $upload_path;
    private $list_image;

    public function init()
    {
        parent::init();
        $config = Application::app()->getConfig()->toArray();
        $this->upload_url = $config['image']['upload_path'];
        $this->upload_path = $config['image']['data_path'];
        $this->list_image = $config['image']['list_image'];
        $this->setViewPath(APP_PATH . '/views');
    }

    public function indexAction()
    {
        Dispatcher::getInstance()->autoRender(0);
        $config_arr = $this->getConfig();
        $action = $this->getRequest()->get('action');
        switch ($action) {
            case 'config':
                $result =  json_encode($config_arr);
                break;
            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $result = $this->uploadAction();
                break;
            /* 列出图片 */
            case 'listimage':
                $result = $this->listAction();
                break;
            /* 列出文件 */
            case 'listfile':
                $result = $this->listAction();
                break;
            /* 抓取远程文件 */
            case 'catchimage':
                $result = $this->crawlerAction();
                break;
            default:
                $result = json_encode(array(
                'state'=> '请求地址出错'
                ));
                break;
        }
        $callback=$this->getRequest()->get("callback");
        /* 输出结果 */
        if (isset($callback)) {
            if (preg_match("/^[\w_]+$/", $callback)) {
                echo htmlspecialchars($callback) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                'state'=> 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }

    public function uploadAction()
    {
        /* 上传配置 */
        $CONFIG = $this->getConfig();
        $base64 = "upload";
        switch (htmlspecialchars($this->getRequest()->get('action'))) {
            case 'uploadimage':
                $config = array(
                    "pathFormat" => $CONFIG['imagePathFormat'],
                    "maxSize" => $CONFIG['imageMaxSize'],
                    "allowFiles" => $CONFIG['imageAllowFiles'],
                    "upload_path" => $this->upload_path
                );
                $fieldName = $CONFIG['imageFieldName'];
                break;
            case 'uploadscrawl':
                $config = array(
                    "pathFormat" => $CONFIG['scrawlPathFormat'],
                    "maxSize" => $CONFIG['scrawlMaxSize'],
                    "allowFiles" => $CONFIG['scrawlAllowFiles'],
                    "oriName" => "scrawl.png",
                    "upload_path" => $this->upload_path
                );
                $fieldName = $CONFIG['scrawlFieldName'];
                $base64 = "base64";
                break;
            case 'uploadvideo':
                $config = array(
                    "pathFormat" => $CONFIG['videoPathFormat'],
                    "maxSize" => $CONFIG['videoMaxSize'],
                    "allowFiles" => $CONFIG['videoAllowFiles'],
                    "upload_path" => $this->upload_path
                );
                $fieldName = $CONFIG['videoFieldName'];
                break;
            case 'uploadfile':
            default:
                $config = array(
                "pathFormat" => $CONFIG['filePathFormat'],
                "maxSize" => $CONFIG['fileMaxSize'],
                "allowFiles" => $CONFIG['fileAllowFiles'],
                "upload_path" => $this->upload_path
                );
                $fieldName = $CONFIG['fileFieldName'];
                break;
        }

        /* 生成上传实例对象并完成上传 */
        $up = new Uploader($fieldName, $config, $base64);

        /**
         * 得到上传文件所对应的各个参数,数组结构
         * array(
         *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
         *     "url" => "",            //返回的地址
         *     "title" => "",          //新文件名
         *     "original" => "",       //原始文件名
         *     "type" => ""            //文件类型
         *     "size" => "",           //文件大小
         * )
         */

        /* 返回数据 */
        return json_encode($up->getFileInfo());
    }

    public function listAction()
    {
        $CONFIG = $this->getConfig();
        /* 判断类型 */
        switch ($this->getRequest()->get('action')) {
            /* 列出文件 */
            case 'listfile':
                $allowFiles = $CONFIG['fileManagerAllowFiles'];
                $listSize = $CONFIG['fileManagerListSize'];
                $path = $CONFIG['fileManagerListPath'];
                break;
            /* 列出图片 */
            case 'listimage':
            default:
                $allowFiles = $CONFIG['imageManagerAllowFiles'];
                $listSize = $CONFIG['imageManagerListSize'];
                $path = $CONFIG['imageManagerListPath'];
        }
        $allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);

        /* 获取参数 */
        $size = $this->getRequest()->get('size');
        $size = isset($size) ? htmlspecialchars($size) : $listSize;
        $start = $this->getRequest()->get('start');
        $start = isset($start) ? htmlspecialchars($start) : 0;
        $end = $start + $size;

        /* 获取文件列表 */
        $path = $this->upload_path.(substr($path, 0, 1) == "/" ? "":"/") . $path;
        // $path = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == "/" ? "":"/") . $path;
        $files = $this->getfiles($path, $allowFiles);
        if (!count($files)) {
            return json_encode(array(
                "state" => "no match file",
                "list" => array(),
                "start" => $start,
                "total" => count($files)
            ));
        }

        /* 获取指定范围的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--) {
            $list[] = $files[$i];
        }
        //倒序
        //for ($i = $end, $list = array(); $i < $len && $i < $end; $i++){
        //    $list[] = $files[$i];
        //}

        /* 返回数据 */
        $result = json_encode(array(
        "state" => "SUCCESS",
        "list" => $list,
        "start" => $start,
        "total" => count($files)
        ));
        return $result;
    }

    /**
     * 遍历获取目录下的指定类型的文件
     * @param $path
     * @param array $files
     * @return array
     */
    private function getfiles($path, $allowFiles, &$files = array())
    {
        if (!is_dir($path)) {
            return null;
        }
        if (substr($path, strlen($path) - 1) != '/') {
            $path .= '/';
        }
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..' && $file[0]!='.') {
                $path2 = $path . $file;
                if (is_dir($path2)) {
                    $this->getfiles($path2, $allowFiles, $files);
                } else {
                    $config = $this->getConfig();
                    $ext = '.'.pathinfo($file, PATHINFO_EXTENSION);
                    if (in_array($ext, $config["fileManagerAllowFiles"])) {
                        $files[] = array(
                            'url'=> substr($path2, strlen($this->upload_path)),
                            'mtime'=> filemtime($path2)
                        );
                    }
                }
            }
        }
        return $files;
    }

    public function crawlerAction()
    {
        set_time_limit(0);
        $CONFIG = $this->getConfig();
        /* 上传配置 */
        $config = array(
        "pathFormat" => $CONFIG['catcherPathFormat'],
        "maxSize" => $CONFIG['catcherMaxSize'],
        "allowFiles" => $CONFIG['catcherAllowFiles'],
        "oriName" => "remote.png"
        );
        $fieldName = $CONFIG['catcherFieldName'];

        /* 抓取远程图片 */
        $list = array();
        $fieldName = $this->getRequest()->getPost($fieldName);
    
        if (isset($fieldName)) {
            $source = $fieldName;
        } else {
            $source = $this->getRequest()->get($fieldName);
        }

        foreach ($source as $imgUrl) {
            $item = new Uploader($imgUrl, $config, "remote");
            $info = $item->getFileInfo();
            array_push($list, array(
                "state" => $info["state"],
                "url" => $info["url"],
                "size" => $info["size"],
                "title" => htmlspecialchars($info["title"]),
                "original" => htmlspecialchars($info["original"]),
                "source" => htmlspecialchars($imgUrl)
            ));
        }

        /* 返回抓取数据 */
        return json_encode(array(
        'state'=> count($list) ? 'SUCCESS':'ERROR',
        'list'=> $list
        ));
    }

    private function getConfig()
    {
        if (!isset($this->upload_url)) {
            $data_path = "/data";
        } else {
            $data_path = $this->upload_url;
        }

        /* 前后端通信相关的配置,注释只允许使用多行方式 */
        return [
        /* 上传图片配置项 */
            /* 执行上传图片的action名称 */
        "imageActionName"=> "uploadimage",
        /* 提交的图片表单名称 */
        "imageFieldName"=>"upfile",
        /* 上传大小限制，单位B */
        "imageMaxSize"=>2048000,
        /* 上传图片格式显示 */
        "imageAllowFiles"=>[".png", ".jpg", ".jpeg", ".gif", ".bmp"],
        /* 是否压缩图片,默认是true */
        "imageCompressEnable"=> true,
        /* 图片压缩最长边限制 */
        "imageCompressBorder"=> 1600,
        /* 插入的图片浮动方式 */
        "imageInsertAlign"=> "none",
        /* 图片访问路径前缀 */
        "imageUrlPrefix"=> "",
        /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "imagePathFormat"=> $data_path."/image/{yyyy}{mm}{dd}/{time}{rand:6}",
        /* {filename} 会替换成原文件名,配置这项需要注意中文乱码问题 */
        /* {rand:6} 会替换成随机数,后面的数字是随机数的位数 */
        /* {time} 会替换成时间戳 */
        /* {yyyy} 会替换成四位年份 */
        /* {yy} 会替换成两位年份 */
        /* {mm} 会替换成两位月份 */
        /* {dd} 会替换成两位日期 */
        /* {hh} 会替换成两位小时 */
        /* {ii} 会替换成两位分钟 */
        /* {ss} 会替换成两位秒 */
        /* 非法字符 \ : * ? " < > | */
        /* 具请体看线上文档: fex.baidu.com/ueditor/#use-format_upload_filename */

        /* 涂鸦图片上传配置项 */
        /* 执行上传涂鸦的action名称 */
        "scrawlActionName"=> "uploadscrawl",
        /* 提交的图片表单名称 */
        "scrawlFieldName"=> "upfile",
        /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "scrawlPathFormat"=> $data_path."/image/{yyyy}{mm}{dd}/{time}{rand:6}",
        /* 上传大小限制，单位B */
        "scrawlMaxSize"=> 2048000,
        /* 图片访问路径前缀 */
        "scrawlUrlPrefix"=> "",
        "scrawlInsertAlign"=> "none",
        /* 截图工具上传 */
        /* 执行上传截图的action名称 */
        "snapscreenActionName"=> "uploadimage",
        /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "snapscreenPathFormat"=> $data_path."/image/{yyyy}{mm}{dd}/{time}{rand:6}",
        /* 图片访问路径前缀 */
        "snapscreenUrlPrefix"=> "",
        /* 插入的图片浮动方式 */
        "snapscreenInsertAlign"=> "none",
        /* 抓取远程图片配置 */
        "catcherLocalDomain"=> ["127.0.0.1", "localhost"],
        /* 执行抓取远程图片的action名称 */
        "catcherActionName"=> "catchimage",
        /* 提交的图片列表表单名称 */
        "catcherFieldName"=> "source",
        /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "catcherPathFormat"=> $data_path."/image/{yyyy}{mm}{dd}/{time}{rand:6}",
        /* 图片访问路径前缀 */
        "catcherUrlPrefix"=> "",
        /* 上传大小限制，单位B */
        "catcherMaxSize"=> 2048000,
        /* 抓取图片格式显示 */
        "catcherAllowFiles"=> [".png", ".jpg", ".jpeg", ".gif", ".bmp"],

        /* 上传视频配置 */
        /* 执行上传视频的action名称 */
        "videoActionName"=> "uploadvideo",
        /* 提交的视频表单名称 */
        "videoFieldName"=> "upfile",
        /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "videoPathFormat"=> $data_path."/video/{yyyy}{mm}{dd}/{time}{rand:6}",
        /* 视频访问路径前缀 */
        "videoUrlPrefix"=> "",
        /* 上传大小限制，单位B，默认100MB */
        "videoMaxSize"=>102400000,
        /* 上传视频格式显示 */
        "videoAllowFiles"=> [
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"],

        /* 上传文件配置 */
        /* controller里,执行上传视频的action名称 */
        "fileActionName"=> "uploadfile",
        /* 提交的文件表单名称 */
        "fileFieldName"=> "upfile",
        /* 上传保存路径,可以自定义保存路径和文件名格式 */
        "filePathFormat"=> $data_path."/file/{yyyy}{mm}{dd}/{time}{rand:6}",
        /* 文件访问路径前缀 */
        "fileUrlPrefix"=> "",
        /* 上传大小限制，单位B，默认50MB */
        "fileMaxSize"=> 51200000,
        "fileAllowFiles"=> [
        ".png", ".jpg", ".jpeg", ".gif", ".bmp",
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
        ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
        ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
        ], /* 上传文件格式显示 */

        /* 列出指定目录下的图片 */
        /* 执行图片管理的action名称 */
        "imageManagerActionName"=> "listimage",
        /* 指定要列出图片的目录 */
        "imageManagerListPath"=> $data_path."/image/",
        /* 每次列出文件数量 */
        "imageManagerListSize"=> 20,
        /* 图片访问路径前缀 */
        "imageManagerUrlPrefix"=> "",
        /* 插入的图片浮动方式 */
        "imageManagerInsertAlign"=> "none",
        /* 列出的文件类型 */
        "imageManagerAllowFiles"=> [".png", ".jpg", ".jpeg", ".gif", ".bmp"],

        /* 列出指定目录下的文件 */
        /* 执行文件管理的action名称 */
        "fileManagerActionName"=> "listfile",
        /* 指定要列出文件的目录 */
        "fileManagerListPath"=> $data_path."/file/",
        /* 文件访问路径前缀 */
        "fileManagerUrlPrefix"=> "",
        /* 每次列出文件数量 */
        "fileManagerListSize"=> 20,
        /* 列出的文件类型 */
        "fileManagerAllowFiles"=> [
        ".png", ".jpg", ".jpeg", ".gif", ".bmp",
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
        ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
        ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
        ]
        ];
    }
}
