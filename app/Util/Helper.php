<?php
namespace App\Util;


class Helper {

    /**
     * 判断ip是否在指定范围内
     * @param $ip
     * @param $startIp
     * @param $endIp
     * @return bool
     */
    public static function ipRange($ip, $startIp, $endIp):bool
    {
        return ip2long($startIp) * -1 >= ip2long($ip) * -1 && ip2long($endIp) * -1 <= ip2long($ip) * -1;
    }

    /**
     * 秒转时间
     * @param $seconds
     * @return false|string
     */
    public static function secToTime($seconds){
        if ($seconds > 3600) {
            $hours = intval($seconds / 3600);
            $time = $hours . ":" . gmstrftime('%M:%S', $seconds);
        } else {
            $time = gmstrftime('%H:%M:%S', $seconds);
        }
        return $time;
    }

    public static function timeDiff($startTime, $endTime)
    {
        $timediff = abs(strtotime($endTime) - strtotime($startTime));
        //计算天数
        $days = intval($timediff / 86400);

        //计算小时数
        $remain = $timediff % 86400;
        $hours = intval($remain / 3600);

        //计算分钟数
        $remain = $remain % 3600;
        $mins = intval($remain / 60);

        //计算秒数
        $secs = $remain % 60;

        $str = '';
        if($days) {
            $str.= $days.'天';
        }
        if($hours) {
            $str.= $hours.'小时';
        }
        if($mins) {
            $str.= $mins.'分';
        }
        if($secs) {
            $str.= $secs.'秒';
        }

        return $str;
    }

    public static function xcxHtml($input, $cache_id = null, $duration = 300, $dependency = null)
    {
        $format = function ($input) {
            $html = str_replace('<img ', '<img style="max-width:100%;height:auto; display:inline-block;margin-bottom:-6px" ', $input);
            $reg = '/src=["\']((http:\/\/|https:\/\/)[^"^\']+)["\']/i';
            if (preg_match_all($reg, $html, $matches)) {
                foreach ($matches[1] as $key => $img_url) {
                    $query = parse_url($img_url, PHP_URL_QUERY);
                    if($query) {
                        parse_str($query, $query_info);
                        if($query_info['tp'] && $query_info['tp'] == 'webp' && $query_info['wx_fmt']) {
                            $replace_img = str_replace('tp=webp', 'tp='.$query_info['wx_fmt'], $matches[0][$key]);
                            $html = str_replace($matches[0][$key], $replace_img, $html);
                        } else {
                            $amp_query = str_replace('&amp;', '&', $query);
                            parse_str($amp_query, $query_info);
                            if($query_info['tp'] && $query_info['tp'] == 'webp' && $query_info['wx_fmt']) {
                                $replace_img = str_replace('tp=webp', 'tp='.$query_info['wx_fmt'], $matches[0][$key]);
                                $replace_img = str_replace('&amp;', '&', $replace_img);
                                $html = str_replace($matches[0][$key], $replace_img, $html);
                            }
                        }
                    }
                    //wx_fmt=gif&tp=webp
                }
            }

            $html = preg_replace('/\<article([^>]*)\>/i', '<div$1>', $html);
            $html = preg_replace('/\<\/article([^>]*)\>/i', '</div$1>', $html);

            $html = preg_replace('/\<section([^>]*)\>/i', '<div$1>', $html);
            $html = preg_replace('/\<\/section([^>]*)\>/i', '</div$1>', $html);

            return $html;
        };
        $html = $format($input);

        return $html;
    }
    private static function compareByTimeStamp($time1, $time2)
    {
        if (strtotime($time1) > strtotime($time2))
            return 1;
        else if (strtotime($time1) < strtotime($time2))
            return -1;
        else
            return 0;
    }

    public static function sortDate($date_list){
        usort($date_list, 'self::compareByTimeStamp');
        return $date_list;
    }

    /**
     * 分转元
     * @param $value
     * @return string
     */
    public static function fen2yuan($value):string
    {
        return sprintf('%.02f', $value / 100);
    }

    /**
     * 元转分
     * @param $value
     * @return int
     */
    public static function multiply100($value):int
    {
        return intval(round(floatval($value) * 100));
    }

    public static function passwordMask($str,$start,$end=0,$dot="*",$charset="UTF-8"){
        $len = mb_strlen($str,$charset);

        if($start==0||$start>$len){
        $start = 1;

        }

        if($end!=0&&$end>$len){
        $end = $len-2;

        }

        $endStart = $len-$end;

        $top = mb_substr($str, 0,$start,$charset);

        $bottom = "";

        if($endStart>0){
        $bottom = mb_substr($str, $endStart,$end,$charset);

        }

        $len = $len-mb_strlen($top,$charset);

        $len = $len-mb_strlen($bottom,$charset);

        $newStr = $top;

        for($i=0;$i<$len;$i++){
            $newStr.=$dot;
        }

        $newStr.=$bottom;

        return $newStr;

    }

    /**
     * url链接的参数转数组
     * @param $query
     * @return array
     */
    public static function convertUrlArray($query)
    {
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }
        return $params;
    }

    /**
     * 获取当前时间是多久前
     * @param $time
     * @return string
     */
    public static function getTimeAgo($time){
        $now = time();
        $d_time = strtotime($time);
        $diff_time = $now - $d_time;
        if($diff_time<30){
            $t_msg = '刚刚';
        }
        elseif($diff_time<60*60){
            // 1个小时内的
            $t_msg = (int)($diff_time/60).'分钟前';
        }elseif($diff_time<24*60*60){
            // 1天内
            $t_msg = (int)($diff_time/(60*60)).'小时前';
        }elseif($diff_time>=24*60*60){
            $t_msg = (int)($diff_time/(24*60*60)).'天前';
        }
        return $t_msg;
    }

    public static function getIPAddress()
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $realip = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } else if (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }
        return $realip;
    }

    /**
     * 计算两地距离(米)
     * @param $lng1
     * @param $lat1
     * @param $lng2
     * @param $lat2
     * @return int
     */
    public static function distance($lng1, $lat1, $lng2, $lat2)
    {
        $fEARTH_RADIUS = 6378137; //m
        //角度换算成弧度
        $fRadLon1 = deg2rad($lng1);
        $fRadLon2 = deg2rad($lng2);
        $fRadLat1 = deg2rad($lat1);
        $fRadLat2 = deg2rad($lat2);

        //计算经纬度的差值
        $fD1 = abs($fRadLat1 - $fRadLat2);
        $fD2 = abs($fRadLon1 - $fRadLon2);

        //距离计算
        $fP = pow(sin($fD1/2), 2) +
            cos($fRadLat1) * cos($fRadLat2) * pow(sin($fD2/2), 2);

        return intval($fEARTH_RADIUS * 2 * asin(sqrt($fP)) + 0.5);
    }

    /**
     * 过滤null值数组
     * @param $arrayData
     * @return array
     */
    public static function filterNull($arrayData): array
    {
        foreach($arrayData as $key => $data) {
            if($data === null) {
                unset($arrayData[$key]);
            }
        }
        return $arrayData;
    }
}
