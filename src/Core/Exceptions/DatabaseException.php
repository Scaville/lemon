<?php

namespace Scaville\Lemon\Core\Exceptions;

class DatabaseException extends AbstractException {
    public function __construct($message, $code, $previous) {
        parent::__construct($message, $code, $previous);
    }
}
