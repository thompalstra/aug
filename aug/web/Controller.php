<?php
namespace aug\web;
use aug\helpers\File;
use aug\helpers\ClassHelper;
class Controller implements ControllerInterface{

  public $title = "My website";

  public static function parseRequest($request){
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
      $controllerNameSpace = implode("/", $uriParts) . "/";
    }
    $controllerName = ClassHelper::toCamelCase($controller."Controller");
    $namespace = ClassHelper::toNamespace("{$environmentRelativePath}controllers/{$controllerNameSpace}{$controllerName}");

    if(class_exists($namespace)){
      $c = new $namespace();
      $c->id = $controller;
      $c->name = $controllerName;
      $c->layout = \Aug::$app->web["layout"];
      $c->layoutPath = File::path("{$environmentRelativePath}layouts/");
      $c->viewPath = File::path("{$environmentRelativePath}views/{$controllerNameSpace}{$controller}/");
      return $c;
    } else {

      \Aug::$app->controller->runError(new \Exception("{$namespace} does not exist"), 404); exit();
    }
  }
  public function runError($exception){
    echo $exception->getMessage(); exit();
  }
  public function beforeAction(){ return true; }
  public function isAllowed($action){
    if(method_exists($this, "rules")){
      $rules = $this->rules();
      foreach($rules as $actionId => $rule){
        if($actionId == $action->id || $actionId == "*"){
          $allow = $rule["allow"];
          if($allow === false){
            if(isset($rule["onDeny"])){
              call_user_func_array($rule["onDeny"], [$rule]);
            }
            return false;
          } else {
            if(isset($rule["onAllow"])){
              call_user_func_array($rule["onAllow"], [$rule]);
            }
            return true;
          }
        }
      }
    }
    return true;
  }
  public function runAction($action){
    if(call_user_func_array([$this, "isAllowed"], [$action]) !== false){
      if(call_user_func_array([$this, "beforeAction"], [$action])){
        if(method_exists($this, $action->name)){
          $params = [];
          $reflection = new \ReflectionMethod($this, $action->name);
          foreach($reflection->getParameters() as $arg){
            if(isset($action->params[$arg->name])){
              $params[] = $action->params[$arg->name];
            }
          }
          if(count($params) == count($reflection->getParameters())){
            call_user_func_array([$this, $action->name], $params);
          } else {
            $this->runError(new \Exception("Invalid parameters provided for {$action->name}"));
          }
        } else {
          $this->runError(new \Exception("{$action->name} does not exist"));
        }
      }
    } else {
      header("Location: /login"); exit();
    }
  }

  public function render($name, $data = []){
    $viewClass = \Aug::$app->web["viewClass"];
    $view = new $viewClass();
    $view->render($name, $data);
  }
  public function renderPartial($name, $data = []){
    $viewClass = \Aug::$app->web["viewClass"];
    $view = new $viewClass();
    $view->renderPartial($name, $data);
  }
  public function renderJson($data){
    echo json_encode($data); exit();
  }
  public function redirect($url){
    header("Location: {$url}"); exit();
  }


}
