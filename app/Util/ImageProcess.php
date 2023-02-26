<?php

namespace App\Util;

use GuzzleHttp\Client;

class ImageProcess {
    private function pc_ImageTTFCenter($image, $text, $font, $size) {

        // find the size of the image
        $xi = ImageSX($image);
        $yi = ImageSY($image);

        // find the size of the text
        $box = ImageTTFBBox($size, 0, $font, $text);

        $xr = abs(max($box[2], $box[4]));
        $yr = abs(max($box[5], $box[7]));

        // compute centering
        $x = intval(($xi - $xr) / 2);
        $y = intval(($yi + $yr) / 2);

        $height = $box[1] - $box[7];

        return array($x, $y, abs($height), $yr);
    }


    private function str_split_unicode($str, $length = 1) {
        $tmp = preg_split('~~u', $str, -1, PREG_SPLIT_NO_EMPTY);
        if ($length > 1) {
            $chunks = array_chunk($tmp, $length);
            foreach ($chunks as $i => $chunk) {
                $chunks[$i] = join('', (array) $chunk);
            }
            $tmp = $chunks;
        }
        return $tmp;
    }

    /**
     * @param resource $image 一个GD画布资源
     * @param string $font 字体路径
     * @param int $fontSize 字体大小
     * @param int $fontColor GD颜色 imagecolorallocate 创建出来的
     * @param $angle
     * @param $text
     * @param int $max_width 文字框宽度
     * @param int $lineHeight 行高
     * @param int $box_x_pos 文字框左上角x坐标
     * @param int $box_y_pos 文字框左上角y坐标
     * @param int $align 0左 1中 2右
     * @param string $next_line
     * @return array 返回最下方的位置和高度
     */
    public function textBox($image, $font, $fontSize, $fontColor, $angle, $text, $max_width, $lineHeight = 1, $box_x_pos = 0, $box_y_pos = 0, $align = 0, $next_line = '')
    {
        $isOtf = substr(strtolower($font), -3) == 'otf'?true:false;

        if(!$image) {
            $image = imagecreate($max_width, 1);
            $box_x_pos = 0;
            $box_y_pos = 0;
        }
        if(is_string($image)) {
            $image_array = explode('.',$image);
            $count = count($image_array);
            if($image_array[$count-1]=='jpeg' || $image_array[$count-1]=='jpg'){
                $image = imagecreatefromjpeg($image);
            }elseif ($image_array[$count-1]=='gif'){
                $image = imagecreatefromgif($image);
            }else{
                $image = imagecreatefrompng($image);
            }
        }

        if($fontColor[0]=='#'){
            $color = str_replace('#', '', $fontColor);
            if (strlen($color) > 3) {
                $rgb = array(
                    'r' => hexdec(substr($color, 0, 2)),
                    'g' => hexdec(substr($color, 2, 2)),
                    'b' => hexdec(substr($color, 4, 2))
                );
            } else {
                $color = $fontColor;
                $r = substr($color, 0, 1) . substr($color, 0, 1);
                $g = substr($color, 1, 1) . substr($color, 1, 1);
                $b = substr($color, 2, 1) . substr($color, 2, 1);
                $rgb = array(
                    'r' => hexdec($r),
                    'g' => hexdec($g),
                    'b' => hexdec($b)
                );
            }
            $fontColor = imagecolorexact($image,$rgb['r'],$rgb['g'],$rgb['b']);
        }

        $real_ori = $box_y_pos;

        $split = $this->str_split_unicode($text);
        $string = "";
        $new_string = "";
        $empty_text = str_replace("\n", '', $text);
        if($isOtf) {
            $box = imageftbbox($fontSize, 0, $font, $empty_text);
        } else {
            $box = imagettfbbox($fontSize, 0, $font, $empty_text);
        }
        $font_offset = $box[5];
        $box_y_pos -= $font_offset;
        $ori_y = $box_y_pos;

        $single_line = $box[3]-$box[5];
        $line_height = $single_line*$lineHeight;
        $count = 0;
        $lines = 0;

        for ($i = 0; $i < count($split); $i++)
        {
            if($split[$i] == "\n") {
                $break = true;
            } else {
                $break = false;
            }
            if(!$break) {
                $new_string.= $split[$i] . "";
            }

            // check size of string
            if($isOtf) {
                $tbbox = imageftbbox($fontSize, 0, $font, $new_string);
            } else {
                $tbbox = imagettfbbox($fontSize, 0, $font, $new_string);
            }


            if ($tbbox[4] < $max_width && !$break)
            {
                $string = $new_string;
                //$box_y_pos = $tbbox[3] + $line_height; // change this to adjust line-height.
            }
            else
            {
                if($break) {
                    $i--;
                }

                if($isOtf) {
                    $bbox = imageftbbox($fontSize, 0, $font, $string);
                } else {
                    $bbox = imagettfbbox($fontSize, 0, $font, $string);
                }

                $new_string = "";
                $width = $bbox[2] - $bbox[0];
                $height =  $bbox[3]-$bbox[5];
                if($line_height > $height && $count > 0){
                    $box_y_pos += ($height*$lineHeight);
                }

                if($align == 1) {
                    //居中
                    $left_point = $box_x_pos+intval(($max_width - $width)/2);
                } elseif($align == 2) {
                    //居右
                    $left_point = $box_x_pos+($max_width-$width);
                } else {
                    $left_point = $box_x_pos;
                }

                if($isOtf) {
                    $tb = imagefttext($image, $fontSize, $angle, $left_point, $box_y_pos, $fontColor, $font, $string);
                } else {
                    $tb = imagettftext($image, $fontSize, $angle, $left_point, $box_y_pos, $fontColor, $font, $string);
                }
                $i--;
                $lines ++;

                $height =  $tb[3]-$tb[5];
                if($height < $line_height){
                    $box_y_pos += $height*$lineHeight;
                }
                $string = "";
                if ($next_line !== '')
                {
                    $max_width += ($box_x_pos - $next_line);
                    $box_x_pos = $next_line;
                }

                $box_y_pos = $tb[3] + $height*$lineHeight; // change this to adjust line-height.
            }
        }
        if($string!=='') {
            if($isOtf) {
                $bbox = imageftbbox($fontSize, 0, $font, $new_string);
            } else {
                $bbox = imagettfbbox($fontSize, 0, $font, $new_string);
            }

            $width = $bbox[2] - $bbox[0];
            $height =  $bbox[3]-$bbox[5];
            if($line_height > $height && $count > 0){
                $box_y_pos += ($height*$lineHeight);
            }

            if($align == 1) {
                //居中
                $left_point = $box_x_pos+intval(($max_width - $width)/2);
            } elseif($align == 2) {
                //居右
                $left_point = $box_x_pos+($max_width-$width);
            } else {
                $left_point = $box_x_pos;
            }

            if($isOtf) {
                imagefttext($image, $fontSize, $angle, $left_point, $box_y_pos, $fontColor, $font, $string); // "draws" the rest of the string
            } else {
                imagettftext($image, $fontSize, $angle, $left_point, $box_y_pos, $fontColor, $font, $string); // "draws" the rest of the string
            }
            $lines ++;
        }


        $total_height = $lines*$line_height;

        return ['bottom'=>$real_ori+$total_height, 'height'=>$total_height, 'gd'=>$image];
    }


