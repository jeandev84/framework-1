<?php
namespace Jan\Component\Http;


/**
 * Class JsonResponse
 * @package Jan\Component\Http
*/
class JsonResponse extends Response
{


     /**
      * @var array
     */
     protected $data;


     /**
      * JsonResponse constructor.
      * @param array $data
      * @param int $status
      * @param array $headers
      * @throws \Exception
     */
     public function __construct($data, int $status = 200, array $headers = [])
     {
         $content = \is_array($data) ? \json_encode($data) : (string) $data;

         if(\json_last_error() != JSON_ERROR_NONE) {
             throw new \Exception('error json content.');
         }

         $headers = array_merge(['Content-Type' => 'application/json'], $headers);
         parent::__construct($content, $status, $headers);
     }


     /**
      * @param array $data
     */
     public function setData(array $data)
     {
          $json = \json_encode($data);
          parent::setContent($json);
     }
}