<?php
namespace aug\base;
class Base{
  public function __get($name){
    $fn = "get" . str_replace(" ", "",ucwords(str_replace("_", " ",$name)));
    if(method_exists($this, $fn)){
        return call_user_func_array([$this, $fn], []);
    }
    return $this->$name;
  }
  public static function className(){
    return get_called_class();
  }
  public static function getShortClassName(){
    $explode = explode("\\",get_called_class());
    return $explode[count($explode)-1];
  }
  public function load($data = []){
    if(isset($data[self::getShortClassName()])){
      foreach($data[self::getShortClassName()] as $k => $v){
        $this->$k = $v;
      }
      return true;
    }
    return false;
  }
  public function getAttributeLabel($attribute){
    if(method_exists(get_called_class(), "labels")){
      $labels = call_user_func_array([get_called_class(), "labels"], []);
      if(isset($labels[$attribute])){
        return $labels[$attribute];
      }
    }
    $attribute = str_replace("_", " ", $attribute);
    $attribute = ucwords($attribute);
    return $attribute;
  }
}
