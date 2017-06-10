<?php

namespace Scaville\Lemon\Core\Engine;

use Scaville\Lemon\Core\Interfaces\Engine;

final class SettingsEngine implements Engine {
    
    static private $instance;
    static private $settings;

    private function __construct() {
        self::$settings = array();
        self::loadSettingFiles();
    }

    private function __clone() {
        
    }

    private function __wakeup() {
        
    }
    
    public static function singleton() {
        if(!isset(self::$instance)){
            $class = __CLASS__;
            self::$instance = new $class;
            unset($class);
        }
        return self::$instance;
    }
    

    private static function loadSettingFiles(){
        $root = new \RecursiveDirectoryIterator($_SERVER['DOCUMENT_ROOT']);
        $iterator = new \RecursiveIteratorIterator($root);
        $files = new \RegexIterator($iterator,'/^.+\.sv.cfg.php$/i',\RecursiveRegexIterator::GET_MATCH);
        foreach ($files as $file=>$content){
            self::$settings = array_replace_recursive(self::$settings, require_once str_replace('\\', DIRECTORY_SEPARATOR, $file));
            unset($file);
            unset($content);
        }
        unset($root);
        unset($iterator);
        unset($files);
    }
    
    public static function getSettings(){
        return self::$settings;
    }
    
}
