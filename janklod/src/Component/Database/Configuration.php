<?php
namespace Jan\Component\Database;



/**
 * Class Configuration
 *
 * @package Jan\Component\Database
*/
class Configuration
{
     const KEY_DRIVER    = 'driver';
     const KEY_HOST      = 'host';
     const KEY_DATABASE  = 'database';
     const KEY_PORT      = 'port';
     const KEY_CHARSET   = 'charset';
     const KEY_USERNAME  = 'username';
     const KEY_PASSWORD  = 'password';
     const KEY_COLLATION = 'collation';
     const KEY_OPTIONS   = 'options';
     const KEY_PREFIX    = 'prefix';
     const KEY_ENGINE    = 'engine';



     /**
      * @var array
     */
     protected $params = [
         self::KEY_DRIVER     => 'mysql',
         self::KEY_DATABASE   => 'default',
         self::KEY_HOST       => '127.0.0.1',
         self::KEY_PORT       => '3306',
         self::KEY_CHARSET    => 'utf8',
         self::KEY_USERNAME   => 'root',
         self::KEY_PASSWORD   => 'secret',
         self::KEY_COLLATION  => 'utf8_unicode_ci',
         self::KEY_OPTIONS    => [],
         self::KEY_PREFIX     => '',
         self::KEY_ENGINE     => 'InnoDB', // InnoDB or MyISAM
     ];



     /**
      * Configuration constructor.
      * @param array $params
     */
     public function __construct(array $params = [])
     {
          $this->add($params);
     }



     /**
      * Set configuration params
      *
      * @param array $params
     */
     public function add(array $params)
     {
          foreach ($params as $key => $value) {
              if (\array_key_exists($key, $this->params)) {
                  $this->params[$key] = $value;
              }
          }
     }



     /**
      * Get configuration param
      *
      * @param string $key
      * @param null $default
      * @return mixed|null
     */
     public function get(string $key, $default = null)
     {
          return $this->params[$key] ?? $default;
     }



     /**
      * Determine if given key exist in configuration params
      *
      * @param string $key
      * @return bool
     */
     public function has(string $key): bool
     {
         return \array_key_exists($key, $this->params);
     }


    /**
     * @return mixed|null
     */
    public function getDriverName()
    {
        return $this->get(self::KEY_DRIVER);
    }



    /**
     * @return mixed|null
     */
    public function getHost()
    {
        return $this->get(self::KEY_HOST);
    }



    /**
     * @return mixed|null
     */
    public function getDatabase()
    {
        return $this->get(self::KEY_DATABASE);
    }


    /**
     * @return mixed|null
     */
    public function getPort()
    {
        return $this->get(self::KEY_PORT);
    }


    /**
     * @return mixed|null
     */
    public function getCharset()
    {
        return $this->get(self::KEY_CHARSET);
    }


    /**
     * @return mixed|null
     */
    public function getUsername()
    {
        return $this->get(self::KEY_USERNAME);
    }


    /**
     * @return mixed|null
     */
    public function getPassword()
    {
        return $this->get(self::KEY_PASSWORD);
    }


    /**
     * @return mixed|null
     */
    public function getOptions()
    {
        return $this->get(self::KEY_OPTIONS);
    }


    /**
     * @return mixed|null
     */
    public function getCollation()
    {
        return $this->get(self::KEY_COLLATION);
    }


    /**
     * @return mixed|null
     */
    public function getPrefix()
    {
        return $this->get(self::KEY_PREFIX);
    }


    /**
     * @param string $table
     * @return string
    */
    public function getTableName(string $table): string
    {
        return  $this->getPrefix(). $table;
    }


    /**
     * @return mixed|null
    */
    public function getEngine()
    {
        return $this->get(self::KEY_ENGINE);
    }
}