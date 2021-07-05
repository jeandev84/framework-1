<?php
namespace Jan\Component\Http;



use Jan\Component\Http\Bag\FileBag;
use Jan\Component\Http\Bag\HeaderBag;
use Jan\Component\Http\Bag\InputBag;
use Jan\Component\Http\Bag\ParameterBag;
use Jan\Component\Http\Bag\ServerBag;
use Jan\Component\Http\Cookie\Cookie;
use Jan\Component\Http\Session\Session;


/**
 * Class Request
 * @package Jan\Component\Http
*/
class Request
{



    /**
     * Get params from request get $_GET
     *
     * @var ParameterBag
    */
    public $queryParams;




    /**
     * Get params from request post $_POST
     *
     * @var ParameterBag
    */
    public $request;





    /**
     * Get attributes
     *
     * @var array
    */
    public $attributes = [];




    /**
     * Get parameters from cookies $_COOKIES
     *
     *
     * @var Cookie
    */
    public $cookies;





    /**
     * Get parameters from request $_FILES
     *
     * @var FileBag
    */
    public $files;




    /**
     * server bag
     *
     * @var ServerBag
    */
    public $server;




    /**
     * headers
     *
     * @var HeaderBag
     */
    public $headers;




    /**
     * Parsed body
     *
     * @var string
     */
    public $content;



    /**
     * Get availables languages
     *
     * @var
     */
    public $languages;



    /**
     * Session
     */
    public $session;



    /**
     *  charset
     */
    public $charsets;



    /**
     * encodings
     *
     * @var string
    */
    public $encodings;




    /**
     * @var string
    */
    public $acceptableContentTypes;





    /**
     * Default locale
     * @var string
    */
    public $locale;




    /**
     * Default local language
     * @var string
    */
    public $defaultLocale = 'en';




    /**
     * request uri
     *
     * @var
     */
    protected $requestUri;



    /**
     * path info
     *
     * @var
     */
    protected $pathInfo;



    /**
     * Get base URL
     *
     * @var string
    */
    protected $baseUrl;




    /**
     * Get base path
     *
     * @var string
    */
    protected $basePath;




    /**
     * Request method
    */
    protected $method;



    /**
     * Format
    */
    protected $format = 'html';




    /**
     * Determine if host is valid
     * @var bool
    */
    private $isHostValid = true;




    /**
     * Request constructor.
     * @param array $queryParams
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param string|null $content
    */
    public function __construct(
        array $queryParams = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        string $content = null
    )
    {
        $this->queryParams = new ParameterBag($queryParams);
        $this->request     = new ParameterBag($request);
        $this->attributes  = new ParameterBag($attributes);
        $this->cookies     = new Cookie($cookies);
        $this->session     = new Session();
        $this->files       = new FileBag($files);
        $this->server      = new ServerBag($server);
        $this->headers     = new HeaderBag($this->server->getHeaders());
        $this->content     = $content;
        $this->languages   = null;
        $this->charsets    = null;
        $this->encodings   = null;
        $this->acceptableContentTypes = null;
        $this->pathInfo    = null;
        $this->requestUri  = null;
        $this->baseUrl     = null;
        $this->basePath    = null;
        $this->method      = null;
        $this->format      = null;
    }



    public static function create()
    {

    }



    /**
     * Create request from factory
     *
     * @param array $queryParams
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param string|null $content
     * @return Request
    */
    public static function createFromFactory(
        array $queryParams = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        string $content = null
    ): Request
    {
        return new static($queryParams, $request, $attributes, $cookies, $files, $server, $content);
    }



    /**
     * @return static
    */
    public static function createFromGlobals(): Request
    {
         $request = static::createFromFactory($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER, 'php://input');

         if($request->hasContentTypeFormUrlEncoded() &&
            $request->requestMethodIn(['PUT', 'DELETE', 'PATCH'])
         ) {
            parse_str($request->getContent(), $data);
            $request->request = new InputBag($data);
         }

         return $request;
    }




    /**
     * Get item
     *
     * @param string $key
     * @return mixed|null
    */
    public function get(string $key)
    {
         if ($this->queryParams->has($key)) {
             return $this->queryParams->get($key);
         }

         if ($this->request->has($key)) {
             return $this->request->get($key);
         }

         return null;
    }




    /**
     * @return string|null
    */
    public function getContent(): ?string
    {
        if(! $this->content) {
            return file_get_contents('php://input');
        }

        return $this->content;
    }



    /**
     * @param string|null $content
     * @return Request
    */
    public function setContent(?string $content): Request
    {
        $this->content = $content;

        return $this;
    }



    /**
     * @param array $attributes
     * @return Request
    */
    public function setAttributes(array $attributes = []): Request
    {
        $this->attributes = $attributes;

        return $this;
    }




    /**
     * @param string $key
     * @param $value
    */
    public function setAttribute(string $key, $value)
    {
        $this->attributes[$key] = $value;
    }



    /**
     * @return array
    */
    public function getAttributes(): array
    {
        return $this->attributes;
    }




    /**
     * @return bool
    */
    public function isXhr(): bool
    {
        return $this->server->get('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest';
    }




    /**
     * @return bool
    */
    protected function hasContentTypeFormUrlEncoded(): bool
    {
        return stripos($this->getContentType(), 'application/x-www-form-urlencoded') === 0;
    }



    /**
     * @return mixed|null
    */
    protected function getContentType()
    {
        return $this->headers->get('CONTENT_TYPE', '');
    }




    /**
     * @param array $methods
     * @return bool
    */
    protected function requestMethodIn(array $methods): bool
    {
        return \in_array($this->toUpperMethod(), $methods);
    }




    /**
     * @return string
    */
    protected function toUpperMethod(): string
    {
        return strtoupper($this->server->get('REQUEST_METHOD', 'GET'));
    }




    /**
     * @return mixed|null
    */
    public function getMethod()
    {
        return $this->server->getMethod();
    }




    /**
     * @return array
    */
    public function getBody()
    {
        return [];
    }
}