<?php
namespace aug\helpers;
class ClassHelper{
  public static function toCamelCase($path = ""){
    return str_replace(" ", "", ucwords(str_replace("-"," ", $path)));
  }
  public static function toNamespace($path = ""){
    return str_replace("/", "\\", $path);
  }
}
