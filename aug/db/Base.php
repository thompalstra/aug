<?php
namespace aug\db;
class Base{
  public static function className(){
    return get_called_class();
  }
  public static function getShortClassName(){
    $explode = explode("\\",get_called_class());
    return $explode[count($explode)-1];
  }
}
