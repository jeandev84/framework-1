<?php
namespace Jan\Component\Http;


use Jan\Component\Http\Bag\CookieBag;
use Jan\Component\Http\Bag\FileBag;
use Jan\Component\Http\Bag\HeaderBag;
use Jan\Component\Http\Bag\InputBag;
use Jan\Component\Http\Bag\ParameterBag;
use Jan\Component\Http\Bag\ServerBag;
use Jan\Component\Http\Parser\UrlHelper;
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
     * @var InputBag
     */
    public $queryParams;



    /**
     * Get params from request post $_POST
     *
     * @var InputBag
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
     * @var CookieBag
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
     * @var
     */
    public $encodings;




    /**
     * @var
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
     * @var Url
    */
    public $url;




    /**
     * Get base URL
     *
     * @var string
    */
    protected $baseUrl;




    /**
     * Request method
    */
    protected $method;



    /**
     * Format
     */
    protected $format = 'html';




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
          $this->initialize($queryParams, $request, $attributes, $cookies, $files, $server, $content);
    }



    /**
     * @param array $queryParams
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param string|null $content
    */
    public function initialize(
        array $queryParams = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        string $content = null
    )
    {
        $this->queryParams = new InputBag($queryParams);
        $this->request     = new InputBag($request);
        $this->attributes  = new ParameterBag($attributes);
        $this->cookies     = new CookieBag($cookies);
        $this->files       = new FileBag($files);
        $this->server      = new ServerBag($server);
        $this->headers     = new HeaderBag($this->server->getHeaders());
        $this->content     = $content;

        $this->session     = new Session();
        $this->method      = $this->getMethod();
        $this->languages   = null;
        $this->charsets    = null;
        $this->encodings   = null;
        $this->acceptableContentTypes = null;
        $this->format      = null;
        $this->baseUrl     = $this->getBaseUrl();
        $this->setUrl($this->getOriginalUrl());
    }




    /**
     * @param array $queryParams
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param string|null $content
     * @return Request
     */
    public static function factory(
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
     * Request factory
     *
     * @return Request
    */
    public static function createFromGlobals(): Request
    {
        $request =  static::factory($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);

        if($request->hasContentTypeFormUrlEncoded() &&
           $request->requestMethodIn(['PUT', 'DELETE', 'PATCH'])
        ) {
            parse_str($request->getContent(), $data);
            $request->request = new InputBag($data);
        }

        return $request;
    }


    /**
     * @param string $uri
     * @param string $method
     * @param array $parameters
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param string|null $content
     * @return Request
    */
    public static function create(
        string $uri,
        string $method = 'GET',
        array $parameters = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        string $content = null
    ): Request
    {

    }



    /**
     * @param string $url
     * @return $this
    */
    public function setUrl(string $url)
    {
        $this->url = new Url($url);

        return $this;
    }



    /**
     * @return string
    */
    public function getOriginalUrl()
    {
        return implode([
            $this->getBaseUrl(),
            $this->server->getRequestUri()
        ]);
    }


    /**
     * @return array
    */
    public function getRequestData()
    {
         return array_merge($this->request->all(), $this->files->all());
    }

    /**
     * @return mixed|null
    */
    public function getMethod()
    {
         return $this->server->get('REQUEST_METHOD');
    }


    /**
     * @param $method
     * @return Request
    */
    public function setMethod($method)
    {
        $this->method = $method;

        $this->server->set('REQUEST_METHOD', $method);

        return $this;
    }


    /**
     * @return bool
    */
    public function isPut()
    {
       return $this->getMethod() === 'PUT';
    }


    /**
     * @return string
    */
    public function getBaseUrl()
    {
        $str = $this->server->getScheme();
        $user = $this->server->getAuthUser();
        $pass = $this->server->getAuthPassword();
        $str .= ($user && $pass) ? $user .':'. $pass .'@' : '';
        $str .= $this->server->getHost();

        if($fragment = $this->request->get('fragment')) {
            $str .= $fragment;
        }

        if($fragment = $this->queryParams->get('fragment')) {
            $str .= $fragment;
        }

//        // request URI
//        if(strpos($str, "#") !== false) {
//            $str .= explode("#", $str)[1];
//        }

        return $str;
    }


    /**
     * put to the end of application
     *
     * Get javascript for get fragment (hash) ?email=jeanyao@ymail.com&region=Moscow#step1
    */
    public function scriptFragment()
    {
        echo "<script type='application/javascript'>
            var forms = document.getElementsByTagName('form'); //get all forms on the site
            for(var i=0; i<forms.length;i++) forms[i].addEventListener('submit', //to each form...
            function(){ //add a submit pre-processing function that will:
            var hidden = document.createElement('input');  //create an extra input element
            hidden.setAttribute('type','hidden'); //set it to hidden so it doesn't break view 
            hidden.setAttribute('name','fragment');  //set a name to get by it in PHP
            hidden.setAttribute('value',window.location.hash); //set a value of #HASH
            this.appendChild(hidden); //append it to the current form
         });
       </script>";
    }

    /**
     * @return Url
    */
    public function getUrl(): Url
    {
        return $this->url;
    }



    /**
     * @return string
    */
    public function getRequestUri()
    {
         return $this->server->get('REQUEST_URI');
    }


    /**
     * @return string
    */
    public function getPathInfo()
    {
        return $this->server->get('PATH_INFO');
    }



    /**
     * @return mixed|null
    */
    public function getDocumentRoot()
    {
        return $this->server->get('DOCUMENT_ROOT');
    }



    /**
     * @return mixed|null
    */
    public function getPort()
    {
        return $this->server->get('SERVER_PORT');
    }



    /**
     * @return mixed|null
    */
    public function getIpClient()
    {
        // must to had some verification
        return $this->server->get('REMOTE_ADDR');
    }



    /**
     * @return mixed|null
    */
    public function getIpPort()
    {
        return $this->server->get('REMOTE_PORT');
    }



    /**
     * @return mixed|null
    */
    public function getScriptName()
    {
        return $this->server->get('SCRIPT_NAME');
    }



    /**
     * @return mixed|null
    */
    public function getHeaders()
    {
        return $this->headers->all();
    }



    /**
     * @param $key
     * @return mixed|null
    */
    public function getHeader($key)
    {
        return $this->headers->get($key);
    }



    /**
     * @return mixed|null
    */
    public function getUserAgent()
    {
        return $this->server->get('HTTP_USER_AGENT');
    }



    /**
     * @return mixed|null
    */
    public function getQueryString()
    {
         return $this->server->get('QUERY_STRING');
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
     * Get attribute
     * @param string $key
     * @return mixed
    */
    public function getAttribute(string $key)
    {
        return $this->attributes[$key] ?? null;
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
     * @return false|string
    */
    public function getJsonContent()
    {
        $content = urldecode(file_get_contents("php://input"));
        $items = explode("&", file_get_contents("php://input"));
        $arr = [];
        foreach ($items as $item) {
            if(\stripos($item, "=") !== false) {
                list($key, $value) = explode("=", $item);
                $arr[$key] = urldecode($value);
            }
        }

        return json_encode($arr);
    }


    /**
     * @return mixed
    */
    public function toArray()
    {
        // JSON TO ARRAY
        $content = '{
             "email" : "jeanyao@ymail.com",
             "password" : "qwerty",
             "username" : "jeanyao"
        }';

        $data = json_decode($content, true);
        echo $data['email'];
        return json_decode($content, true);
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
    protected function requestMethodIn(array $methods)
    {
        return \in_array($this->toUpperMethod(), $methods);
    }


    /**
     * @return string
    */
    protected function toUpperMethod()
    {
         return strtoupper($this->server->get('REQUEST_METHOD', 'GET'));
    }

}