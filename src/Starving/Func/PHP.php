<?php

namespace Starving\Func;

class PHP
{
    /**
     * 提取富文本字符串的纯文本
     * @param $string string 需要进行截取的富文本字符串
     * @param $num int 需要截取多少位
     * @return string
     */
    public static function StringToText($string, $num)
    {
        return 'StringToText...';
//        if ($string) {
//            //把一些预定义的 HTML 实体转换为字符
//            $html_string = htmlspecialchars_decode($string);
//            //将空格替换成空
//            $content = str_replace(" ", "", $html_string);
//            //函数剥去字符串中的 HTML、XML 以及 PHP 的标签,获取纯文本内容
//            $contents = strip_tags($content);
//            //返回字符串中的前$num字符串长度的字符
//            return mb_strlen($contents, 'utf-8') > $num ? mb_substr($contents, 0, $num, "utf-8") . '....' : mb_substr($contents, 0, $num, "utf-8");
//        } else {
//            return $string;
//        }
    }
}