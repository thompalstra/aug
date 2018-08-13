<?php
namespace aug\web;
use aug\helpers\FileHelper;
use aug\helpers\ClassHelper;
class Action implements ActionInterface{
  public function __construct($id, $params){
    $this->id = $id;
    $this->name = "action" . ClassHelper::toCamelCase($this->id);
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
}
