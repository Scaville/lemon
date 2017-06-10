<?php

namespace Scaville\Lemon\Core\Http;

class JSONResponse extends Response {

    public function __construct($message = '', $statusCode = 200, $data = null) {
        $this->content = $message;
        $this->statusCode = $statusCode;
        $this->data = $data;
        parent::__construct($message, $statusCode);
    }

    public function sendResponse() {
        return $this->sendJsonResponse();
    }

    /**
     * Send Json Response.
     */
    public function sendJsonResponse() {
        header_remove();
        if (substr(php_sapi_name(), 0, 3) == 'cgi') {
            header('Status: ' . $this->statusCode . ' ' . $this->status[$this->statusCode]);
        } else {
            header('HTTP/1.1 ' . $this->statusCode . ' ' . $this->status[$this->statusCode]);
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array(
            'status' => $this->statusCode,
            'message' => $this->content,
            'data' => $this->data
                ), JSON_UNESCAPED_UNICODE);
    }

}
