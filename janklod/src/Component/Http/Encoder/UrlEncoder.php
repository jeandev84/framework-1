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
     public static function encode(string $url)
     {
         return urlencode($url);
     }



     /**
      * @param string $url
      * @return string
     */
     public static function decode(string $url)
     {
         return urldecode($url);
     }
}