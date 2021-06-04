<?php
namespace Jan\Component\Http\Bag;


/**
 * Class ServerBag
 * @package Jan\Component\Http\Bag
*/
class ServerBag extends ParameterBag
{
    /**
     * @return array
    */
    public function getHeaders()
    {
        $headers = [];

        foreach ($this->params as $key => $value)
        {
            if(strpos($key, 'HTTP_') === 0)
            {
                $headers[substr($key, 5)] = $value;

            } elseif (\in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH', 'CONTENT_MD5'], true)) {
                $headers[$key] = $value;
            }
        }


        return $headers;
    }



    /**
     * @return mixed
    */
    public function getDocumentRoot()
    {
       return $this->get('DOCUMENT_ROOT');
    }


    /**
     * @return mixed|null
   */
    public function getProtocol()
    {
        return $this->get('SERVER_PROTOCOL');
    }




    /**
     * @return mixed|null
    */
    public function getPathInfo()
    {
        return $this->get('PATH_INFO');
    }


    /**
     * @return mixed|null
    */
    public function getHost()
    {
        return $this->get('HTTP_HOST');
    }



    /**
     * @return mixed|null
    */
    public function getMethod()
    {
        return $this->get('REQUEST_METHOD');
    }


    /**
     * @return mixed|null
    */
    public function getRequestUri()
    {
        return $this->get('REQUEST_URI');
    }



    /**
     * @return bool
    */
    public function isSecure(): bool
    {
        $https = $this->get('HTTPS');
        $port  = $this->get('SERVER_PORT');

        return $https == 'on' && $port == 443;
    }


    /**
     * @return string
    */
    public function getScheme(): string
    {
        return ($this->isSecure() ? 'https' : 'http') . '://';
    }


    /**
     * @return mixed|null
    */
    public function getAuthUser()
    {
         return $this->get('PHP_AUTH_USER');
    }


    /**
     * @return mixed|null
    */
    public function getAuthPassword()
    {
        return $this->get('PHP_AUTH_PW');
    }
}