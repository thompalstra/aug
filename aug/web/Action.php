<?php
namespace aug\web;
class Action implements ActionInterface{
  public static function parseRequest($request){
    $ds = \Aug::$app->ds;

    $default = \Aug::$app->web["default"];
    $explode = explode("/", $default);
    array_shift($explode);
    $actionName = $explode[0];

    $explode = explode("/", $request);
    array_shift($explode); // remove first empty instance

    if(count($explode) > 0){
      $actionName = $explode[count($explode)-1];
    }
    return self::parseActionName($actionName);
  }
  public static function parseActionName($actionName){
    return "action" . str_replace(" ", "", ucwords(str_replace("-"," ", $actionName)));
  }
}
