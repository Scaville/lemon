<?php

namespace Scaville\Lemon\Core\Http;

class ViewResponse extends Response {

    public function __construct($content, $statusCode = 200) {
        $this->statusCode = $statusCode;
        $this->content = $content;
        parent::__construct($content, $statusCode);
    }

    public function sendResponse() {
        return $this->sendViewResponse();
    }

    /**
     * Send View Response.
     */
    public function sendViewResponse() {
        return $this->content->render();
    }

}
