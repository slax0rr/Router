<?php
namespace SlaxWeb\Router;

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

    public function __toString()
    {
        return "{$this->_domain} ({$this->_method}) {$this->_uri}";
    }

    protected function _setUp()
    {
        // check if request is from CLI
        if (php_sapi_name() === "cli") {
            $options = getopt("u:", ["uri:"]);
            if (isset($options["u"])) {
                $options["uri"] = $options["u"];
            }
            $this->_uri = $options["uri"];
            $this->_method = "CLI";
            $this->_domain = "Command Line";
        } else {
            // normal WEB request
            if (isset($_SERVER["REQUEST_METHOD"]) === false) {
                throw new E\RequestException("REQUEST_METHOD not defined. Review your WebServer configuration", 500);
            }
            $this->_method = $_SERVER["REQUEST_METHOD"];

            if (isset($_SERVER["SCRIPT_FILENAME"]) === false) {
                throw new E\RequestException("SCRIPT_FILENAME not defined. Review your WebServer configuration", 500);
            }
            $scriptName = str_replace(".", "\.", basename($_SERVER["SCRIPT_FILENAME"]));
            $this->_uri = preg_replace("~^/{$scriptName}~", "", $_SERVER["REQUEST_URI"]);
        }

        if (isset($_SERVER["HTTP_HOST"]) === false) {
            throw new E\RequestException("HTTP_HOST not defined. Review your WebServer configuration", 500);
        }
        $this->_domain = $_SERVER["HTTP_HOST"];
    }
}
