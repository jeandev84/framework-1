<?php
namespace Jan\Component\Templating\Exception;


/**
 * Class ViewException
 * @package Jan\Component\Templating\Exception
 */
class ViewException extends \Exception
{
      protected $code = 409;
      protected $message = '';
}