<?php
namespace aug\web;
class Action implements ActionInterface{
  public function __construct($id, $params){
    $this->id = $id;
    $this->name = self::parseActionName($this->id);
    $this->params = $params;
  }
  public static function parseRequest($request){
    $ds = \Aug::$app->ds;

    $default = \Aug::$app->web["default"];
    $explode = explode("/", $default);
    array_shift($explode);
    $id = $explode[0];

    $explode = explode("/", $request[0]);
    array_shift($explode); // remove first empty instance

    if(count($explode) > 0){
      $id = $explode[count($explode)-1];
    }
    return new self($id, $request[1]);
  }
  public static function parseActionName($actionName){
    return "action" . str_replace(" ", "", ucwords(str_replace("-"," ", $actionName)));
  }
}
