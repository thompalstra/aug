<?php
namespace aug\web;
class Controller implements ControllerInterface{
  public static function parseRequest($request){
    $ds = \Aug::$app->ds;

    $default = \Aug::$app->web["default"];
    $environmentName = \Aug::$app->environment->name;

    $explode = explode("/", $default);
    array_shift($explode);
    $controllerName = $explode[0];
    $controllerPath = "";

    $explode = explode("/", $request);
    array_shift($explode); // remove first empty instance
    if(count($explode) > 1){
      array_pop($explode);
      $controllerName = $explode[count($explode)-1];
      array_pop($explode);
    }
    if(count($explode) >= 1){
      $controllerPath = implode(DIRECTORY_SEPARATOR, $explode) . $ds;
    }
    $controllerName = self::parseControllerName($controllerName);

    return "{$environmentName}{$ds}{$controllerPath}{$controllerName}";
  }
  public static function parseControllerName($controllerName){
    return str_replace(" ", "", ucwords(str_replace("-"," ", $controllerName))) . "Controller";
  }
}
