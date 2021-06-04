<?php
namespace Jan\Component\Http\Helper;


/**
 * Class UrlHelper
 *
 * @package Jan\Component\Http\Helper
*/
class UrlHelper
{

      /**
       * @var string
      */
      protected $url;


      /**
       * ParseUrlHelper constructor.
       * @param string $url
      */
      public function __construct(string $url)
      {
            $this->url = $url;
      }



      /**
       * @return mixed
      */
      public function getParses()
      {
          return parse_url($this->url);
      }


      /**
       * @param int $type
       * @return mixed
      */
      public function getParse(int $type)
      {
          return parse_url($this->url, $type);
      }
}