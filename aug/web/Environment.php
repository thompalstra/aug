<?php
namespace aug\web;
class Environment{
  public static function parseRequest($host){
    $environment = new self();

    $explode = explode(".", $host);
    if(count($explode) == 1){
      $host = $explode[0];
    } else if(count($explode) == 2){
      $host = $explode[0];
    } else if(count($explode) == 3){
      $host = $explode[1];
    }

    if(isset(\Aug::$app->sites[$host])){
      $environment->name = \Aug::$app->sites[$host];
      $environment->prefix = ($environment->name === "backend") ? "backend" : "frontend";
      $environment->relativePath = $environment->prefix . DIRECTORY_SEPARATOR . $environment->name . DIRECTORY_SEPARATOR;
      $environment->absolutePath = \Aug::$app->root . $environment->relativePath;
    } else {
      $environment->name = "main";
      $environment->prefix = ($environment->name === "backend") ? "backend" : "frontend";
      $environment->relativePath = $environment->prefix . DIRECTORY_SEPARATOR . $environment->name . DIRECTORY_SEPARATOR;
      $environment->absolutePath = \Aug::$app->root . $environment->relativePath;
    }
    return $environment;
  }
}
