<?php
namespace Jan\Component\Routing;


/**
 * Class Route
 * @package Jan\Component\Routing
 */
class Route implements \ArrayAccess
{


    /**
     * route path
     *
     * @var string
    */
    protected $path = '';



    /**
     * route handle
     *
     * @var mixed
    */
    protected $target;



    /**
     * route name
     *
     * @var string
    */
    protected $name = '';



    /**
     * route name prefix
     *
     * @var string
    */
    protected $namePrefix = '';



    /**
     * route regex params
     *
     * @var array
     */
    protected $params  = [];


    /**
     * methods using by route
     *
     * @var array
     */
    protected $methods = [];



    /**
     * route matches params
     *
     * @var array
     */
    protected $matches = [];


    /**
     * route middleware
     *
     * @var array
    */
    protected $middleware = [];



    /**
     * route options
     *
     * @var array
     */
    protected $options = [];


    /**
     * @var array
    */
    protected static $nameStorage = [];


    /**
     * Route constructor.
     *
     * @param array $methods
     * @param string $path
     * @param mixed|null $target
     * @param string|null $prefixName
    */
    public function __construct(array $methods = [], string $path = '', $target = null, string $prefixName = null)
    {
        $this->setMethods($methods);
        $this->setPath($path);
        $this->setTarget($target);
        $this->setNamePrefix($prefixName);
    }



    /**
     * get route path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }


    /**
     * @return string
     */
    public function getResolvedPath(): string
    {
        return $this->removeTrailingSlashes($this->path);
    }


    /**
     * set route path
     *
     * @param string $path
     * @return Route
     */
    public function setPath(string $path): Route
    {
        $this->path = $path;

        return $this;
    }



    /**
     * get route target
     *
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }


    /**
     * set route target
     *
     * @param mixed $target
     * @return Route
     */
    public function setTarget($target): Route
    {
        $this->target = $target;

        return $this;
    }



    /**
     * @return string
     */
    public function getNamePrefix(): string
    {
        return $this->namePrefix;
    }


