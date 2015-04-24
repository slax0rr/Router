<?php
namespace \SlaxWeb\Router;

use SlaxWeb\Router\Exceptions as E;

class Request
{
    protected $_uri = "";
    protected $_domain = "";
    protected $_method = "";

    public function __construct()
    {
        $this->_setUp();
    }

    public function __get($param)
    {
        $property = "_{$param}";
        if (isset($this->{$property})) {
            return $this->{$property};
        }

        return null;
    }

    protected function _setUp()
    {
        if (isset($_SERVER["HTTP_HOST"]) === false) {
            throw new E\RequestException("HTTP_HOST not defined. Review your WebServer configuration", 500);
        }
        $this->_domain = $_SERVER["HTTP_HOST"];

        if (isset($_SERVER["REQUEST_METHOD"]) === false) {
            throw new E\RequestException("REQUEST_METHOD not defined. Review your WebServer configuration", 500);
        }
        $this->_method = $_SERVER["REQUEST_METHOD"];

        if (isset($_SERVER["SERVER_FILENAME"]) === false) {
            throw new E\RequestException("SERVER_FILENAME not defined. Review your WebServer configuration", 500);
        }
        $scriptName = str_replace(".", "\.", basename($_SERVER["SERVER_FILENAME"]));
        $this->_uri = preg_replace("~^/{$scriptName}~", "", $_SERVER["REQUEST_URI"]);
    }
}
