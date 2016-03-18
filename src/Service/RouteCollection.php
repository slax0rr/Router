<?php
/**
 * Route Collection Helper
 *
 * The Route Collection Helper provides an easy way to add Routes to the
 * Container.
 *
 * @package   SlaxWeb\Router
 * @author    Tomaz Lovrec <tomaz.lovrec@gmail.com>
 * @copyright 2016 (c) Tomaz Lovrec
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://github.com/slaxweb/
 * @version   0.3
 */
namespace SlaxWeb\Router\Service;

abstract class RouteCollection implements \Pimple\ServiceProviderInterface
{
    /**
     * Container
     *
     * @var \Pimple\Container
     */
    protected $_container = null;

    /**
     * Routes
     *
     * @var array
     */
    protected $_routes = [];
 
    /**
     * Register Service
     *
     * Method called by the Pimple\Container when registering this service.
     * From here the 'define' method is called, and then the protected property
     * '_routes' is iterated, and all found routes are added to the Route
     * Container. Also exposes the received DIC to the protected property.
     *
     * @param \Pimple\Container $container DIC
     * @return void
     */
    public function register(\Pimple\Container $container)
    {
        $this->_container = $container;
        $this->define();

        foreach ($this->_routes as $route) {
            $newRoute = $container["router.newRoute"];
            $newRoute->set(
                ($route["uri"] ?? null),
                ($route["method"] ?? null),
                ($route["action"] ?? null)
            );

            $container["routesContainer.service"]->add($newRoute);
        }
    }

    /**
     * Define Routes
     *
     * This method is called when the service is registered, and can be used to
     * add new route definitions to the internal container property.
     *
     * @return void
     */
    abstract public function define();
}
