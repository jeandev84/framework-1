<?php
namespace Jan\Component\Routing;


use Closure;


/**
 * Class Router
 * @package Jan\Component\Routing
 */
class Router extends RouteCollection
{


    /**
     * route patterns
     *
     * @var array
     */
    protected $patterns = [];




    /**
     * @var RouteParameter
     */
    protected $routeParameters;



    /**
     * @var string
    */
    protected $baseUrl;


    /**
     * Router constructor.
     * @param string $url
     */
    public function __construct(string $url = '')
    {
        if($url) {
            $this->setUrl($url);
        }

        $this->routeParameters = new RouteParameter();
    }



    /**
     * @param string $baseUrl
     * @return $this
    */
    public function setUrl(string $baseUrl): Router
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }


    /**
     * @return string
    */
    public function getUrl(): string
    {
        return $this->baseUrl;
    }



    /**
     * @param string $path
     * @param $target
     * @param string|null $name
     * @return Route
     */
    public function get(string $path, $target, string $name = null): Route
    {
        return $this->map(['GET'], $path, $target, $name);
    }


    /**
     * @param string $path
     * @param $target
     * @param string|null $name
     * @return Route
     */
    public function post(string $path, $target, string $name = null): Route
    {
        return $this->map(['POST'], $path, $target, $name);
    }



    /**
     * @param string $path
     * @param $target
     * @param string|null $name
     * @return Route
     */
    public function put(string $path, $target, string $name = null): Route
    {
        return $this->map(['PUT'], $path, $target, $name);
    }




    /**
     * @param string $path
     * @param $target
     * @param string|null $name
     * @return Route
     */
    public function delete(string $path, $target, string $name = null): Route
    {
        return $this->map(['DELETE'], $path, $target, $name);
    }




    /**
     * @param string $path
     * @param $target
     * @param string|null $name
     * @return Route
     */
    public function any(string $path, $target, string $name = null): Route
    {
        return $this->map('GET|POST|PUT|DELETE|PATCH', $path, $target, $name);
    }



    /**
     * @param Closure $routeCallback
     * @param array $options
     */
    public function group(Closure $routeCallback, array $options = [])
    {
        if($options) {
            $this->routeParameters->addOptions($options);
        }

        $routeCallback($this);
        $this->routeParameters->flushOptions();
    }



    /**
     * @param Closure|null $closure
     * @param array $options
     * @return Router
     */
    public function api(Closure $closure = null, array $options = []): Router
    {
        $options = $this->routeParameters->configureApiDefaultOptions($options);

        if(! $closure) {
            $this->routeParameters->addOptions($options);
            return $this;
        }

        $this->group($closure, $options);
    }


    /**
     * @param array|string $methods
     * @param string $path
     * @param $target
     * @param string|null $name
     * @return Route
     */
    public function map($methods, string $path, $target, string $name = null): Route
    {
        $methods    = $this->routeParameters->resolveMethods($methods);
        $path       = $this->routeParameters->resolvePath($path);
        $target     = $this->routeParameters->resolveTarget($target);

        $middleware = $this->routeParameters->getMiddlewares();
        $prefixName = $this->routeParameters->getPrefixName();

        $route = new Route($methods, $path, $target, $prefixName);

        $route->where($this->patterns);
        $route->middleware($middleware);
        $route->addOptions($this->routeParameters->getDefaultParams());

        if($name) {
            $route->name($name);
        }

        return $this->add($route);
    }



    /**
     * @param $name
     * @param $regex
     * @return Router
     *
     * Example:
     * $router = new Router();
     * $router->pattern('id', '[0-9]+');
     * $router->pattern(['id' => '[0-9]+']);
    */
    public function pattern($name, $regex = null): Router
    {
        $patterns = is_array($name) ? $name : [$name => $regex];

        $this->patterns = array_merge($this->patterns, $patterns);

        return $this;
    }


    /**
     * @param string $name
     * @return $this
     */
    public function name(string $name): Router
    {
        $this->routeParameters->addOptionName($name);

        return $this;
    }


    /**
     * @param array $middleware
     * @return $this
     */
    public function middleware(array $middleware): Router
    {
        $this->routeParameters->addOptionMiddleware($middleware);

        return $this;
    }


    /**
     * @param $prefix
     * @return Router
     */
    public function prefix($prefix): Router
    {
        $this->routeParameters->addOptionPrefix($prefix);

        return $this;
    }


    /**
     * @param $namespace
     * @return Router
     */
    public function namespace($namespace): Router
    {
        $this->routeParameters->addOptionNamespace($namespace);

        return $this;
    }



    /**
     * @param string $requestMethod
     * @param string $requestUri
     * @return Route|bool
     */
    public function match(string $requestMethod, string $requestUri)
    {
        foreach ($this->getRoutes() as $route) {
            if($route->match($requestMethod, $requestUri)) {
                return $route;
            }
        }

        return false;
    }


    /**
     * @param string $name
     * @param array $params
     * @return string|false
     * @throws \Exception
     */
    public function generate(string $name, array $params = [])
    {
        try {
            $route = $this->getRoute($name);
        }catch (\Exception $e) {
            return false;
        }

        return $route->convertParams($params);
    }


    /**
     * @param string $name
     * @param array $params
     * @return string
     * @throws \Exception
     */
    public function url(string $name, array $params = []): string
    {
        return rtrim($this->baseUrl, '/') . $this->generate($name, $params);
    }
}