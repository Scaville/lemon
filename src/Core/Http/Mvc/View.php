<?php

namespace Scaville\Lemon\Core\Http\Mvc;

use Scaville\Lemon\Core\Application;
use Scaville\Lemon\Providers\Settings;

class View {

    protected $layout = 'layout\layout';
    protected $body = 'layout\body';
    protected $title;
    protected $scripts = array();
    protected $styles = array();
    protected $meta = array();
    protected $headerTags = array();
    protected $variables = array();
    protected $paths = array();
    private $render = "";

    /**
     * Constructor.
     * @param array $options
     */
    public function __construct(array $options) {
        $this->paths = Application::getProvider(Settings::class)->getModuleSetting('paths');
        $this->setTitle((isset($options['title'])) ? $options['title'] : '');
        $this->addControllerScripts($options);
        $this->addControllerStyles($options);
        $this->addControllerMetaTags($options);
        if (isset($options['variables'])) {
            $this->variables = $options['variables'];
        }
        if (isset($options['layout'])) {
            $this->layout = $options['layout'];
        }
        if (isset($options['body'])) {
            $this->body = $options['body'];
        }
    }

    /**
     * Addes to the dom all controller scripts.
     * @param array $options
     */
    private function addControllerScripts(array $options) {
        if (isset($options['scripts'])) {
            foreach ($options['scripts'] as $key => $stack) {
                if ($key === 'full') {
                    foreach ($stack as $index=>$script) {
                        $this->addScript($script);
                    }
                } else {
                    foreach ($stack as $script) {
                        $this->addScript($this->paths['assets'] . $key . "/" . str_replace(".js", "", $script) . ".js");
                    }
                }
            }
        }
    }

    /**
     * Addes to the dom all controller styles.
     * @param array $options
     */
    private function addControllerStyles(array $options) {
        if (isset($options['styles'])) {
            foreach ($options['styles'] as $key => $stack) {
                if ($key === 'full') {
                    foreach ($stack as $index=>$style) {
                        $this->addStyle($style);
                    }
                } else {
                    foreach ($stack as $style) {
                        $this->addStyle($this->paths['assets'] . $key . "/" . str_replace(".css", "", $style) . ".css");
                    }
                }
            }
        }
    }

    /**
     * Addes to the dom the controller meta tags.
     * @param array $options
     */
    private function addControllerMetaTags(array $options) {
        if (isset($options['meta'])) {
            foreach ($options['meta'] as $meta) {
                $this->addMetaTag($meta);
            }
        }
    }

    public function addScript($script, $index = null) {
        if (null !== $index) {
            $this->scripts[$index] = "<script type='text/javascript' src='{$script}'></script>";
        } else {
            $this->scripts[] = "<script type='text/javascript' src='{$script}'></script>";
        }
    }

    public function addStyle($style, $index = null) {
        if (null !== $index) {
            $this->styles[$index] = "<link rel='stylesheet' type='text/css' href='{$style}'/>";
        } else {
            $this->styles[] = "<link rel='stylesheet' type='text/css' href='{$style}'/>";
        }
    }

    public function addMetaTag($meta) {
        $this->meta[] = $meta;
        return $this;
    }

    public function addHeaderTag($tag) {
        $this->headerTags[] = $tag;
    }

    private function loadFile($filename) {
        ob_start();
        $file = str_replace('\\', '/', $this->paths['views'] . DIRECTORY_SEPARATOR . str_replace('.php', '', $filename) . '.php');
        if (file_exists($file)) {
            require_once($file);
        }
        return ob_get_clean();
    }

    /**
     * Render the View.
     */
    public function render() {
        $this->openDocument();
        $this->write($this->loadFile($this->layout));
        echo $this->render;
    }

    /**
     * Generates the start of the document structure.
     * @return string
     */
    private function openDocument() {
        return $this->write("<!--"
                        . "\n\tLEMON FRAMEWORK®"
                        . "\n\n\tAuthor: Fabricio Paulo"
                        . "\n\tDistributor: SCAVILLE™ BRASIL INC."
                        . "\n\t© Copyright " . date("Y") . " - All rights reserved."
                        . "\n\n\tThis document was automatically generated"
                        . "\n\tby the Lemon Framework render engine."
                        . "\n-->\n");
    }

    /**
     * Write in render engine a content.
     * @param string $str
     * @return string
     */
    private function write($str) {
        return $this->render .= $str;
    }

    protected function writeTitle() {
        echo "<title>" . $this->title . "</title>\n";
    }

    protected function writeScripts() {
        foreach ($this->scripts as $script) {
            echo "\t\t" . $script . "\n";
        }
    }

    protected function writeStyles() {
        foreach ($this->styles as $style) {
            echo "\t\t" . $style . "\n";
        }
    }

    protected function writeMeta() {
        foreach ($this->meta as $meta) {
            echo "\t\t" . $meta . "\n";
        }
    }

    protected function writeHeaderTags() {
        foreach ($this->headerTags as $tag) {
            echo "\t\t" . $tag . "\n";
        }
    }

    protected function writeBody() {
        echo "\n" . str_replace("\n", "\n\t\t", "\t\t" . $this->loadFile($this->body)) . "\n";
    }

    /**
     * Insert on the view one file.
     * @param string $name
     */
    public function partial($name) {
        $file = ($this->paths['views'] . '/' . str_replace('\\', '/', str_replace('.php', '', trim($name))) . '.php');
        if (file_exists($file)) {
            require_once $file;
        }
    }

    public function getVariable($name) {
        return $this->variables[$name];
    }
    
    public function setVariable($name,$value){
        $this->variables[$name] = $value;
        return $this;
    }

    public function getLayout() {
        return $this->layout;
    }

    public function getBody() {
        return $this->body;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getScripts() {
        return $this->scripts;
    }

    public function getStyles() {
        return $this->styles;
    }

    public function getMeta() {
        return $this->meta;
    }

    public function getHeaderTags() {
        return $this->headerTags;
    }

    public function getVariables() {
        return $this->variables;
    }

    public function getPaths() {
        return $this->paths;
    }

    public function setLayout($layout) {
        $this->layout = $layout;
        return $this;
    }

    public function setBody($body) {
        $this->body = $body;
        return $this;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function setScripts($scripts) {
        $this->scripts = $scripts;
        return $this;
    }

    public function setStyles($styles) {
        $this->styles = $styles;
        return $this;
    }

    public function setMeta($meta) {
        $this->meta = $meta;
        return $this;
    }

    public function setHeaderTags($headerTags) {
        $this->headerTags = $headerTags;
        return $this;
    }

    public function setVariables($variables) {
        $this->variables = $variables;
        return $this;
    }

    public function setPaths($paths) {
        $this->paths = $paths;
        return $this;
    }

}
