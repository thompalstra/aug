<?php
namespace aug\web;
class Controller implements ControllerInterface{
  public static function parseRequest($request){
    $ds = \Aug::$app->ds;

    $default = \Aug::$app->web["default"];
    $environmentRelativePath = \Aug::$app->environment->relativePath;
    $defaultParts = explode("/",trim($default, "/"));
    $params = [];
    array_pop($defaultParts);
    $controller = $defaultParts[count($defaultParts)-1];
    array_pop($defaultParts);
    $controllerNameSpace = implode("/",$defaultParts);

    $uriParts = explode("/",trim($request[0], "/"));
    $params = [];

    if(count($uriParts) > 0 && !empty($uriParts[count($uriParts)-1])){
      array_pop($uriParts);
    }
    if(count($uriParts) > 0 && !empty($uriParts[count($uriParts)-1])){
      $controller = $uriParts[count($uriParts)-1];
      array_pop($uriParts);
    }
    if(count($uriParts) > 0 && !empty($uriParts[count($uriParts)-1])){
      $controllerNameSpace = str_replace("/", "\\", implode("/",$uriParts)) . $ds;
    }
    $controllerName = self::parseControllerName($controller);
    $namespace = "{$environmentRelativePath}controllers{$ds}{$controllerNameSpace}{$controllerName}";
    if(class_exists($namespace)){
      $c = new $namespace();
      $c->id = $controller;
      $c->name = $controllerName;
      $c->layout = \Aug::$app->web["layout"];
      $c->layoutPath = "{$environmentRelativePath}layouts{$ds}";
      $c->viewPath = str_replace(["/","\\"], DIRECTORY_SEPARATOR, "{$environmentRelativePath}views{$ds}{$controllerNameSpace}{$controller}{$ds}");
      return $c;
    } else {

      \Aug::$app->controller->runError(new \Exception("{$namespace} does not exist"), 404); exit();
    }
  }
  public static function parseControllerName($controllerName){
    return str_replace(" ", "", ucwords(str_replace("-"," ", $controllerName))) . "Controller";
  }
  public function runError($exception){
    echo $exception->getMessage(); exit();
  }
  public function runAction($action){
    if(method_exists($this, $action->name)){
      $params = [];
      $reflection = new \ReflectionMethod($this, $action->name);
      foreach($reflection->getParameters() as $arg){
        if(isset($action->params[$arg->name])){
          $params[] = $action->params[$arg->name];
        } else {
          $params[] = null;
        }
      }
      call_user_func_array([$this, $action->name], $params);
    } else {
      // exception
      $this->runError(new \Exception("{$action->name} does not exist"));
    }
  }

  public function render($name, $data = []){
    $viewClass = \Aug::$app->web["viewClass"];
    $view = new $viewClass();
    $view->render($name, $data);
  }
  public function renderJson($data){
    echo json_encode($data); exit();
  }
}
