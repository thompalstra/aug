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

    \aug\db\Connection::initialize('mysql:host=localhost;dbname=query_db', "root", "");

    $this->environment = \aug\web\Environment::parseRequest($_SERVER["HTTP_HOST"]);

    $this->loadEnvironmentConfig($this->getEnvironmentConfig($this->environment->relativePath));
    $this->loadEnvironmentRoutes($this->getEnvironmentRoutes($this->environment->relativePath));

    $identityClass = Aug::$app->web["identityClass"];

    session_start();

    Aug::$app->identity = new $identityClass();
    Aug::$app->identity->restore();

    $requestClass = Aug::$app->web["requestClass"];

    Aug::$app->request = new $requestClass();

    $controllerClass = Aug::$app->web["controllerClass"];
    $actionClass = Aug::$app->web["actionClass"];

    Aug::$app->controller = new $controllerClass();
    Aug::$app->action = new $actionClass("index", []);

    return $this;
  }
  public function run(){

    $controllerClass = Aug::$app->web["controllerClass"];
    $actionClass = Aug::$app->web["actionClass"];
    $requestClass = Aug::$app->web["requestClass"];

    $route = Aug::$app->request->handleRequest($_SERVER["REQUEST_URI"]);

    Aug::$app->controller = $controllerClass::parseRequest($route);
    Aug::$app->action = $actionClass::parseRequest($route);

    Aug::$app->controller->runAction(Aug::$app->action);
  }
  public function setFlash($key, $message){
    if(!isset($_SESSION["flash"])){
      $_SESSION["flash"] = [];
    }
    $_SESSION["flash"][$key] = $message;
  }
  public function getFlash($key){
    if(isset($_SESSION["flash"]) && isset($_SESSION["flash"][$key])){
      ob_start();
      echo $_SESSION["flash"][$key];
      $flash = ob_get_contents();
      ob_end_clean();
      unset($_SESSION["flash"][$key]);
      return $flash;
    }
  }
}
