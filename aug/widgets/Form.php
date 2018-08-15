<?php
namespace aug\widgets;
use aug\helpers\Html;
class Form extends \aug\base\Widget{

  protected $attributes = [
    "class" => "form form-default"
  ];
  protected $layout = "{rowStart}{label}{controlRowStart}{control}{controlRowEnd}{errors}{rowEnd}";

  public $rowOptions = [
    "class" => "form-row"
  ];
  public $labelOptions = [
    "class" => "form-label"
  ];
  public $controlOptions = [
    "class" => "control"
  ];
  public $controlRowOptions = [
    "class" => "form-control"
  ];
  public $errorOptions = [
    "class" => "form-error"
  ];

  public function field($model, $attribute){
    return new FormField($model, $attribute, $this);
  }
  public function begin($options = []){
    foreach($options as $k => $v){
      if($k === "attributes"){
        $this->attributes = Html::mergeAttributes($this->attributes, $v);
      } else {
        $this->$k = $v;
      }
    }
    return Html::openTag("form", $this->attributes);
  }
  public function end(){
    return Html::closeTag("form");
  }
  public function getLayout(){
    return $this->layout;
  }
}
