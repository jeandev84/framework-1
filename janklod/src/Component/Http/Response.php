<?php
namespace Jan\Component\Http;


use Jan\Component\Http\Bag\HeaderBag;
use Jan\Component\Http\Contract\ResponseInterface;


/**
 * Class Response
 *
 * @package Jan\Component\Http
*/
class Response implements ResponseInterface
{

     use StatusCode;


     /**
       * Http protocol version
      *
       * @var string
      */
      protected $protocol = 'HTTP/1.0';


      /**
       * Content of response
       *
       * @var string
      */
      protected $content;



      /**
        * Status code of response
        *
        * @var int
      */
      protected $status;



      /**
        * Headers bag
        *
        * @var HeaderBag
      */
      public $headers;



      /**
       * Response constructor.
       *
       * @param string|null $content
       * @param int $status
       * @param array $headers
      */
      public function __construct(string $content = null, int $status = 200, array $headers = [])
      {
           $this->setContent($content);
           $this->setStatus($status);
           $this->headers = new HeaderBag($headers);
      }


      /**
       * set http protocol version
       *
       * @param $protocol
      */
      public function setProtocol($protocol)
      {
           $this->protocol = $protocol;
      }


      /**
       * get protocol version
       *
       * @return string
      */
      public function getProtocol(): string
      {
          return $this->protocol;
      }



      /**
       * set response content
       *
       * @param $content
      */
      public function setContent($content)
      {
           $this->content = $content;
      }


      /**
       * get response content
       *
       * @return string|null
      */
      public function getContent()
      {
          return $this->content;
      }



      /**
       * set response code status
       *
       * @param int $status
      */
      public function setStatusCode(int $status)
      {
           $this->status = $status;
      }



      /**
       * get response code status
       *
       * @return int
      */
      public function getStatusCode(): int
      {
          return $this->status;
      }



      /**
       * set response header
       *
       * @param $key
       * @param $value
      */
      public function setHeader($key, $value = null)
      {
          $this->headers->parse($key, $value);
      }



      /**
       * Get response headers
       *
       * @return array
      */
      public function getHeaders(): array
      {
          return $this->headers->all();
      }



     /**
       * @return string
     */
     public function getMessage(): string
     {
        return $this->messages[$this->status] ?? '';
     }




    /**
       * @param $headers
       * @return Response
      */
      public function withHeaders($headers): Response
      {
          $this->headers->merge($headers);

          return $this;
      }




      /**
       * @param int $status
       * @return $this
      */
      public function withStatus($status): Response
      {
          $this->setStatus($status);

          return $this;
      }



      /**
       * @param $content
       * @return $this
      */
      public function withBody($content): Response
      {
         $this->setContent($content);

         return $this;
      }



      /**
       * @param $version
       * @return $this
      */
      public function withProtocol($version): Response
      {
          $this->setProtocol($version);

         return $this;
      }


      /**
       * @param array $data
       * @return Response
      */
      public function toJson(array $data): Response
      {
           $this->setHeader('Content-Type', 'application/json');
           return $this->withBody(\json_encode($data));
      }


      /**
       * send response headers
       *
       * @return void
      */
      public function sendHeaders()
      {
          foreach ($this->getHeaders() as $key => $value) {
              header(\is_numeric($key) ? $value : $key .' : ' . $value);
          }
      }


      /**
       * send response body
       *
       * @return void
      */
      public function sendBody()
      {
          echo $this->getContent();
      }


      /**
       * send status message of response
       *
       * @return mixed
      */
      public function sendStatusMessage()
      {
           if(! $message = $this->getMessage()) {
                http_response_code($this->status);
                return $this;
           }

           $this->setHeader(
              sprintf('%s %s %s', $this->protocol, $this->status, $message)
           );
      }


      /**
       * send all information to the server
       *
       * @return mixed
      */
      public function send()
      {
          if(\headers_sent()) {
              return $this;
          }

          if (\php_sapi_name() == 'cli') {
              return false;
          }

          $this->sendStatusMessage();
          $this->sendHeaders();
      }


      /**
       * @return string
      */
      public function __toString()
      {
           return (string) $this->getContent();
      }
}