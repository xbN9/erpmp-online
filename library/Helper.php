<?php

/**
 *
 * @doifusd
 * @author doifusd
 * @version doifusd
 * @date 2019-03-05
 */
class Helper {

    public static function xsrf(): string {
        return md5(microtime(true));
    }

    /**
     * @doifusd  getClientIP 2019-03-05 16:47:14
     *
     * @Return
     */
    public static function getRemoteAddr(): string {
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        }
        if (!empty($ipaddress)) {
            $res = preg_match('/[\d\.]{7,15}/', $ipaddress, $matches) ? $matches[0] : '';
            return $res;
        }
        return '';
    }

    /**
     * 替换第一次出现的字符串
     *
     * @param     $search
     * @param     $replace
     * @param     $subject
     * @param int $cur
     *
     * @return mixed
     */
    public static function strReplaceFirst($search, $replace, $subject, $cur = 0) {
        return (strpos($subject, $search, $cur)) ? substr_replace($subject, $replace, (int) strpos($subject, $search, $cur), strlen($search)) : $subject;
    }

    /**
     * 清理URL中的http头
     *
     * @param      $url
     * @param bool $cleanall
     *
     * @return mixed|string
     */
    public static function cleanUrl($url, $cleanall = true) {
        if (strpos($url, 'http://') !== false) {
            if ($cleanall) {
                return '/';
            } else {
                return str_replace('http://', '', $url);
            }
        }
        return $url;
    }

    /**
     * 获取当前域名
     *
     * @param bool $http
     * @param bool $entities
     *
     * @return string
     */
    public static function getHttpHost($http = false, $entities = false) {
        $host = (isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST']);
        if ($entities) {
            $host = htmlspecialchars($host, ENT_COMPAT, 'UTF-8');
        }
        if ($http) {
            $host = self::getCurrentUrlProtocolPrefix() . $host;
        }
        return $host;
    }

    /**
     * 获取当前服务器名
     *
     * @return mixed
     */
    public static function getServerName() {
        if (isset($_SERVER['HTTP_X_FORWARDED_SERVER']) && $_SERVER['HTTP_X_FORWARDED_SERVER']) {
            return $_SERVER['HTTP_X_FORWARDED_SERVER'];
        }
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * 获取用户来源地址
     *
     * @return null
     */
    public static function getReferer() {
        if (isset($_SERVER['HTTP_REFERER'])) {
            return $_SERVER['HTTP_REFERER'];
        } else {
            return null;
        }
    }

    /**
     * 判断是否使用了HTTPS
     *
     * @return bool
     */
    public static function usingSecureMode() {
        if (isset($_SERVER['HTTPS'])) {
            return ($_SERVER['HTTPS'] == 1 || strtolower($_SERVER['HTTPS']) == 'on');
        }
        if (isset($_SERVER['SSL'])) {
            return ($_SERVER['SSL'] == 1 || strtolower($_SERVER['SSL']) == 'on');
        }
        return false;
    }

    /**
     * 获取当前URL协议
     *
     * @return string
     */
    public static function getCurrentUrlProtocolPrefix() {
        if (self::usingSecureMode()) {
            return 'https://';
        } else {
            return 'http://';
        }
    }

    /**
     * 判断是否本站链接
     *
     * @param $referrer
     *
     * @return string
     */
    public static function secureReferrer($referrer) {
        if (preg_match('/^http[s]?:\/\/' . self::getServerName() . '(:443)?\/.*$/Ui', $referrer)) {
            return $referrer;
        }
        return '/';
    }

    public static function getParameter($param, $type = 1) {
        if (isset($param)) {
            switch ($type) {
            case 1:
                $str = addslashes(trim($param));
                break;
            case 2:
                $str = intval(trim($param));
                break;
            case 3:
                //浮点
                $str = floatval(trim($param));
                break;
            case 4:
                //html 字符串
                $str = htmlentities(trim($param), ENT_QUOTES);
                break;
            }
            return $str;
        } else {
            return false;
        }
    }

    public static function paramsOut($param, $type) {
        if (isset($param) && !empty(trim($param))) {
            switch ($type) {
            case 1:
                return intval($param);
                break;
            case 2:
                //浮点
                return sprintf("%.2f", $param);
                break;
            case 3:
                //字符串
                return trim($param);
                break;
            case 4:
                //html 字符串
                return html_entity_decode(trim($param), ENT_NOQUOTES | ENT_HTML5, "UTF-8");
                break;
            }
        }
    }

    public static function responseResult($code = 200, $msg, array $param = [], array $ext = [], $allowOrigin = false) {
        if ($code != '' && isset($code) && is_numeric($code)) {
            $data['code'] = $code;
            $data['msg']  = $msg;
            if ($code == 0) {
                $data = [];
            } elseif ($code != 0) {
                if (!empty($param)) {
                    $data['data'] = $param;
                } else {
                    $data['data'] = [];
                }
            }
            if (!empty($ext)) {
                $data = array_merge($data, $ext);
            }
            if ($allowOrigin == true) {
                header('Content-Type:application/json; charset=utf-8');
                header('Access-Control-Allow-Origin:*');
                header("Access-Control-Allow-Method:POST,GET");
            }
            return json_encode($data);
        } else {
            return false;
        }
    }

    public static function response(array $data, $allowOrigin = false) {
        $response = new \Yaf\Response\Http();
        $response->setBody(json_encode($data, JSON_UNESCAPED_UNICODE));
        $response->setHeader('Content-Type', 'application/json; charset=utf-8');
        if ($allowOrigin == true) {
            $response->setHeader('Access-Control-Allow-Origin', '*');
            $response->setHeader('Access-Control-Allow-Method', 'POST,GET');
        }
        return $response->response();
    }

    /**
     * 过滤HTML内容后返回
     * @param      $string
     * @param bool $html
     * @return array|string
     */
    public static function outPut($param, $html = false) {
        if (!$html) {
            return strip_tags($string);
        }
        if (is_array($string)) {
            return array_map('htmlentitiesUTF8', $string);
        } else {
            return htmlentities(strval($string), ENT_QUOTES, 'utf-8');
        }
    }

    public static function htmlentitiesDecodeUTF8($string) {
        if (is_array($string)) {
            return array_map(['Helper', 'htmlentitiesDecodeUTF8'], $string);
        }
        return html_entity_decode((string) $string, ENT_QUOTES, 'utf-8');
    }

    /**TODO 细看
     * 删除文件夹
     * @param      $dirname
     * @param bool $delete_self
     */
    public static function deleteDirectory($dirname, $delete_self = true) {
        $dirname = rtrim($dirname, '/') . '/';
        if (is_dir($dirname)) {
            $files = scandir($dirname);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..' && $file != '.svn') {
                    if (is_dir($dirname . $file)) {
                        Helper::deleteDirectory($dirname . $file, true);
                    } elseif (file_exists($dirname . $file)) {
                        unlink($dirname . $file);
                    }
                }
            }
            if ($delete_self) {
                rmdir($dirname);
            }
        }
    }

    /**TODO 细看
     * 显示错误信息
     * @param string $string
     * @param array  $error
     * @param bool   $htmlentities
     * @return mixed|string
     */
    public static function displayError($string = 'Fatal error', $error = array(), $htmlentities = true) {
        if (DEBUG_MODE) {
            if (!is_array($error) || empty($error)) {
                return str_replace('"', '&quot;', $string) . ('<pre>' . print_r(debug_backtrace(), true) . '</pre>');
            }
            $key = md5(str_replace('\'', '\\\'', $string));
            $str = (isset($error) and is_array($error) and key_exists($key, $error)) ? ($htmlentities ? htmlentities($error[$key], ENT_COMPAT, 'UTF-8') : $error[$key]) : $string;
            return str_replace('"', '&quot;', stripslashes($str));
        } else {
            return str_replace('"', '&quot;', $string);
        }
    }

    /**
     * 打印出对象的内容
     * @param      $object
     * @param bool $kill
     * @return mixed
     */
    public static function dieObject($object, $kill = true) {
        echo '<pre style="text-align: left;">';
        print_r($object);
        echo '</pre><br />';
        if ($kill) {
            die('END');
        }
        return ($object);
    }

    public static function encrypt($passwd) {
        return md5(_COOKIE_KEY_ . $passwd);
    }

    public static function getToken($string) {
        return !empty($string) ? Helper::encrypt($string) : false;
    }

    /**
     * 截取字符串，支持中文
     * @param        $str
     * @param        $max_length
     * @param string $suffix
     * @return string
     */

    public static function truncate($str, $max_length, $suffix = '...') {
        if (Helper::strlen($str) <= $max_length) {
            return $str;
        }
        $str = utf8_decode($str);
        return (utf8_encode(substr($str, 0, $max_length - Helper::strlen($suffix)) . $suffix));
    }

    /**TODO 细看
     * @param $str
     * @return mixed
     */
    public static function replaceAccentedChars($str) {
        $patterns = array( /* Lowercase */
            '/[\x{0105}\x{00E0}\x{00E1}\x{00E2}\x{00E3}\x{00E4}\x{00E5}]/u',
            '/[\x{00E7}\x{010D}\x{0107}]/u',
            '/[\x{010F}]/u',
            '/[\x{00E8}\x{00E9}\x{00EA}\x{00EB}\x{011B}\x{0119}]/u',
            '/[\x{00EC}\x{00ED}\x{00EE}\x{00EF}]/u',
            '/[\x{0142}\x{013E}\x{013A}]/u',
            '/[\x{00F1}\x{0148}]/u',
            '/[\x{00F2}\x{00F3}\x{00F4}\x{00F5}\x{00F6}\x{00F8}]/u',
            '/[\x{0159}\x{0155}]/u',
            '/[\x{015B}\x{0161}]/u',
            '/[\x{00DF}]/u',
            '/[\x{0165}]/u',
            '/[\x{00F9}\x{00FA}\x{00FB}\x{00FC}\x{016F}]/u',
            '/[\x{00FD}\x{00FF}]/u',
            '/[\x{017C}\x{017A}\x{017E}]/u',
            '/[\x{00E6}]/u',
            '/[\x{0153}]/u',
            /* Uppercase */
            '/[\x{0104}\x{00C0}\x{00C1}\x{00C2}\x{00C3}\x{00C4}\x{00C5}]/u',
            '/[\x{00C7}\x{010C}\x{0106}]/u',
            '/[\x{010E}]/u',
            '/[\x{00C8}\x{00C9}\x{00CA}\x{00CB}\x{011A}\x{0118}]/u',
            '/[\x{0141}\x{013D}\x{0139}]/u',
            '/[\x{00D1}\x{0147}]/u',
            '/[\x{00D3}]/u',
            '/[\x{0158}\x{0154}]/u',
            '/[\x{015A}\x{0160}]/u',
            '/[\x{0164}]/u',
            '/[\x{00D9}\x{00DA}\x{00DB}\x{00DC}\x{016E}]/u',
            '/[\x{017B}\x{0179}\x{017D}]/u',
            '/[\x{00C6}]/u',
            '/[\x{0152}]/u',
        );
        $replacements = array(
            'a',
            'c',
            'd',
            'e',
            'i',
            'l',
            'n',
            'o',
            'r',
            's',
            'ss',
            't',
            'u',
            'y',
            'z',
            'ae',
            'oe',
            'A',
            'C',
            'D',
            'E',
            'L',
            'N',
            'O',
            'R',
            'S',
            'T',
            'U',
            'Z',
            'AE',
            'OE',
        );
        return preg_replace($patterns, $replacements, $str);
    }

    public static function cleanNonUnicodeSupport($pattern) {
        if (!defined('PREG_BAD_UTF8_OFFSET')) {
            return $pattern;
        }
        return preg_replace('/\\\[px]\{[a-z]\}{1,2}|(\/[a-z]*)u([a-z]*)$/i', "$1$2", $pattern);
    }

    /**
     * 转换成小写字符，支持中文
     * @param $str
     * @return bool|string
     */
    public static function strtolower($str) {
        if (is_array($str)) {
            return false;
        }
        if (function_exists('mb_strtolower')) {
            return mb_strtolower($str, 'utf-8');
        }
        return strtolower($str);
    }

    /**
     * 计算字符串长度
     * @param        $str
     * @param string $encoding
     * @return bool|int
     */
    public static function strlen($str, $encoding = 'UTF-8') {
        if (is_array($str) || is_object($str)) {
            return false;
        }
        $str = html_entity_decode($str, ENT_COMPAT, 'UTF-8');
        if (function_exists('mb_strlen')) {
            return mb_strlen($str, $encoding);
        }
        return strlen($str);
    }

    public static function stripslashes($string) {
        if (get_magic_quotes_gpc()) {
            $string = stripslashes($string);
        }
        return $string;
    }

    /**
     * 转换成大写字符串
     * @param $str
     * @return bool|string
     */
    public static function strtoupper($str) {
        if (is_array($str)) {
            return false;
        }
        if (function_exists('mb_strtoupper')) {
            return mb_strtoupper($str, 'utf-8');
        }
        return strtoupper($str);
    }

    /**
     * 截取字符串
     * @param        $str
     * @param        $start
     * @param bool   $length
     * @param string $encoding
     * @return bool|string
     */
    public static function substr($str, $start, $length = false, $encoding = 'utf-8') {
        if (is_array($str) || is_object($str)) {
            return false;
        }
        if (function_exists('mb_substr')) {
            return mb_substr($str, intval($start), ($length === false ? self::strlen($str) : intval($length)), $encoding);
        }
        return substr($str, $start, ($length === false ? Helper::strlen($str) : intval($length)));
    }

    public static function nl2br($str) {
        return preg_replace("/((<br ?\/?>)+)/i", "<br />", str_replace(array("\r\n", "\r", "\n"), "<br />", $str));
    }

    public static function br2nl($str) {
        return str_replace("<br />", "\n", $str);
    }

    public static function ceilf($value, $precision = 0) {
        $precisionFactor = $precision == 0 ? 1 : pow(10, $precision);
        $tmp             = $value * $precisionFactor;
        $tmp2            = (string) $tmp;
        if (strpos($tmp2, '.') === false) {
            return ($value);
        }
        if ($tmp2[strlen($tmp2) - 1] == 0) {
            return $value;
        }
        return ceil($tmp) / $precisionFactor;
    }

    public static function floorf($value, $precision = 0) {
        $precisionFactor = $precision == 0 ? 1 : pow(10, $precision);
        $tmp             = $value * $precisionFactor;
        $tmp2            = (string) $tmp;
        // If the current value has already the desired precision
        if (strpos($tmp2, '.') === false) {
            return ($value);
        }
        if ($tmp2[strlen($tmp2) - 1] == 0) {
            return $value;
        }
        return floor($tmp) / $precisionFactor;
    }

    public static function replaceSpace($url) {
        return urlencode(strtolower(preg_replace('/[ ]+/', '-', trim($url, ' -/,.?'))));
    }

    /**
     * 判断是否64位架构
     *
     * @return bool
     */
    public static function isX86_64arch() {
        return (PHP_INT_MAX == '9223372036854775807');
    }

    /**
     * 获取服务器配置允许最大上传文件大小
     *
     * @param int $max_size
     *
     * @return mixed
     */
    public static function getMaxUploadSize($max_size = 0) {
        $post_max_size       = Helper::convertBytes(ini_get('post_max_size'));
        $upload_max_filesize = Helper::convertBytes(ini_get('upload_max_filesize'));
        if ($max_size > 0) {
            $result = min($post_max_size, $upload_max_filesize, $max_size);
        } else {
            $result = min($post_max_size, $upload_max_filesize);
        }
        return $result;
    }

    public static function convertBytes($value) {
        if (is_numeric($value)) {
            return $value;
        } else {
            $value_length = strlen($value);
            $qty          = (int) substr($value, 0, $value_length - 1);
            $unit         = strtolower(substr($value, $value_length - 1));
            switch ($unit) {
            case 'k':
                $qty *= 1024;
                break;
            case 'm':
                $qty *= 1048576;
                break;
            case 'g':
                $qty *= 1073741824;
                break;
            }
            return $qty;
        }
    }

    /**
     * 获取内存限制
     *
     * @return int
     */
    public static function getMemoryLimit() {
        $memory_limit = @ini_get('memory_limit');
        return Helper::getOctets($memory_limit);
    }

    public static function getOctets($option) {
        if (preg_match('/[0-9]+k/i', $option)) {
            return 1024 * (int) $option;
        }
        if (preg_match('/[0-9]+m/i', $option)) {
            return 1024 * 1024 * (int) $option;
        }
        if (preg_match('/[0-9]+g/i', $option)) {
            return 1024 * 1024 * 1024 * (int) $option;
        }
        return $option;
    }

    public static function object2array(&$object) {
        return json_decode(json_encode($object), true);
    }

    public static function getmicrotime() {
        list($usec, $sec) = explode(" ", microtime());
        return floor($sec + $usec * 1000000);
    }

    /**
     * 根据时间生成图片名
     *
     * @param string $image_type
     *
     * @return float|string
     */
    public static function getTimeImageName($image_type = "image/jpeg") {
        if ($image_type == "image/jpeg" || $image_type == "image/pjpeg") {
            return self::getmicrotime() . ".jpg";
        } elseif ($image_type == "image/gif") {
            return self::getmicrotime() . ".gif";
        } elseif ($image_type == "image/png") {
            return self::getmicrotime() . ".png";
        } else {
            return self::getmicrotime();
        }
    }

    /**
     * 日期计算
     *
     * @param $interval
     * @param $step
     * @param $date
     *
     * @return bool|string
     */
    public static function dateadd($interval, $step, $date) {
        list($year, $month, $day) = explode('-', $date);
        if (strtolower($interval) == 'y') {
            return date('Y-m-d', mktime(0, 0, 0, $month, $day, intval($year) + intval($step)));
        } elseif (strtolower($interval) == 'm') {
            return date('Y-m-d', mktime(0, 0, 0, intval($month) + intval($step), $day, $year));
        } elseif (strtolower($interval) == 'd') {
            return date('Y-m-d', mktime(0, 0, 0, $month, intval($day) + intval($step), $year));
        }
        return date('Y-m-d');
    }

    public static function redirectTo($link) {
        if (strpos($link, 'http') !== false) {
            header('Location: ' . $link);
        } else {
            header('Location: ' . Helper::getHttpHost(true) . '/' . $link);
        }
        exit;
    }

    public static function returnAjaxJson($array) {
        if (!headers_sent()) {
            header("Content-Type: application/json; charset=utf-8");
        }
        echo (json_encode($array));
        ob_end_flush();
        exit;
    }

    /**
     * HackNews热度计算公式
     *
     * @param $time
     * @param $viewcount
     *
     * @return float|int
     */
    public static function getGravity($time, $viewcount) {
        $timegap = ($_SERVER['REQUEST_TIME'] - strtotime($time)) / 3600;
        if ($timegap <= 24) {
            return 999999;
        }
        return round((pow($viewcount, 0.8) / pow(($timegap + 24), 1.2)), 3) * 1000;
    }

    public static function getGravityS($stime, $viewcount) {
        $timegap = ($_SERVER['REQUEST_TIME'] - $stime) / 3600;
        if ($timegap <= 24) {
            return 999999;
        }
        return round((pow($viewcount, 0.8) / pow(($timegap + 24), 1.2)), 3) * 1000;
    }

    /**
     * 优化的file_get_contents操作，超时关闭
     *
     * @param      $url
     * @param bool $use_include_path
     * @param null $stream_context
     * @param int  $curl_timeout
     *
     * @return bool|mixed|string
     */
    public static function file_get_contents($url, $use_include_path = false, $stream_context = null, $curl_timeout = 8) {
        if ($stream_context == null && preg_match('/^https?:\/\//', $url)) {
            $stream_context = @stream_context_create(array('http' => array('timeout' => $curl_timeout)));
        }
        if (in_array(ini_get('allow_url_fopen'), array('On', 'on', '1')) || !preg_match('/^https?:\/\//', $url)) {
            return @file_get_contents($url, $use_include_path, $stream_context);
        } elseif (function_exists('curl_init')) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($curl, CURLOPT_TIMEOUT, $curl_timeout);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $opts = stream_context_get_options($stream_context);
            if (isset($opts['http']['method']) && Helper::strtolower($opts['http']['method']) == 'post') {
                curl_setopt($curl, CURLOPT_POST, true);
                if (isset($opts['http']['content'])) {
                    parse_str($opts['http']['content'], $datas);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $datas);
                }
            }
            $content = curl_exec($curl);
            curl_close($curl);
            return $content;
        } else {
            return false;
        }
    }

    public static function ZipTest($from_file) {
        $zip = new PclZip($from_file);
        return ($zip->privCheckFormat() === true);
    }

    public static function ZipExtract($from_file, $to_dir) {
        if (!file_exists($to_dir)) {
            mkdir($to_dir, 0777);
        }
        $zip  = new PclZip($from_file);
        $list = $zip->extract(PCLZIP_OPT_PATH, $to_dir);
        return $list;
    }

    /**
     * 获取文件扩展名
     *
     * @param $file
     *
     * @return mixed|string
     */
    public static function getFileExtension($file) {
        if (is_uploaded_file($file)) {
            return "unknown";
        }
        return pathinfo($file, PATHINFO_EXTENSION);
    }

    /** TODO 添加支持跨域
     * 以固定格式将数据及状态码返回手机端
     *
     * @param      $code
     * @param      $data
     * @param bool $native
     */
    public static function returnMobileJson($code, $data, $native = false) {
        if (!headers_sent()) {
            header("Content-Type: application/json; charset=utf-8");
        }
        if (is_array($data) && $native) {
            self::walkArray($data, 'urlencode', true);
            echo (urldecode(json_encode(array('code' => $code, 'data' => $data))));
        } elseif (is_string($data) && $native) {
            echo (urldecode(json_encode(array('code' => $code, 'data' => urlencode($data)))));
        } else {
            echo (json_encode(array('code' => $code, 'data' => $data)));
        }
        ob_end_flush();
        exit;
    }

    /**
     * 遍历路径
     *
     * @param        $path
     * @param string $ext
     * @param string $dir
     * @param bool   $recursive
     *
     * @return array
     */
    public static function scandir($path, $ext = 'php', $dir = '', $recursive = false) {
        $path      = rtrim(rtrim($path, '\\'), '/') . '/';
        $real_path = rtrim(rtrim($path . $dir, '\\'), '/') . '/';
        $files     = scandir($real_path);
        if (!$files) {
            return array();
        }
        $filtered_files = array();
        $real_ext       = false;
        if (!empty($ext)) {
            $real_ext = '.' . $ext;
        }
        $real_ext_length = strlen($real_ext);
        $subdir          = ($dir) ? $dir . '/' : '';
        foreach ($files as $file) {
            if (!$real_ext || (strpos($file, $real_ext) && strpos($file, $real_ext) == (strlen($file) - $real_ext_length))) {
                $filtered_files[] = $subdir . $file;
            }
            if ($recursive && $file[0] != '.' && is_dir($real_path . $file)) {
                foreach (Helper::scandir($path, $ext, $subdir . $file, $recursive) as $subfile) {
                    $filtered_files[] = $subfile;
                }
            }
        }
        return $filtered_files;
    }

    public static function sys_get_temp_dir() {
        if (function_exists('sys_get_temp_dir')) {
            return sys_get_temp_dir();
        }
        if ($temp = getenv('TMP')) {
            return $temp;
        }
        if ($temp = getenv('TEMP')) {
            return $temp;
        }
        if ($temp = getenv('TMPDIR')) {
            return $temp;
        }
        $temp = tempnam(__FILE__, '');
        if (file_exists($temp)) {
            unlink($temp);
            return dirname($temp);
        }
        return null;
    }

    /**
     * XSS
     *
     * @param $str
     *
     * @return mixed
     */
    public static function removeXSS($str) {
        $str = str_replace('<!--  -->', '', $str);
        $str = preg_replace('~/\*[ ]+\*/~i', '', $str);
        $str = preg_replace('/\\\0{0,4}4[0-9a-f]/is', '', $str);
        $str = preg_replace('/\\\0{0,4}5[0-9a]/is', '', $str);
        $str = preg_replace('/\\\0{0,4}6[0-9a-f]/is', '', $str);
        $str = preg_replace('/\\\0{0,4}7[0-9a]/is', '', $str);
        $str = preg_replace('/&#x0{0,8}[0-9a-f]{2};/is', '', $str);
        $str = preg_replace('/&#0{0,8}[0-9]{2,3};/is', '', $str);
        $str = preg_replace('/&#0{0,8}[0-9]{2,3};/is', '', $str);
        $str = htmlspecialchars($str);
        // 非成对标签
        $lone_tags = array("img", "param", "br", "hr");
        foreach ($lone_tags as $key => $val) {
            $val = preg_quote($val);
            $str = preg_replace('/&lt;' . $val . '(.*)(\/?)&gt;/isU', '<' . $val . "\\1\\2>", $str);
            $str = self::transCase($str);
            $str = preg_replace_callback('/<' . $val . '(.+?)>/i', create_function('$temp', 'return str_replace("&quot;","\"",$temp[0]);'), $str);
        }
        $str = preg_replace('/&amp;/i', '&', $str);
        // 成对标签
        $double_tags = array("table", "tr", "td", "font", "a", "object", "embed", "p", "strong", "em", "u", "ol", "ul", "li", "div", "tbody", "span", "blockquote", "pre", "b", "font");
        foreach ($double_tags as $key => $val) {
            $val = preg_quote($val);
            $str = preg_replace('/&lt;' . $val . '(.*)&gt;/isU', '<' . $val . "\\1>", $str);
            $str = self::transCase($str);
            $str = preg_replace_callback('/<' . $val . '(.+?)>/i', create_function('$temp', 'return str_replace("&quot;","\"",$temp[0]);'), $str);
            $str = preg_replace('/&lt;\/' . $val . '&gt;/is', '</' . $val . ">", $str);
        }
        // 清理js
        $tags = array(
            'javascript',
            'vbscript',
            'expression',
            'applet',
            'meta',
            'xml',
            'behaviour',
            'blink',
            'link',
            'style',
            'script',
            'embed',
            'object',
            'iframe',
            'frame',
            'frameset',
            'ilayer',
            'layer',
            'bgsound',
            'title',
            'base',
            'font',
        );
        foreach ($tags as $tag) {
            $tag = preg_quote($tag);
            $str = preg_replace('/' . $tag . '\(.*\)/isU', '\\1', $str);
            $str = preg_replace('/' . $tag . '\s*:/isU', $tag . '\:', $str);
        }
        $str = preg_replace('/[\s]+on[\w]+[\s]*=/is', '', $str);
        return $str;
    }

    public static function transCase(string $str): string {
        if (empty($str)) {
            return '';
        }
        $str = preg_replace('/(e|ｅ|Ｅ)(x|ｘ|Ｘ)(p|ｐ|Ｐ)(r|ｒ|Ｒ)(e|ｅ|Ｅ)(s|ｓ|Ｓ)(s|ｓ|Ｓ)(i|ｉ|Ｉ)(o|ｏ|Ｏ)(n|ｎ|Ｎ)/is', 'expression', $str);
        return $str;
    }

    /**
     * @param        $url
     * @param string $method
     * @param null   $postFields
     * @param null   $header
     *
     * @return mixed
     * @throws Exception
     */
    public static function curl($url, $method = 'GET', $postFields = null, $header = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        switch ($method) {
        case 'POST':
            curl_setopt($ch, CURLOPT_POST, true);
            if (!empty($postFields)) {
                if (is_array($postFields) || is_object($postFields)) {
                    if (is_object($postFields)) {
                        $postFields = Helper::object2array($postFields);
                    }
                    $postBodyString = "";
                    $postMultipart  = false;
                    foreach ($postFields as $k => $v) {
                        if ("@" != substr($v, 0, 1)) {
                            //判断是不是文件上传
                            $postBodyString .= "$k=" . urlencode($v) . "&";
                        } else {
                            //文件上传用multipart/form-data，否则用www-form-urlencoded
                            $postMultipart = true;
                        }
                    }
                    unset($k, $v);
                    if ($postMultipart) {
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
                    } else {
                        curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
                    }
                } else {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
                }
            }
            break;
        default:
            if (!empty($postFields) && is_array($postFields)) {
                $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($postFields);
            }
            break;
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        if (!empty($header) && is_array($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        }
        curl_close($ch);
        return $response;
    }

    /**
     * 下载文件保存到指定位置
     *
     * @param $url
     * @param $filepath
     *
     * @return bool
     */
    public static function saveFile($url, $filepath) {
        if (Validate::isAbsoluteUrl($url) && !empty($filepath)) {
            $file = self::file_get_contents($url);
            $fp   = @fopen($filepath, 'w');
            if ($fp) {
                @fwrite($fp, $file);
                @fclose($fp);
                return $filepath;
            }
        }
        return false;
    }

    /**
     * 文件复制
     *
     * @param $source
     * @param $dest
     *
     * @return bool
     */
    public static function copyFile($source, $dest) {
        if (file_exists($dest) || is_dir($dest)) {
            return false;
        }
        return copy($source, $dest);
    }

    /**
     * 判断是否爬虫，范围略大
     *
     * @return bool
     */
    public static function isSpider(): bool {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $ua      = strtolower($_SERVER['HTTP_USER_AGENT']);
            $spiders = array('spider', 'bot');
            foreach ($spiders as $spider) {
                if (strpos($ua, $spider) !== false) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 判断是否命令行执行
     *
     * @return bool
     */
    public static function isCli(): bool {
        if (isset($_SERVER['SHELL']) && !isset($_SERVER['HTTP_HOST'])) {
            return true;
        }
        return false;
    }

    public static function sendToBrowser($file, $delaftersend = true, $exitaftersend = true) {
        if (file_exists($file) && is_readable($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment;filename = ' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check = 0, pre-check = 0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            if ($delaftersend) {
                unlink($file);
            }
            if ($exitaftersend) {
                exit;
            }
        }
    }

    /**计算excel列转化为数值
     * @param $index
     * @return string
     */
    public static function getExcelValue($index) {
        $index = (int) $index;
        if ($index <= 0) {
            return;
        }
        //输入检测
        $dimension = ceil(log(25 * $index + 26, 26)) - 1;
        //算结果一共有几位，实际算的是位数减1，记住是26进制的位数
        $n = $index - 26 * (pow(26, $dimension - 1) - 1) / 25;
        //算结果在所在位数总数中排第几个   d
        $n--; //转化为索引
        return str_pad(
            str_replace(
                array_merge(range(0, 9), range('a', 'p')),
                range('A', 'Z'),
                base_convert($n, 10, 26)
            ),
            $dimension,
            'A',
            STR_PAD_LEFT
        );
    }

    /**
     * @param $arr
     * @return mixed
     */
    public static function _csvEnCoding(array $arr): array
    {
        foreach ($arr as $key => $val) {
            $arr[$key] = iconv('UTF-8', 'GBK//IGNORE', $val);
        }
        return $arr;
    }

    /**
     * @param $title
     * @param $data
     * @param $filename
     */
    public static function outPutCsv($title, $data, $filename) {
        set_time_limit(0);
        ini_set('memory_limit', '2048M');
        header('Content-Type: application/vnd.ms-excel;');
        header("Content-type: application/x-msexcel");
        header("Content-type: text/csv");
        header("content-Disposition:filename=" . $filename . ".csv");
        $fp = fopen("php://output", "w+");
        fputcsv($fp, self::_csvEnCoding($title));

        foreach ($data as $val) {
            $i = 0;
            foreach ($val as $subv) {
                $out[$i] = $subv;
                ++$i;
            }
            fputcsv($fp, self::_csvEnCoding($out));
        }
        fclose($fp);
        exit;
    }

    /**
     * 天转化为一年中的第几周,一年中的第几月
     * @param  [type] $day  [时间]
     * @param  [type] $type [1转化为周,2转化为月]
     * @return [type]       [string]
     */
    public static function dayToWeek(string $day, int $type): string {
        if ($type == 1) {
            return date('oW', strtotime($day));
        } else {
            return date('Ym', strtotime($day));
        }
    }

    /**
     * @doifusd  arrayMultirSort 2019-03-05 16:47:14
     *
     * @Param    $data
     * @Param    $sortkey
     * @Param    $sort
     *
     * @Return
     */
    public static function arrayMultirSort(array $data, string $sortkey, string $sort = 'asc'): array
    {
        $data = array_values($data);
        if (!isset($data[0][$sortkey])) {
            return [];
        }
        $keyArr = array_column($data, $sortkey);
        if ($sort == 'asc') {
            array_multisort($keyArr, SORT_ASC, $data);
        } elseif ($sort == 'desc') {
            array_multisort($keyArr, SORT_DESC, $data);
        }
        return $data;
    }

    /**
     * [arrayMultiSort 根据键值对二维数组有小到大]
     * @param  [type] $data    [description]
     * @param  [type] $sortkey [description]
     * @param  string $start   [description]
     * @param  string $len     [description]
     * @return [type]          [description]
     */
    public static function arrayMultiSort($data, $sortkey, $start = '', $len = ''): array
    {
        foreach ($data as $key => &$value) {
            foreach ($data as $k => $val) {
                if ($value[$sortkey] < $val[$sortkey]) {
                    $tmp        = $val;
                    $data[$k]   = $value;
                    $data[$key] = $tmp;
                }
            }
        }
        if (isset($start) && isset($len)) {
            $data = array_slice($data, $start, $len);
        }
        return $data;
    }

    public static function arraysSum(array...$arrays): array
    {
        return array_map(function (array $array): float {
            return array_sum($array);
        }, $arrays);
    }

    public static function dump($param) {
        die(print_r($param));
    }

    public static function sqlDump($entity) {
        \Doctrine\Common\Util\Debug::dump($entity);
    }

    public static function clearHtml($str = '', array $c_str = []) {
        if (empty($str)) {
            return false;
        }
        $res = preg_replace_callback("/<[^>]+>|(&nbsp;)/", function ($matches) {
            if (!empty($matches)) {
                return '';
            }
        }, $str);
        if ($res) {
            if (!empty($c_str)) {
                $re = str_replace($c_str, '', $res);
                if (is_array($re)) {
                    return join($re);
                } else {
                    return $re;
                }
            } else {
                return $res;
            }
        }
    }

    public static function fileList($path) {
        $data = [];
        if (is_dir($path)) {
            if ($dh = opendir($path)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != '.' && $file != '..' && $file[0] != '.') {
                        $file_path = $path . $file;
                        if (is_file($file_path)) {
                            $tmp = [
                                'name' => $file,
                                'file' => $file_path,
                            ];
                            array_push($data, $tmp);
                        } elseif (is_dir($file_path)) {
                            $this->fileList($file_path);
                        }
                    }
                }
                closedir($dh);
            } else {
                return false;
            }
        } else {
            return false;
        }
        return $data;
    }

    public static function objectArray($array) {
        if (is_object($array)) {
            $array = (array) $array;
        }if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = self::objectArray($value);
            }
        }
        return $array;
    }
}
