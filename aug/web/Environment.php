<?php
namespace aug\web;
class Environment{
  public static function parseRequest($host){
    $environment = new self();
    if(isset(\Aug::$app->sites[$host])){
      $environment->name = \Aug::$app->sites[$host];
      $environment->relativePath = "frontend" . DIRECTORY_SEPARATOR . $environment->name . DIRECTORY_SEPARATOR;
      $environment->absolutePath = \Aug::$app->root . DIRECTORY_SEPARATOR . $environment->relativePath;
    } else {
      $environment->name = "main";
      $environment->relativePath = "frontend" . DIRECTORY_SEPARATOR . $environment->name . DIRECTORY_SEPARATOR;
      $environment->absolutePath = \Aug::$app->root . DIRECTORY_SEPARATOR . $environment->relativePath;
    }
    return $environment;
  }
}
