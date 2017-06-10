<?php

namespace Scaville\Lemon\Core\HTTP;

class Route {

    private $name;
    private $module;
    private $controller;
    private $action;
    private $params;

    public function __construct($module = null, $controller = null, $action = null, $params = array()) {
        $this->module = $module;
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;
    }

    public function getName() {
        return $this->name;
    }

    public function getModule() {
        return $this->module;
    }

    public function getController() {
        return $this->controller;
    }

    public function getAction() {
        return $this->action;
    }

    public function getParams() {
        return $this->params;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setModule($module) {
        $this->module = $module;
        return $this;
    }

    public function setController($controller) {
        $this->controller = $controller;
        return $this;
    }

    public function setAction($action) {
        $this->action = $action;
        return $this;
    }

    public function setParams($params) {
        $this->params = $params;
        return $this;
    }
    
    public function addParam($param = null, $value = null){
        if(null !== $param){
            $this->params[$param] = $value;
        }else{
            $this->params[] = $value;
        }
    }

}
