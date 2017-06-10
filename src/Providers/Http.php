<?php

namespace Scaville\Lemon\Providers;

use Scaville\Lemon\Core\Application;
use Scaville\Lemon\Core\Http\Request;
use Scaville\Lemon\Core\Interfaces\Provider;
use Scaville\Lemon\Core\Http\Route;
use Scaville\Lemon\Factories\ControllerFactory;
use Scaville\Lemon\Factories\ResponseFactory;

class Http implements Provider {

    private $routeParams;

    public function __construct() {
        
    }

    /**
     * Open the requested route.
     */
    public function openRoute() {
        $request = $this->getRequest();

        if (null !== $request) {
            $route = $request->getRoute();
            $controller = ControllerFactory::createInstance($request->getRoute());
            $action = $route->getAction();
            if (null !== $controller) {
                if (!method_exists($controller, $action)) {
                    throw new \Exception("A ação não existe!");
                }
                return ResponseFactory::factory($controller->$action());
            }
        }
    }

    /**
     * Returns the request.
     * @return Request
     */
    public function getRequest() {
        $request = new Request();

        if (false !== strpos($request->getRequestUri(), '.')) {
            return null;
        }

        $request->setRoute($this->extract($request));

        return $request;
    }

    /**
     * Mount the route request elements.
     * @param Request $request
     * @return Request
     */
    private function extract($request) {
        $route = new Route();
        $routes = Application::getProvider(Settings::class)->getApplicationSetting('route');
        if ($request->getRequestUri() === '/') {
            $defaultRoute = $routes['default'];
            $route->setName('default');
            $route->setModule($defaultRoute['module']);
            $route->setController($defaultRoute['controller']);
            $route->setAction($defaultRoute['controller']['action']);
            unset($defaultRoute);
        } else {
            $uri = array_map('strtolower', explode('/', substr($request->getRequestUri(), 1, strlen($request->getRequestUri()))));
            $uriModule =  isset($uri[0]) ? $uri[0] : null;
            $uriController = isset($uri[1]) ? $uri[1] : null;
            $uriAction = isset($uri[2]) ? $uri[2] : null;
            unset($uri[0]);
            unset($uri[1]);
            unset($uri[2]);
            $uri = array_values($uri);
            unset($routes['default']);
            foreach ($routes as $module => $stack) {
                if (strtolower($module) === $uriModule) {
                    $stack = array_change_key_case($stack, CASE_LOWER);
                    if (array_key_exists($uriController, $stack)) {
                        $route->setModule($uriModule);
                        $route->setController($stack[$uriController]);
                        $route->setAction((isset($uriAction) && '' !== trim($uriAction)) ? $uriAction : 'index');
                        $params = null;
                        if (isset($stack[$uriController]['actions'][$uriAction]['params'])) {
                            $params = $stack[$uriController]['actions'][$uriAction]['params'];
                        }
                        if (null !== $params) {
                            foreach ($params as $key => $value) {
                                if(isset($uri[$value])){
                                    $route->addParam($key, $uri[$value]);
                                }else{
                                    $route->addParam($key, null);
                                }
                            }
                        } else {
                            $route->setParams($uri);
                        }
                    }
                    break;
                }
            }
            unset($uriModule);
            unset($uriController);
            unset($uriAction);
            unset($uri);
        }
        unset($routes);

        return $route;
    }

}
