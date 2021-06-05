<?php
namespace Jan\Component\Http;


/**
 * Class RequestContext
 * @package Jan\Component\Http
*/
class RequestContext
{

     /**
       * @param string $scheme
       * @param string $host
       * @param string $uri
       * @param string $fragment ( http://localhost:8000/foo?page=1&name=john#target1)
       * @return string
     */
     public function url(string $scheme = 'http', string $host = '', string $uri = '/', string $fragment = ''): string
     {
          return sprintf('%s://%s%s%s', $scheme, $host, $uri, $fragment);
     }
}