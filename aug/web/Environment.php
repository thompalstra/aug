<?php
namespace aug\web;
class Environment{
  public static function parseRequest($host){
    $environment = new self();

    $host = str_replace("www.", "", $host);

    $domain = null;
    $subdomain = "frontend";

    $explode = explode(".", $host);
    if(count($explode) == 1){
      $domain = $explode[0];
    } else if(count($explode) == 2){
      $domain = $explode[0];
    } else if(count($explode) == 3){
      $subdomain = $explode[0];
      $domain = $explode[1];
    }

    if(isset(\Aug::$app->sites[$host])){
      $environment->name = \Aug::$app->sites[$host];
      $environment->prefix = $subdomain;
      $environment->relativePath = $environment->prefix . DIRECTORY_SEPARATOR . $environment->name . DIRECTORY_SEPARATOR;
      $environment->absolutePath = \Aug::$app->root . $environment->relativePath;
    } else {
      $environment->name = "main";
      $environment->prefix = $subdomain;
      $environment->relativePath = $environment->prefix . DIRECTORY_SEPARATOR . $environment->name . DIRECTORY_SEPARATOR;
      $environment->absolutePath = \Aug::$app->root . $environment->relativePath;
    }
    return $environment;
  }
}
