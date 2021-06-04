<?php
namespace Jan\Component\Routing\Contract;


/**
 * Interface RouteMatcherInterface
 * @package Jan\Component\Routing\Contract
*/
interface RouteMatcherInterface
{
    /**
     * @param $requestMethod
     * @param $requestUri
     * @return mixed
    */
    public function match($requestMethod, $requestUri);
}