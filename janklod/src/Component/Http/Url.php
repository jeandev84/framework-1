<?php
namespace Jan\Component\Http;


use Jan\Component\Http\Helper\UrlHelper;


/**
 * Class Uri
 * @package Jan\Component\Http
*/
class Url
{


     /**
      * @var string
     */
     protected $scheme;


      /**
       * @var mixed
      */
      protected $user;


      /**
       * @var mixed
      */
      protected $password;


      /**
       * @var string
      */
      protected $host;



     /**
      * @var string
     */
     protected $port;



     /**
      * @var string
     */
     protected $path;



     /**
      * @var string
     */
     protected $queryString;


     /**
      * @var mixed
     */
     protected $fragment;



     /**
      * Uri constructor.
      * @param string $url
     */
     public function __construct(string $url)
     {
          $parser            =  new UrlHelper($url);
          $this->scheme      =  $parser->getParse(PHP_URL_SCHEME);
          $this->user        =  $parser->getParse(PHP_URL_USER);
          $this->password    =  $parser->getParse(PHP_URL_PASS);
          $this->host        =  $parser->getParse(PHP_URL_HOST);
          $this->port        =  $parser->getParse(PHP_URL_PORT);
          $this->path        =  $parser->getParse(PHP_URL_PATH);
          $this->queryString =  $parser->getParse(PHP_URL_QUERY);
          $this->fragment    =  $parser->getParse(PHP_URL_FRAGMENT);
     }



     /**
      * @return mixed
     */
     public function getScheme()
     {
         return $this->scheme;
     }


    /**
     * @return mixed
    */
    public function getHost()
    {
        return $this->host;
    }



    /**
      * @return mixed
    */
    public function getPort()
    {
         return $this->port;
    }


    /**
      * @return mixed
    */
    public function getPath()
    {
         return $this->path;
    }


    /**
     * @return mixed
    */
    public function getQueryString()
    {
        return $this->queryString;
    }


    /**
     * @return mixed
    */
    public function getFragment()
    {
        return $this->fragment;
    }



    /**
     * @return mixed
    */
    public function getUser()
    {
        return $this->user;
    }



    /**
      * @return mixed
    */
    public function getPassword()
    {
        return $this->password;
    }
}