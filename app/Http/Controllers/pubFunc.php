<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class pubFunc extends Controller
{
    public static function stringInsert($str,$insertstr,$pos)
    {
        $str = substr($str, 0, $pos) . $insertstr . substr($str, $pos);
        return $str;
    }
    public static function randr($j = 8){
        $string = "";
        for($i=0; $i < $j; $i++){
            $x = mt_rand(0, 2);
            switch($x){
                case 0: $string.= chr(mt_rand(97,122));break;
                case 1: $string.= chr(mt_rand(65,90));break;
                case 2: $string.= chr(mt_rand(48,57));break;
            }
        }
        return $string;
    }
    public static function randId($id){
        $strlnId = strlen($id)*2;
        if($strlnId < 4)
            $strlnId = $strlnId+3;
        if($strlnId > 6)
            $strlnId = $strlnId-4;
        $randst = pubFunc::randr( $strlnId );
        settype($id,"String");
        $arr = str_split($id,1);
        $randId = '';$i=-1;
        foreach ($arr as $index) {
            $i=$i+2;
            $randst = pubFunc::stringInsert($randst , $index , $i);
        }
        return $randst;
    }

    public static function relativeTime( $time, $format = 'Y-m-d H:i' ){
        $time = strtotime($time);
        $dif = time() - $time;
        $dateArray = array(
            "ثانیه" => 60,     // 60 Seconds in 1 Minute
            "دقیقه" => 60,     // 60 Minutes in 1 Hour
            "ساعت" => 24,       // 24 Hours in 1 Day
            "روز" => 7,         // 7 Days in 1 Week
            "هفته" => 4,        // 4 Weeks in 1 Month
            "ماه" => 12,      // 12 Months in 1 Year
            "سال" => 5        // max: 5 Years
        );
        if( $dif <= 15 ) return "لحظاتی پیش";
        foreach( $dateArray as $key => $item ){
            if($dif < $item)
                return $dif . ' ' . $key . ( $dif == 1? '' : ' ' ) . ' پیش';
            $dif = round( $dif/$item );
        }
        return date( $format, $time );
    }

    public static function cuttext($texto,$tamanho ) {
        $texto = htmlspecialchars($texto);
        if (strlen($texto) > $tamanho) {
            $texto = substr($texto, 0, $tamanho);
            $end = strrpos($texto," ");
            $texto = substr($texto, 0,$end);
            $texto .= " ...";
        }
        return $texto;
    }

    public static function putPermanentEnv($key, $value)
    {
        $path = app()->environmentFilePath();

        $escaped = preg_quote('='.env($key), '/');

        file_put_contents($path, preg_replace(
            "/^{$key}{$escaped}/m",
            "{$key}={$value}",
            file_get_contents($path)
        ));
    }
}
