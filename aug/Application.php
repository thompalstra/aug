<?php
class Application{
  public function __construct($options = []){
    $this->root = dirname(__DIR__);
    $this->ds = DIRECTORY_SEPARATOR;

    $this->loadConfig($this->getConfig("common"));
    $this->loadRoutes($this->getRoutes("common"));
  }
  protected function getConfig($config){
    $fp = "{$this->root}{$this->ds}{$config}{$this->ds}config{$this->ds}config.php";
    if(file_exists($fp)){
      return include $fp;
    }
    return [];
  }
  protected function loadConfig($data){
    foreach($data as $k => $v){
      $this->$k = $v;
    }
  }
  protected function getRoutes($config){
    $fp = "{$this->root}{$this->ds}{$config}{$this->ds}config{$this->ds}config.php";
    if(file_exists($fp)){
      return include $fp;
    }
    return [];
  }
  protected function loadRoutes($data){
    $this->routes = $data;
  }
  public static function init(){
    include("autoloader.php");
    include("Aug.php");
    Aug::$app = new self();
    return Aug::$app;
  }
  public function prepare(){
    $this->environment = \aug\web\Environment::parseRequest($_SERVER["HTTP_HOST"]);
    return $this;
  }
  public function run(){

    $controllerClass = Aug::$app->web["controllerClass"];
    $actionClass = Aug::$app->web["actionClass"];
    $requestClass = Aug::$app->web["requestClass"];

    // $route = $controllerClass::handleRequest($_SERVER["REQUEST_URI"]);

    $route = $requestClass::handleRequest($_SERVER["REQUEST_URI"]);

    $controller = $controllerClass::parseRequest($route);
    $action = $actionClass::parseRequest($route);

    var_dump($action, $controller); die;
  }
}
