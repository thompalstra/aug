<?php
namespace aug\base;
class Widget extends Base implements WidgetInterface{
  public static function widget($options = []){
    $className = get_called_class();
    $widget = new $className;
    call_user_func_array([$widget, "init"], [$options]);
    return call_user_func_array([$widget, "run"], [$options]);
  }
  public function init($options = []){}
  public function run(){}
}
