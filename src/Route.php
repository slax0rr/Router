<?php
/**
 * Route class of Router component
 *
 * The instance of a Route class is one route definition. Each Route must be
 * stored in the Container class.
 *
 * @package   SlaxWeb\Router
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.3
 */
namespace SlaxWeb\Router;

class Route
{
    /**
     * URI
     *
     * @var string
     */
    protected $_uri = "";

    /**
     * Method
     *
     * @var string
     */
    protected $_method = "";

    /**
     * Action
     *
     * @var callable
     */
    protected $_callable = null;

    /**
     * Method GET
     */
    const METHOD_GET = "GET";

    /**
     * Method POST
     */
    const METHOD_POST = "POST";

    /**
     * Method PUT
     */
    const METHOD_PUT = "PUT";

    /**
     * Method DELETE
     */
    const METHOD_DELETE = "DELETE";

    /**
     * Method Command Line Interface
     */
    const METHOD_CLI = "CLI";

    /**
     * Any Method
     */
    const METHOD_ANY = "*";

    /**
     * Set Route data
     *
     * Sets the retrieved data to internal properties. Prior to setting, the
     * method is checked, that it is valid, and raises an
     * 'InvalidMethodException' on error.
     *
     * @param string $uri Request URI regex without delimiter
     * @param string $method HTTP Request Method, accepts METHODO_* constant
     * @param callable $action Route action
     * @return void
     */
    public function set(string $uri, string $method, callable $action)
    {
        if (
            in_array(
                $method,
                [
                    self::METHOD_GET,
                    self::METHOD_POST,
                    self::METHOD_PUT,
                    self::METHOD_DELETE,
                    self::METHOD_CLI,
                    self::METHOD_ANY
                ]
            ) === false
        ) {
            throw new Exception\InvalidMethodException(
                "Route HTTP Method '{$method}' is not valid."
            );
        }

        $this->_uri = "~^{$uri}$~";
        $this->_method = $method;
        $this->_action = $action;
    }

    /**
     * Get magic method
     *
     * Used to retrieved protected class properties.
     *
     * @param string $param Name of the protected parameter, without the
     *                      underscore.
     * @return mixed
     */
    public function __get($param)
    {
        $property = "_{$param}";
        if (isset($this->{$property}) === false) {
            throw new Exception\UnknownPropertyException(
                "Property '{$param}' does not exist in " . __CLASS__
                . ", unable to get value."
            );
        }

        return $this->{$property};
    }
}
