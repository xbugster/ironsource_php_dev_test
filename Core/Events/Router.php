<?php
/**
 * @desc    Routing event
 * @author  Valentin Ruskevych <leaderpvp@gmail.com>
 */

namespace Core\Events;


use Core\Http\Request;
use Core\Http\Response;

class Router implements \SplObserver
{

    /**
     * @desc array holding map of request method to action
     * @var array
     */
    private $_restMethodMap = array(
        'get' => 'show',
        'post' => 'create',
        'put' => 'update',
        'patch' => 'update',
        'delete' => 'remove',
    );

    /**
     * @param \SplSubject|\Core\App $subject
     */
    public function update(\SplSubject $subject) : void
    {
        if ($subject->is_halted()) { return; }
        $registry = $subject->getRegistry();
        $routeDispatchConfig = $this->findRoutesDispatchConfig(
            $registry->get('routes'),
            $registry->get('request')->getPath(),
            $registry->get('request')->getRequestMethod()
        );

        if (is_null($routeDispatchConfig)) {
            $subject->halt(
                'Request could not be satisfied. Please follow api documentation, which yet does not exist :-P'
            );
            return;
        }
        $response = $this->dispatch(
            $registry->get('config')['application']['controllers_namespace'],
            $registry->get('request'),
            $routeDispatchConfig['controller'],
            $routeDispatchConfig['action']
        );

        $subject->setResponse($response);
    }

    /**
     * @desc Dispatcher to controller.
     * @param string $controllersNamespace
     * @param Request $requestObject
     * @param string $controllerName
     * @param string $actionName
     * @return Response
     */
    public function dispatch(
        $controllersNamespace = null,
        Request $requestObject,
        $controllerName = null,
        $actionName = null) : Response
    {
        $qualifiedControllerNamespace = $controllersNamespace . ucfirst(strtolower($controllerName)) . 'Controller';
        $actionName = $actionName . 'Action';
        # Dynamic call due to nature.
        $controller = new $qualifiedControllerNamespace($requestObject);
        return $controller->{$actionName}(); # must return instance of Core\Http\Response; !
    }

    /**
     * @desc Try finding routing RESOURCE (REST Resource)
     * @param array $routes
     * @param string|null $routeName
     * @param string|null $method
     * @return array|null
     */
    public function getRoutingResourceConfig(
        array $routes = array(),
        $routeName = null,
        $method = null) :? array
    {
        if (empty($routes) || !isset($routes['resources'])) {
            return null;
        }

        $routeConfig = $routes['resources'][$routeName] ?? null;
        if ( is_null( $routeConfig ) || !isset( $this->_restMethodMap[$method] ) ) {
            return null;
        }
        $routeConfig['dispatch']['action'] = strtolower($this->_restMethodMap[$method]);
        return $routeConfig;
    }

    /**
     * @desc Try finding standard route(not resource).
     * @param array $routes
     * @param null $routeName
     * @return array|null
     */
    public function getRoutingRouteConfig(array $routes = array(), $routeName = null) :? array {
        if (empty($routes)
            || !isset($routes['routes']))
        {
            return null;
        }

        return $routes['routes'][$routeName] ?? null;
    }

    /**
     * @desc check if Routing Allowed for method
     * @param array $routeConfig
     * @param null $method
     * @return bool
     */
    public function isEnrouteAllowed(array $routeConfig = array(), $method = null) : bool {
        if (isset($routeConfig['methods'])
            && isset($routeConfig['methods'][$method])
            && isset($routeConfig['dispatch'])
        ) {
            return true;
        }
        return false;
    }

    /**
     * @param $routes
     * @param $path
     * @param $method
     * @return array|null
     */
    public function findRoutesDispatchConfig($routes, $path, $method) :? array {
        $routeName = ltrim($path, DIRECTORY_SEPARATOR);
        $routeConfig = $this->getRoutingResourceConfig($routes, $routeName, $method);

        if (is_null($routeConfig)) { # if resource not found, try finding route.
            $routeConfig = $this->getRoutingRouteConfig($routes, $routeName);
        }
        if (!is_null($routeConfig) && $this->isEnrouteAllowed($routeConfig, $method)) {
            return $routeConfig['dispatch'];
        }
        return null;
    }
}