<?php

namespace Scaville\Lemon\Core\Exceptions;

class AbstractException extends \Exception{
    public function __construct($message, $code, $previous) {
        parent::__construct($message, $code, $previous);
    }
}