    public function loadRemote($path)
    {
        $is_remote = false;
        if(stripos($path, 'http://')===0 || stripos($path, 'https://')===0) {
            $url = $path;
            $is_remote = true;
        } elseif(!is_file($path)) {
            $url = \Storage::url($path);
            $is_remote = true;
        } else {
            $url = $path;
        }
        if($is_remote) {
            $client = new Client();
            $res = $client->request('GET', $url);
            $content = $res->getBody()->getContents();
        } else {
            $content = file_get_contents($url);
        }

        $file_path = parse_url($url, PHP_URL_PATH);

        $ext = strtolower(pathinfo ( $file_path, PATHINFO_EXTENSION ));


        $temp_dir = base_path('storage/runtime');
        if(!is_dir($temp_dir)) {
            mkdir($temp_dir, 0777, true);
        }
        $temp_path = $temp_dir.'/'.uniqid(date('YmdHis')).'.'.$ext;

        file_put_contents($temp_path, $content);

        if($ext == 'jpg' || $ext == 'jpeg') {
            $canvas = imagecreatefromjpeg($temp_path);
        }elseif($ext == 'png') {
            try{
                $canvas = imagecreatefrompng($temp_path);
            }catch (\Exception $e) {
                $canvas = imagecreatefromjpeg($temp_path);
            }
        } else {
            $canvas = imagecreatefromjpeg($temp_path);
        }

        @unlink($temp_path);

        return $canvas;
    }


    /**
     * 把 URL 地址的图片帖到指定地址去
     * @param $canvas
     * @param $url
     * @param $top_left_x
     * @param $top_left_y
     * @param $width
     * @param $height
     * @param int $round 是否转成圆形图片
     * @throws \Exception
     */
    public function pasteImage($canvas, $url, $top_left_x, $top_left_y, $width, $height, $round = 0)
    {
        try{
            $src_image = $this->loadRemote($url);
            $src_height = imagesy($src_image);
            $src_width = imagesx($src_image);
            if($round) {
                $src_image = $this->roundImage($src_image);
            }
            imagecopyresampled( $canvas , $src_image , $top_left_x , $top_left_y , 0 , 0 , $width , $height , $src_width , $src_height);

            imagedestroy($src_image);
        }catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * 把图片变圆形
     * @param resource $src_img
     * @return resource
     */
    public function roundImage($src_img) {
        $w   = imagesx($src_img);
        $h   = imagesy($src_img);
        $w   = min($w, $h);
        $h   = $w;
        $img = imagecreatetruecolor($w, $h);
        //这一句一定要有
        imagesavealpha($img, true);
        //拾取一个完全透明的颜色,最后一个参数127为全透明
        $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $bg);
        $r   = $w / 2; //圆半径
        $y_x = $r; //圆心X坐标
        $y_y = $r; //圆心Y坐标
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgbColor = imagecolorat($src_img, $x, $y);
                if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                    imagesetpixel($img, $x, $y, $rgbColor);
                }
            }
        }

        imagedestroy($src_img);

        return $img;
    }
}
