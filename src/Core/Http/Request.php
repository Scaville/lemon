<?php

namespace Scaville\Lemon\Core\HTTP;

class Request {

    private $requestUri;
    private $allowMethods = [];
    private $params = [];
    private $headers = [];
    private $route;

    public function __construct() {
        if ($this->isOptions()) {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: ' . implode(',', $this->allowMethods));
            header('Access-Control-Allow-Headers: X-Requested-With, Authorization');
            header('Access-Control-Allow-Credentials: true');
            header("HTTP/1.1 200 OK");
            die(" ");
        }

        $this->initializeRequest();
    }

    /**
     * Start the request.
     */
    private function initializeRequest() {
        $this->loadHeaders();
        $this->requestUri = $this->headers['REQUEST_URI'];

        if ($this->isPost() || $this->isPut()) {
            $this->extractPostParams();
        } else if ($this->isGet()) {
            $this->extractGetParams();
        }
    }

    /**
     * Load the headers from the request.
     */
    private function loadHeaders() {
        foreach ($_SERVER as $key => $value) {
            if (
                    (strpos($key, 'HTTP') !== false) ||
                    (strpos($key, 'APP') !== false) ||
                    (strpos($key, 'REQUEST') !== false)
            ) {
                $this->headers = array_merge($this->headers, array($key => $value));
            }
        }
        $this->headers = array_merge($this->headers, array('HTTP_CLIENT_IP' => $_SERVER['REMOTE_ADDR']));
    }

    /**
     * Add a header to the request.
     * @param string $header
     * @param string $value
     * @return Request
     */
    public function addHeader($header, $value) {
        $this->headers = array_merge($this->headers, array($header => $value));
        return $this;
    }

    /**
     * Add headers to the request.
     * @param array $headers
     * @return Request
     */
    public function addHeaders(array $headers) {
        foreach ($headers as $header => $value) {
            $this->addHeader($header, $value);
        }
        return $this;
    }

    /**
     * Removes a header from the request.
     * @param string $header
     * @return Request
     */
    public function removeHeader($header) {
        if (array_key_exists($header, $this->headers)) {
            unset($this->headers[$header]);
        }
        return $this;
    }

    /**
     * Remove headers from the request.
     * @param array $headers
     * @return Request
     */
    public function removeHeaders(array $headers) {
        foreach ($headers as $header) {
            $this->removeHeader($header);
        }
        return $this;
    }

    /**
     * Add a method http allowed on the request.
     * @param string $method
     * @return Request
     */
    public function addAllowMethod($method) {
        if (!array_key_exists($method, $this->allowMethods)) {
            $this->allowMethods[] = strtoupper($method);
        }
        return $this;
    }

    /**
     * Add methods http allowed on the request.
     * @param array $methods
     * @return Request
     */
    public function addAllowMethods(array $methods) {
        foreach ($methods as $method) {
            $this->addAllowMethod($method);
        }
        return $this;
    }

    /**
     * Add a parameter to the request.
     * @param string $param
     * @param string $value
     * @return Request
     */
    public function addParam($param, $value) {
        if (!array_key_exists($param, $this->params)) {
            $this->params[$param] = $value;
        }
        return $this;
    }

    /**
     * Add parameters to the request.
     * @param array $params
     * @return Request
     */
    public function addParams(array $params) {
        foreach ($params as $param => $value) {
            $this->addParam($param, $value);
        }
        return $this;
    }

    /**
     * Verify if the request follow the GET method.
     * @return boolean
     */
    public function isGet() {
        return $this->verifyMethod("GET");
    }

    /**
     * Verify if the request follow the POST method
     * @return boolean
     */
    public function isPost() {
        return $this->verifyMethod("POST");
    }

    /**
     * Verify if the request follow the PUT method
     * @return boolean
     */
    public function isPut() {
        return $this->verifyMethod("PUT");
    }

    /**
     * Verify if the request follow the DELETE method
     * @return boolean
     */
    public function isDelete() {
        return $this->verifyMethod("DELETE");
    }

    /**
     * Verify if the request follow the OPTIONS method
     * @return boolean
     */
    public function isOptions() {
        return $this->verifyMethod("OPTIONS");
    }

    /**
     * Extract the parameters from the POST request.
     */
    private function extractPostParams() {
        $jsonParams = json_decode(file_get_contents("php://input"), false);

        if (null !== $jsonParams) {
            foreach ($jsonParams as $key => $value) {
                $this->params[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * Extract the parameters from the GET request.
     */
    private function extractGetParams() {
        $this->params = $_REQUEST;
        return $this;
    }

    /**
     * Verify if the request corresponds to the method expected. 
     * @param string $method
     * @return boolean
     */
    private function verifyMethod($method) {
        if ($_SERVER["REQUEST_METHOD"] === $method) {
            return true;
        } else {
            return false;
        }
    }

    public function getRequestUri() {
        return $this->requestUri;
    }

    public function getAllowMethods() {
        return $this->allowMethods;
    }

    public function getParams() {
        return $this->params;
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function getRoute() {
        return $this->route;
    }

    public function setRequestUri($requestUri) {
        $this->requestUri = $requestUri;
        return $this;
    }

    public function setAllowMethods($allowMethods) {
        $this->allowMethods = $allowMethods;
        return $this;
    }

    public function setParams($params) {
        $this->params = $params;
        return $this;
    }

    public function setHeaders($headers) {
        $this->headers = $headers;
        return $this;
    }

    public function setRoute($route) {
        foreach ($route->getParams() as $key => $param){
            $this->addParam($key, $param);
        }
        $this->route = $route;
        return $this;
    }

}
