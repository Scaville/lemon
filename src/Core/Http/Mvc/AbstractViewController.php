<?php

namespace Scaville\Lemon\Core\Http\Mvc;

use Scaville\Lemon\Core\Application;
use Scaville\Lemon\Providers\Http;

abstract class AbstractViewController {

    protected function getScripts() {
        return array();
    }

    protected function getStyles() {
        return array();
    }
    
    protected function getRequest(){
        return Application::getProvider(Http::class)->getRequest();
    }
}
