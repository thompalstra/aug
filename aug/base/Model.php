<?php
namespace aug\base;
class Model extends \aug\core\Record{
  public function __construct($options = [], $isNewRecord = true){
    $this->isNewRecord = $isNewRecord;
  }
  public static function getClassName(){
    return get_called_class();
  }
  public static function getShortClassName(){
    $split = explode("\\",self::getClassName());
    return $split[count($split)-1];
  }
  public function load($data = []){

  }
}
