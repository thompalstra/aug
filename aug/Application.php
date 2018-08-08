<?php
class Application{
  public function __construct($options = []){
    $this->root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
    $this->ds = DIRECTORY_SEPARATOR;

    $this->loadConfig($this->getConfig("common"));
    $this->loadRoutes($this->getRoutes("common"));
  }
  protected function getConfig($config){
    $fp = "{$this->root}{$config}{$this->ds}config{$this->ds}config.php";
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
    $fp = "{$this->root}{$config}{$this->ds}config{$this->ds}routes.php";
    if(file_exists($fp)){
      return include $fp;
    }
    return [];
  }
  protected function loadRoutes($data){
    foreach($data as $k => $v){
      $this->routes[$k] = $v;
    }
  }
  protected function getEnvironmentConfig($config){
    $fp = "{$this->root}{$config}{$this->ds}config{$this->ds}config.php";
    if(file_exists($fp)){
      return include $fp;
    }
    return [];
  }
  protected function loadEnvironmentConfig($data){
    foreach($data as $k => $v){
      $this->$k = $v;
    }
  }
  protected function getEnvironmentRoutes($config){
    $fp = "{$this->root}{$config}config{$this->ds}routes.php";
    if(file_exists($fp)){
      return include $fp;
    }
    return [];
  }
  protected function loadEnvironmentRoutes($data){
    foreach($data as $k => $v){
      $this->routes[$k] = $v;
    }
  }
  public static function init(){
    include("autoloader.php");
    include("Aug.php");
    Aug::$app = new self();
    return Aug::$app;
  }
  public function prepare(){
    $this->environment = \aug\web\Environment::parseRequest($_SERVER["HTTP_HOST"]);

    $this->loadEnvironmentConfig($this->getEnvironmentConfig($this->environment->relativePath));
    $this->loadEnvironmentRoutes($this->getEnvironmentRoutes($this->environment->relativePath));

    $controllerClass = \Aug::$app->web["controllerClass"];
    $actionClass = Aug::$app->web["actionClass"];
    // Aug::$app->controller = $controllerClass::parseRequest(\Aug::$app->web["default"]);
    // Aug::$app->action = $controllerClass::parseRequest(\Aug::$app->web["default"]);
    Aug::$app->controller = new $controllerClass();
    Aug::$app->action = new $actionClass("home", []);
    return $this;
  }
  public function run(){

    $controllerClass = Aug::$app->web["controllerClass"];
    $actionClass = Aug::$app->web["actionClass"];
    $requestClass = Aug::$app->web["requestClass"];

    $route = $requestClass::handleRequest($_SERVER["REQUEST_URI"]);

    Aug::$app->controller = $controllerClass::parseRequest($route);
    Aug::$app->action = $actionClass::parseRequest($route);

    Aug::$app->controller->runAction(Aug::$app->action);
  }
}
