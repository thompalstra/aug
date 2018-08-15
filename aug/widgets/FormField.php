<?php
namespace aug\widgets;
use aug\helpers\Html;
class FormField extends \aug\base\Widget{

  protected $model;
  protected $attribute;
  protected $attributeIsArray = false;

  public function __construct($model, $attribute, $form){
    $this->model = $model;
    $this->attribute = $attribute;
    $this->form = $form;

    $this->inputName = $this->getInputName();
    $this->attributeValue = $this->getAttributeValue();
  }
  public function getRowStart($attributes = []){
    return Html::openTag("div", $attributes);
  }
  public function getLabel($attributes = []){
    $model = $this->model;
    $attribute = $this->attribute;
    return Html::tag("label", $attributes, $model::getAttributeLabel($attribute));
  }
  public function getErrors($attributes = []){
    $errors = "";
    $attributes = Html::mergeAttributes($this->form->controlOptions, $attributes);
    var_dump($this->form->controlOptions, $attributes); die;
    if($this->model->hasErrors()){;
      $errors = [];
      foreach($this->model->getErrors($this->attribute) as $msg){
        $errors[] =
         Html::openTag("span", []) . $msg . Html::closeTag("span");
      }
      $errors = Html::openTag("label", $attributes) . implode("", $errors) . Html::closeTag("label");
    }
    return $errors;
  }
  public function getRowEnd(){
    return Html::closeTag("div");
  }
  public function createLayout($control){
    $layout = $this->form->getLayout();
    $layout = str_replace("{rowStart}", $this->getRowStart($this->form->rowOptions), $layout);
    $layout = str_replace("{label}", $this->getLabel($this->form->labelOptions), $layout);
    $layout = str_replace("{controlRowStart}", $this->getControlRowStart($this->form->controlRowOptions), $layout);
    $layout = str_replace("{control}", $control, $layout);
    $layout = str_replace("{controlRowEnd}", $this->getControlRowEnd(), $layout);
    $layout = str_replace("{errors}", $this->getErrors($this->form->errorOptions), $layout);
    $layout = str_replace("{rowEnd}", $this->getRowEnd(), $layout);
    return $layout;
  }
  public function getControlRowStart($attributes = []){
    return Html::openTag("div", $attributes);
  }
  public function getControlRowEnd(){
    return Html::closeTag("div");
  }
  public function textInput($attributes = []){
    $attributes["type"] = "text";
    if(!isset($attributes["name"])){
      $attributes["name"] = $this->inputName;
    }
    if(!isset($attributes["value"])){
      $attributes["value"] = $this->attributeValue;
    }
    return $this->createLayout(Html::input($attributes));
  }
  public function passwordInput($attributes = []){
    $attributes["type"] = "password";
    if(!isset($attributes["name"])){
      $attributes["name"] = $this->inputName;
    }
    if(!isset($attributes["value"])){
      $attributes["value"] = $this->attributeValue;
    }
    return $this->createLayout(Html::input($attributes));
  }
  public function checkboxInput($attributes = []){
    $attributes["type"] = "checkbox";

    if(!isset($attributes["name"]))
      $attributes["name"] = $this->inputName;

    if($this->attributeValue == true)
      $attributes["checked"] = "";

    $hiddenAttributes = [
      "type" => "hidden",
      "value" => 0,
      "name" => $this->inputName
    ];
    return $this->createLayout(Html::input($hiddenAttributes).Html::input($attributes));
  }
  public function selectInput($options = [], $attributes = []){
    if(!isset($attributes["name"])){
      $attributes["name"] = $this->inputName;
    }
    return $this->createLayout(Html::select($options, $this->attributeValue, $attributes));
  }
  public function getInputId(){

  }
  public function getInputName(){
    $model = $this->model;
    $shortClassName = $model::getShortClassName();
    $attributeName = $this->attribute;
    $l = strlen($attributeName);
    if($attributeName[$l-1] == "]" && $attributeName[$l-2] == "["){
      $this->attributeIsArray = true;
      $attributeName = substr($attributeName, 0, $l-2);
      return "{$shortClassName}[$attributeName][]";
    }
    return "{$shortClassName}[$attributeName]";
  }
  public function getAttributeValue(){
    $attr = $this->attribute;
    $model = $this->model;
    return $model->$attr;
  }
}
