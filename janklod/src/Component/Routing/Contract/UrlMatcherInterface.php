<?php
namespace Jan\Component\Routing\Contract;


/**
 * Interface UrlMatcherInterface
 * @package Jan\Component\Routing\Contract
*/
interface UrlMatcherInterface
{
     /**
      * @param string $url
      * @return mixed
     */
     public function matchUrl(string $url);
}