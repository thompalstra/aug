<?php
namespace aug\helpers;
class ClassHelper{
  public static function camelCase($path = ""){
    return str_replace(" ", "", ucwords(str_replace("-"," ", $path)));
  }
  public static function namespace($path = ""){
    return str_replace("/", "\\", $path);
  }
}
