<?php
namespace Jan\Component\Http;


/**
 * Class RedirectResponse
 * @package Jan\Component\Http
*/
class RedirectResponse extends Response
{

      /**
       * @var string
      */
      protected $url;




      /**
       * RedirectResponse constructor.
       *
       * @param string $url
       * @param int $status
       * @param array $headers
      */
      public function __construct(string $url, int $status = 302, array $headers = [])
      {
          parent::__construct('', $status, $headers);

          $this->url = $url;
      }


      /**
       * @param string $url
       * @return $this
      */
      public function setUrl(string $url): RedirectResponse
      {
          $this->url = $url;

          $this->setContent(
              sprintf('<!DOCTYPE html>
              <html>
                 <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="refresh" content="0;url=\'%s\'">
                    
                    <title>Redirecting to %s</title>
                 </head>
                 <body>
                     Redirecting to <a href="%s">link</a>
                 </body>
              </html>
              ', htmlspecialchars($url, \ENT_QUOTES, 'UTF-8')));

              $this->headers->set('Location', $url);

              return $this;
      }
}