    /**
     * @param string $namePrefix
     * @return Route
     */
    public function setNamePrefix(string $namePrefix): Route
    {
        $this->namePrefix = $namePrefix;

        return $this;
    }



    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }



    /**
     * set route name
     *
     * @param string $name
     * @return Route
     */
    public function name(string $name): Route
    {
        $name = $this->namePrefix . $name;

        if(static::exists($name)) {
            throw new \RuntimeException(
                sprintf('This route name (%s) already taken!', $name)
            );
        }

        static::$nameStorage[$name] = $this;

        $this->name = $name;

        return $this;
    }



    /**
     * @return array
     */
    public static function nameStorage(): array
    {
        return static::$nameStorage;
    }



    /**
     * @param $name
     * @return bool
    */
    public static function exists($name): bool
    {
        return \array_key_exists($name, static::$nameStorage);
    }



    /**
     * @param $name
     * @return Route
    */
    public static function retrieve($name): Route
    {
        return static::$nameStorage[$name];
    }



    /**
     * @param string $name
     * @param array $params
     * @return bool|string
     */
    public static function generate(string $name, array $params = [])
    {
        if(! static::exists($name))
        {
            return false;
        }

        return static::retrieve($name)->convertParams($params);
    }



    /**
     * get route params
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }


    /**
     * set params
     * @param $name
     * @param $regex
     * @return $this
    */
    public function where($name, $regex = null): Route
    {
        foreach ($this->parseWhere($name, $regex) as $name => $regex)
        {
            $this->params[$name] = $this->resolveRegex($regex);
        }

        return $this;
    }


    /**
     * @param string $name
     * @return $this
     */
    public function whereNumeric(string $name): Route
    {
        return $this->where($name, '[0-9]+'); // (\d+)
    }


    /**
     * @param string $name
     * @return Route
     */
    public function any(string $name): Route
    {
        return $this->where($name, '.*');
    }


    /**
     * @param string $name
     * @return $this|Route
     */
    public function whereWord(string $name): Route
    {
        return $this->where($name, '\w+');
    }


    /**
     * @param string $name
     * @return $this|Route
     */
    public function whereDigital(string $name): Route
    {
        return $this->where($name, '\d+');
    }


    /**
     * @param string $name
     * @return Route
     */
    public function whereAlphaNumeric(string $name): Route
    {
        // [^[:alnum:]\-_]
        return $this->where($name, '[^a-z_\-0-9]'); // (\w+)
    }



    /**
     * @param string $name
     * @return Route
     */
    public function whereSlug(string $name): Route
    {
        return $this->where($name, '[a-z\-0-9]+'); // (\w+)
    }

    /**
     * get route methods
     *
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }


    /**
     * @param string $separator
     * @return string
     */
    public function toStringMethod($separator = '|'): string
    {
        return implode($separator, $this->getMethods());
    }


    /**
     * set route methods
     *
     * @param array $methods
     * @return Route
     */
    public function setMethods(array $methods): Route
    {
        $this->methods = $methods;

        return $this;
    }



    /**
     * get matches params
     *
     * @return array
     */
    public function getMatches(): array
    {
        return $this->matches;
    }


    /**
     * @param $name
     * @return mixed
     */
    public function getParam($name): string
    {
        return $this->params[$name] ?? '';
    }


    /**
     * get route middlewares
     *
     * @return array
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }


    /**
     * set middlewares
     *
     * @param array|string $middleware
     * @return Route
     */
    public function middleware($middleware): Route
    {
        $this->middleware = array_merge($this->middleware, (array) $middleware);

        return $this;
    }


    /**
     * get options
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }


    /**
     * set route options
     *
     * @param array $options
     * @return Route
     */
    public function addOptions(array $options): Route
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }


    /**
     * @param $key
     * @param $value
     * @return Route
     */
    public function setOption($key, $value): Route
    {
        $this->options[$key] = $value;

        return $this;
    }




    /**
     * Determine if the given request method is allowed
     *
     * @param string $method
     * @return bool
     */
    public function matchMethod(string $method): bool
    {
        if(\in_array($method, $this->methods)) {
            $this->addOptions(compact('method'));
            return true;
        }

        return false;
    }


    /**
     * Determine if the given request uri is matched
     *
     * @param string $url
     * @return bool
     */
    public function matchPath(string $url): bool
    {
        if(preg_match($pattern = $this->getPattern(), $this->resolveURL($url), $matches)) {

            $this->matches = $this->filterMatchedParams($matches);

            $this->addOptions([
                'pattern'  => $pattern,
                'url'      => $url
            ]);

            return true;
        }

        return false;
    }


    /**
     * Determine if current request method and uri matches route
     *
     * @param $requestMethod
     * @param $requestUri
     * @return bool
     */
    public function match($requestMethod, $requestUri): bool
    {
        return $this->matchMethod($requestMethod) && $this->matchPath($requestUri);
    }


    /**
     * @return mixed
    */
    public function call()
    {
        if(! is_callable($this->target)) {
            return $this->target;
        }

        return call_user_func_array($this->target, array_values($this->matches));
    }



    /**
     * Generate pattern
     *
     * @param string $flag
     * @return string
     */
    protected function getPattern(string $flag = 'i'): string
    {
        $pattern = $this->getResolvedPath();

        if($patterns = $this->getPatterns()) {
            foreach($patterns as $k => $v) {
                $pattern = preg_replace(["#{{$k}}#", "#{{$k}.?}#"], [$v, '?'. $v .'?'], $pattern);
            }
        }

        return '#^'. $pattern .'$#'. $flag;
    }



    /**
     * @return array
     */
    public function getPatterns(): array
    {
        $patterns = [];

        if($this->params) {
            foreach ($this->params as $name => $regex) {
                $patterns[$name] = '(?P<'. $name .'>'. $regex . ')';
            }
        }

        return $patterns;
    }



    /**
     * Convert path params
     *
     * @param array $params
     * @return string
     */
    public function convertParams(array $params = [])
    {
        $path = $this->getPath();

        if($params) {
            foreach($params as $k => $v) {
                $path = preg_replace(["#{{$k}}#", "#{{$k}.?}#"], $v, $path);
            }
        }

        return  sprintf('/%s', trim($path, '/'));
    }


    /**
     * @param string $path
     * @return string
     */
    protected function resolveURL(string $path): string
    {
        // TODO refactoring
        if(stripos($path, '?') !== false) {
            $path = explode('?', $path, 2)[0];
        }

        $this->addOptions(compact('path'));
        return $this->removeTrailingSlashes($path);
    }


    /**
     * @param string $path
     * @return string
     */
    protected function removeTrailingSlashes(string $path): string
    {
        return trim($path, '\\/');
    }


    /**
     * @param string $path
     * @param string $prefix
     * @return string
     */
    protected function removeRightTrailingShashes(string $path, string $prefix = '')
    {
        return $prefix . rtrim($path, '\\/');
    }


    /**
     * @param array $matches
     * @return array
     */
    protected function filterMatchedParams(array $matches): array
    {
        return array_filter($matches, function ($key) {

            return ! is_numeric($key);

        }, ARRAY_FILTER_USE_KEY);
    }



    /**
     * Determine parses
     *
     * @param $name
     * @param $regex
     * @return array
     */
    protected function parseWhere($name, $regex): array
    {
        return \is_array($name) ? $name : [$name => $regex];
    }


    /**
     * @param $regex
     * @return string|string[]
     */
    protected function resolveRegex($regex)
    {
        return str_replace('(', '(?:', $regex);
    }


    /**
     * @param mixed $offset
     * @return bool|void
     */
    public function offsetExists($offset)
    {
        return property_exists($this, $offset);
    }


    /**
     * @param mixed $offset
     * @return mixed|void
     */
    public function offsetGet($offset)
    {
        if(property_exists($this, $offset)) {
            return $this->{$offset};
        }

        return null;
    }


    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if(property_exists($this, $offset)) {
            $this->{$offset} = $value;
        }
    }


    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if(property_exists($this, $offset)) {
            unset($this->{$offset});
        }
    }
}