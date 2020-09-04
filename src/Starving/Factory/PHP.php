<?php

namespace Starving\Factory;

class PHP
{
    /**
     * @param $idCard
     * @return bool
     */
    public static function checkIdCard($idCard)
    {
        if (strlen($idCard) != 18) {
            return false;
        }
        $idCard_base = substr($idCard, 0, 17);
        $verify_code = substr($idCard, 17, 1);
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        $verify_code_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        $total = 0;
        for ($i = 0; $i < 17; $i++) {
            $total += substr($idCard_base, $i, 1) * $factor[$i];
        }
        $mod = $total % 11;
        if ($verify_code == $verify_code_list[$mod]) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 提取富文本字符串的纯文本
     *
     * @param $string string 需要进行截取的富文本字符串
     * @param $num int 需要截取多少位
     * @return string
     */
    public static function stringToText($string, $num)
    {
        if ($string) {
            $html_string = htmlspecialchars_decode($string);
            $content = str_replace(" ", "", $html_string);
            $contents = strip_tags($content);
            return mb_strlen($contents, 'utf-8') > $num ? mb_substr($contents, 0, $num, "utf-8") . '....' : mb_substr($contents, 0, $num, "utf-8");
        } else {
            return $string;
        }
    }

    /**
     * 秒数转日期格式
     *
     * @param $second
     * @return string
     */
    public static function timeToDateString($second)
    {
        $day = floor($second / (3600 * 24));
        $second = $second % (3600 * 24);
        $hour = floor($second / 3600);
        $second = $second % 3600;
        $minute = floor($second / 60);
        $second = $second % 60;
        return $day . '天' . $hour . '小时' . $minute . '分' . $second . '秒';
    }

    /**
     * 手机号码星号处理
     *
     * @param $mobile
     * @return mixed
     */
    public static function replaceMobile($mobile)
    {
        if (!empty($mobile)) {
            return substr_replace($mobile, '****', 3, 4);
        } else {
            return '';
        }
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
    public static function convertUnderline($str)
    {
        $str = preg_replace_callback('/([-_]+([a-z]{1}))/i', function ($matches) {
            return strtoupper($matches[2]);
        }, $str);
        return $str;
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
    public static function humpToLine($str)
    {
        $str = preg_replace_callback('/([A-Z]{1})/', function ($matches) {
            return '_' . strtolower($matches[0]);
        }, $str);
        return $str;
    }

    /**
     * 下划线转驼峰
     *
     * @param array $data
     * @return array
     */
    public static function convertHump(array $data)
    {
        $result = [];
        foreach ($data as $key => $item) {
            if (is_array($item) || is_object($item)) {
                $result[self::convertUnderline($key)] = self::convertHump((array)$item);
            } else {
                $result[self::convertUnderline($key)] = $item;
            }
        }
        return $result;
    }

    /**
     * 驼峰转下划线
     *
     * @param array $data
     * @return array
     */
    public static function convertLine(array $data)
    {
        $result = [];
        foreach ($data as $key => $item) {
            if (is_array($item) || is_object($item)) {
                $result[self::humpToLine($key)] = self::convertLine((array)$item);
            } else {
                $result[self::humpToLine($key)] = $item;
            }
        }
        return $result;
    }

    /**
     * 传入日期格式或时间戳格式时间，返回与当前时间的差距，如1分钟前，2小时前，5月前，3年前等
     *
     * @param string or int $date 分两种日期格式"2013-12-11 14:16:12"或时间戳格式"1386743303"
     * @param int $type $type = 1为时间戳格式，$type = 2为date时间格式
     * @return string
     */
    public static function formatTime($date, $type = 1)
    {
        date_default_timezone_set('PRC'); //设置成中国的时区
        switch ($type) {
            case 1:
                //$date时间戳格式
                $second = time() - $date;
                $minute = floor($second / 60) ? floor($second / 60) : 1; //得到分钟数
                if ($minute >= 60 && $minute < (60 * 24)) { //分钟大于等于60分钟且小于一天的分钟数，即按小时显示
                    $hour = floor($minute / 60); //得到小时数
                } elseif ($minute >= (60 * 24) && $minute < (60 * 24 * 30)) { //如果分钟数大于等于一天的分钟数，且小于一月的分钟数，则按天显示
                    $day = floor($minute / (60 * 24)); //得到天数
                } elseif ($minute >= (60 * 24 * 30) && $minute < (60 * 24 * 365)) { //如果分钟数大于等于一月且小于一年的分钟数，则按月显示
                    $month = floor($minute / (60 * 24 * 30)); //得到月数
                } elseif ($minute >= (60 * 24 * 365)) { //如果分钟数大于等于一年的分钟数，则按年显示
                    $year = floor($minute / (60 * 24 * 365)); //得到年数
                }
                break;
            case 2:
                //$date为字符串格式 2013-06-06 19:16:12
                $date = strtotime($date);
                $second = time() - $date;
                $minute = floor($second / 60) ? floor($second / 60) : 1; //得到分钟数
                if ($minute >= 60 && $minute < (60 * 24)) { //分钟大于等于60分钟且小于一天的分钟数，即按小时显示
                    $hour = floor($minute / 60); //得到小时数
                } elseif ($minute >= (60 * 24) && $minute < (60 * 24 * 30)) { //如果分钟数大于等于一天的分钟数，且小于一月的分钟数，则按天显示
                    $day = floor($minute / (60 * 24)); //得到天数
                } elseif ($minute >= (60 * 24 * 30) && $minute < (60 * 24 * 365)) { //如果分钟数大于等于一月且小于一年的分钟数，则按月显示
                    $month = floor($minute / (60 * 24 * 30)); //得到月数
                } elseif ($minute >= (60 * 24 * 365)) { //如果分钟数大于等于一年的分钟数，则按年显示
                    $year = floor($minute / (60 * 24 * 365)); //得到年数
                }
                break;
            default:
                break;
        }
        if (empty($date)) {
            return '--';
        } else {
            if (isset($year)) {
                return $year . '年前';
            } elseif (isset($month)) {
                return $month . '月前';
            } elseif (isset($day)) {
                return $day . '天前';
            } elseif (isset($hour)) {
                return $hour . '小时前';
            } elseif (isset($minute)) {
                return $minute . '分钟前';
            }
        }

    }

    /**
     * 获取随机字符串
     *
     * @param int $length 长度
     * @param string $type 类型
     * @param int $convert 转换大小写
     * @return string 随机字符串
     */
    public static function random($length = 6, $type = 'string', $convert = 0)
    {
        $config = array(
            'number' => '1234567890',
            'letter' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'string' => 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
            'all' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
        );

        if (!isset($config[$type]))
            $type = 'string';
        $string = $config[$type];

        $code = '';
        $strLen = strlen($string) - 1;
        for ($i = 0; $i < $length; $i++) {
            $code .= $string{mt_rand(0, $strLen)};
        }
        if (!empty($convert)) {
            $code = ($convert > 0) ? strtoupper($code) : strtolower($code);
        }

        return $code;
    }
}