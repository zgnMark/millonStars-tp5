<?php

// 应用公共文件
if (!function_exists('passwd')) {
    /**
     * 密码加密
     * @param unknown $str
     */
    function passwd($str)
    {
        return md5(md5($str));
    }
}
/**
 * 下划线转驼峰
 * 思路:
 * step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
 * step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
 */
function camelize($uncamelizedWords, $separator = '_')
{
    //开头小写
    //$uncamelizedWords = $separator . str_replace($separator, " ", strtolower($uncamelizedWords));
    //开头也大写,不改变原有大写
    $uncamelizedWords = str_replace($separator, " ", $uncamelizedWords);

    return ltrim(str_replace(" ", "", ucwords($uncamelizedWords)), $separator);
}

/**
 * 驼峰命名转下划线命名
 * 思路:
 * 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
 */
function uncamelize($camelCaps, $separator = '_')
{
    return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
}
/**
 * 获取关部
 */
if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

if (!function_exists('disnull')) {

    /**
     * 密码加密
     * @param unknown $str
     */
    function disnull($where)
    {
        $where = array_filter($where, function ($v) {
            if (is_numeric($v) && ($v == 0)) {
                return true;
            }
            return !empty($v);
        });
    }
}
