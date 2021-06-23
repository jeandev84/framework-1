<?php
namespace Jan\Component\Routing;


/**
 * Class RouteParameter
 * @package Jan\Component\Routing
*/
class RouteParameter
{
    const PATH_PREFIX               = 'path.prefix';
    const NAMESPACE_PREFIX          = 'namespace.prefix';
    const NAME_PREFIX               = 'name.prefix';

    const OPTION_PARAM_PATH_PREFIX  = 'prefix';
    const OPTION_PARAM_NAMESPACE    = 'namespace';
    const OPTION_PARAM_MIDDLEWARE   = 'middleware';
    const OPTION_PARAM_NAME_PREFIX  = 'name';


    /**
     * @var array
     */
    protected $options = [];


    /**
     * @param array $options
     */
    public function addOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }


    /**
     * @param $key
     * @param $value
     */
    public function addOption($key, $value)
    {
        $this->options[$key] = $value;
    }


    /**
     * @param string $name
     */
    public function addOptionName(string $name)
    {
        $this->addOption(static::OPTION_PARAM_NAME_PREFIX, $name);
    }


    /***
     * @param array $middleware
     */
    public function addOptionMiddleware(array $middleware)
    {
        $this->addOption(static::OPTION_PARAM_MIDDLEWARE, $middleware);
    }


    /**
     * @param string $prefix
     */
    public function addOptionPrefix(string $prefix)
    {
        $this->addOption(static::OPTION_PARAM_PATH_PREFIX, $prefix);
    }


    /**
     * @param string $namespace
     */
    public function addOptionNamespace(string $namespace)
    {
        $this->addOption(static::OPTION_PARAM_NAMESPACE, $namespace);
    }



    /**
     * remove all options options
     *
     * @return void
     */
    public function flushOptions()
    {
        $this->options = [];
    }


    /**
     * flush parameters and options
     */
    public function flush()
    {
        $this->flushOptions();
    }


    /**
     * @param $key
     */
    protected function removeOption($key)
    {
        unset($this->options[$key]);
    }



    /**
     * Get option by given param
     *
     * @param $key
     * @param null $default
     * @return mixed|void|null
     */
    public function getOption($key, $default = null)
    {
        foreach (array_keys($this->options) as $index) {
            if(! $this->hasOption($index)) {
                return new \Exception(sprintf('%s is not available this param', $index));
            }
        }

        return $this->options[$key] ?? $default;
    }


    /**
     * @param $methods
     * @return array
     */
    public function resolveMethods($methods): array
    {
        if(\is_string($methods)) {
            $methods = explode('|', $methods);
        }
        return (array) $methods;
    }


    /**
     * @param $path
     * @return mixed|string
     */
    public function resolvePath($path)
    {
        if($prefix = $this->getOption(static::OPTION_PARAM_PATH_PREFIX)) {
            $path = rtrim($prefix, '/') . '/'. ltrim($path, '/');
        }
        return $path;
    }



    /**
     * Resolve handle
     *
     * @param $target
     * @return mixed
     */
    public function resolveTarget($target)
    {
        if(\is_string($target) && $namespace = $this->getOption(static::OPTION_PARAM_NAMESPACE)) {
            $target = rtrim(ucfirst($namespace), '\\') .'\\' . $target;
        }
        return $target;
    }



//    /**
//     * @param $name
//     * @return string
//    */
//    public function resolveName($name): string
//    {
//        if($prefixed = $this->getOption(static::OPTION_PARAM_NAME_PREFIX)) {
//            return $prefixed . $name;
//        }
//        return $name;
//    }


    /**
     * @return \Exception|mixed|void|null
     */
    public function getMiddlewares()
    {
        return $this->getOption(self::OPTION_PARAM_MIDDLEWARE, []);
    }


    /**
     * @return \Exception|mixed|void|null
     */
    public function getPrefixName()
    {
        return $this->getOption(static::OPTION_PARAM_NAME_PREFIX, '');
    }


    /**
     * @param array $options
     * @return array
     */
    public function configureApiDefaultOptions(array $options)
    {
        if(! isset($options[self::OPTION_PARAM_PATH_PREFIX])) {
            $options[self::OPTION_PARAM_PATH_PREFIX] = 'api';
        }

        return $options;
    }


    /**
     * @param $key
     * @return bool
     */
    protected function hasOption($key): bool
    {
        return \in_array($key, $this->getOptionParams());
    }



    /**
     * @return string[]
     */
    protected function getOptionParams(): array
    {
        return [
            self::OPTION_PARAM_PATH_PREFIX,
            self::OPTION_PARAM_NAMESPACE,
            self::OPTION_PARAM_MIDDLEWARE,
            self::OPTION_PARAM_NAME_PREFIX
        ];
    }



    /**
     * @return string[]
    */
    public function getDefaultParams(): array
    {
        return [
            self::PATH_PREFIX      => (string) $this->getOption(self::OPTION_PARAM_PATH_PREFIX),
            self::NAME_PREFIX      => (string) $this->getOption(self::OPTION_PARAM_NAME_PREFIX),
            self::NAMESPACE_PREFIX => (string) $this->getOption(self::OPTION_PARAM_NAMESPACE)
        ];
    }
}