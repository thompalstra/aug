<?php
namespace aug\helpers;
class File{
  public static function path($path = ""){
    return str_replace(["\\", "/"], DIRECTORY_SEPARATOR, $path);
  }
}
