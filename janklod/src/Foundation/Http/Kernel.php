<?php
namespace Jan\Foundation\Http;


use Exception;
use Jan\Component\Http\Request;
use Jan\Component\Http\Response;
use Jan\Contract\Http\Kernel as HttpKernelContract;



/**
 * Class Kernel
 *
 * @package Jan\Foundation\Http
*/
class Kernel implements HttpKernelContract
{

    /**
     * @param Request $request
     * @return Response
     * @throws Exception
    */
    public function handle(Request $request): Response
    {
        try {

            $response = new Response('Loaded data from server ...');

        } catch (\Exception $e) {

            $response = new Response('Server error', $e->getCode());
        }

        return $response;
    }



    /**
     * @param Request $request
     * @param Response $response
     * @return void
     * @throws Exception
    */
    public function terminate(Request $request, Response $response)
    {
         $request->setMethod('PUT');
         dump($request);
         dump($request->url());
         $response->sendBody();
    }
}