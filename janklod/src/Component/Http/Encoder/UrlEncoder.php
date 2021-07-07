<?php
namespace Jan\Component\Http\Encoder;


/**
 * Class UrlEncoder
 * @package Jan\Component\Http\Encoder
*/
class UrlEncoder
{

     /**
      * @param string $url
      * @return string
     */
     public static function encode(string $url): string
     {
         return urlencode($url);
     }



     /**
      * @param string $url
      * @return string
     */
     public static function decode(string $url): string
     {
         return urldecode($url);
     }
}