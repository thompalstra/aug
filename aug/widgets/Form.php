<?php
namespace aug\widgets;
use aug\helpers\HtmlHelper;
class Form extends \aug\base\Widget{

  protected $_attributes = [];
  protected $_layout = "{rowStart}{label}{control}{errors}{rowEnd}";

  public $rowOptions = [
    "class" => "form-row"
  ];
  public $labelOptions = [
    "class" => "form-label"
  ];
  public $controlOptions = [
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
      $this->$k = $v;
    }
    return HtmlHelper::openTag("form", $this->attributes);
  }
  public function end(){
    return HtmlHelper::closeTag("form");
  }
  public function getLayout(){
    return $this->_layout;
  }
}
