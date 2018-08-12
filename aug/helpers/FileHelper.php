<?php
namespace aug\helpers;
class FileHelper{
  public static function path($path = ""){
    return str_replace(["\\", "/"], DIRECTORY_SEPARATOR, $path);
  }
}
