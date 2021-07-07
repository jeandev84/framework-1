<?php
namespace Jan\Component\Http\Parser;


use Jan\Component\Http\Encoder\UrlEncoder;

/**
 * Class UrlParser
 *
 * @package Jan\Component\Http\Parser
*/
class UrlParser
{

     /**
      * @var string
     */
     protected $path;



     /**
      * UrlParser constructor.
      * @param string|null $path
     */
     public function __construct(string $path = null)
     {
          if ($path) {
              $this->setPath($path);
          }
     }



     /**
      * @param string $path
     */
     public function setPath(string $path)
     {
         $this->path = UrlEncoder::decode($path);
     }



     /**
      * @return string
     */
     public function getPath(): string
     {
         return $this->path;
     }



     /**
      * Get parsed param by given type
      *
      * @param int $type
      * @return array|false|int|string|null
     */
     public function parse(int $type)
     {
         return parse_url($this->path, $type);
     }



     /**
      * Get parses params
      *
      * @return array|false|int|string|null
     */
     public function parses()
     {
         return parse_url($this->path);
     }
}