<?php
namespace Jan\Component\Http\Middleware\Contract;


use Jan\Component\Http\Request;
use Jan\Component\Http\Response;

/**
 * Interface MiddlewareInterface
 * @package Jan\Component\Http\Middleware\Contract
*/
interface MiddlewareInterface
{

    /**
     * @param Request $request
     * @param callable $next
     * @return mixed
    */
    public function __invoke(Request $request, callable $next);



    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
    */
    public function terminate(Request $request, Response $response);
}