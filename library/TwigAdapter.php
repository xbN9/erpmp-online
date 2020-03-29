<?php
use Yaf\View_Interface;

class TwigAdapter implements View_Interface {

    protected $loader; //模板加载路径
    protected $twig; //模板实例化
    protected $variables = array();

    /**
     * 初始化
     */
    public function __construct() {
        //Twig_Autoloader::register();
        $this->loader = new Twig_Loader_Filesystem();
        $this->twig   = new Twig_Environment($this->loader, array(
            'debug' => true,
            'cache' => false,
            //'autoescape' => array($this, 'escapingStrategyCallback'),
        ));
        $this->twig->addExtension(new Twig_Extension_Debug());
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name) {
        return isset($this->variables[$name]);
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value) {
        $this->variables[$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        return $this->variables[$name];
    }

    /**
     * @param string $name
     */
    public function __unset($name) {
        unset($this->variables[$name]);
    }

    /**
     * Return twig instance
     * @return \Twig_Environment
     */
    public function getTwig() {
        return $this->twig;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    public function assign($name, $value = null) {
        $this->variables[$name] = $value;
    }

    /**
     * @param string $template
     * @param array  $variables
     * @return bool
     */
    public function display($template, $variables = null) {
        echo $this->render($template, $variables);
    }

    /**
     * @return string
     */
    public function getScriptPath() {
        return $this->loader->getPaths();
    }

    /**
     * @param string $template
     * @param array  $variables
     * @return string
     */
    public function render($template, $variables = null) {
        if (is_array($variables)) {
            $this->variables = array_merge($this->variables, $variables);
        }
        return $this->twig->loadTemplate($template)->render($this->variables);
    }

    /**
     * @param string $templateDir
     * @return void
     */
    public function setScriptPath($templateDir) {
        $this->loader->setPaths($templateDir);
    }
}
