<?php

namespace Scaville\Lemon\Core\Exceptions;

class ApplicationException extends AbstractException{
    public function __construct($message, $code = null, $previous = null) {
        parent::__construct($message, $code = null, $previous = null);
    }
}
