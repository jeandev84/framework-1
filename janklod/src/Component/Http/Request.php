<?php
namespace Jan\Component\Http;



use Jan\Component\Http\Bag\FileBag;
use Jan\Component\Http\Bag\HeaderBag;
use Jan\Component\Http\Bag\InputBag;
use Jan\Component\Http\Bag\ParameterBag;
use Jan\Component\Http\Bag\ServerBag;
use Jan\Component\Http\Cookie\Cookie;
use Jan\Component\Http\Parser\UrlParser;
use Jan\Component\Http\Session\Session;


/**
 * Class Request
 * @package Jan\Component\Http
*/
class Request
{



    /**
     * Get query params from request get $_GET
     *
     * @var ParameterBag
    */
    public $query;




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
     * @var Session
    */
    public $session;




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
     * request uri
     *
     * @var Uri
    */
    public $uri;




    /**
     * @var string
    */
    public $requestUri;




    /**
     * @var
    */
    public $method;



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
        $this->query       = new ParameterBag($queryParams);
        $this->request     = new ParameterBag($request);
        $this->attributes  = new ParameterBag($attributes);
        $this->cookies     = new Cookie($cookies);
        $this->session     = new Session();
        $this->files       = new FileBag($files);
        $this->server      = new ServerBag($server);
        $this->headers     = new HeaderBag($this->server->getHeaders());
        $this->content     = $content;
        $this->requestUri  = $this->server->getRequestUri();
        $this->uri         = new Uri(new UrlParser($this->requestUri));
        $this->method      = $this->getMethod();
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
         if ($this->query->has($key)) {
             return $this->query->get($key);
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
     * Determine if the protocol is secure
     *
     * @return bool
    */
    public function isSecure(): bool
    {
        $https = $this->server->get('HTTPS');
        $port  = $this->server->get('SERVER_PORT');


        return $https == 'on' && $port == 443;
    }




    /**
     * @return string
    */
    public function getScheme(): string
    {
         return $this->isSecure() ? 'https' : 'http';
    }



    /**
     * @return bool
    */
    public function isXhr(): bool
    {
        return $this->server->get('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest';
    }


    /**
     * Set method
     *
     * @param string $method
     * @return Request
    */
    public function setMethod(string $method): Request
    {
        $this->method = $method;

        $this->server->set('REQUEST_METHOD', $method);

        return $this;
    }



    /**
     * @return mixed|null
    */
    public function getMethod()
    {
        if(! \is_null($this->method)) {
            return $this->method;
        }

        return $this->method = $this->server->get('REQUEST_METHOD', 'GET');
    }



    /**
     * Get request body
     *
     * @return array
    */
    public function getBody(): array
    {
        switch ($this->getMethod()) {
            case 'GET':
                return $this->query->sanitize(INPUT_GET);
                break;
            case 'POST':
                return $this->request->sanitize(INPUT_POST);
                break;
            default:
                return [];
        }
    }



    /**
     * @return Uri
    */
    public function getUri(): Uri
    {
        return $this->uri;
    }



    /**
     * @return string
    */
    public function url(): string
    {
       return implode([$this->baseUrl(), $this->requestUri, $this->uri->getFragment()]);
    }



    /**
     * @return string
    */
    public function baseUrl(): string
    {
        $scheme   = $this->getScheme() . '://';
        $user     = $this->server->getAuthUser();
        $pass     = $this->server->getAuthPassword();
        $auth     = ($user && $pass) ? $user .':'. $pass : '';
        $host     = $this->server->getHost();

        return implode([$scheme, $auth, $host]);
    }




    /**
     * @param string $host
     * @return bool
    */
    public function isValidHost(string $host): bool
    {
        return $this->server->getHost() === $host;
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
        return strtoupper($this->getMethod());
    }
}