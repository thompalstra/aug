<?php
namespace aug\widgets\desktop;
use aug\helpers\Html;
class Toolstrip extends \aug\base\Widget{

  protected $items = [];
  protected $attributes = [
    "class" => ["toolstrip", "toolstrip-default"]
  ];
  protected $itemAttributes = [
    "class" => []
  ];
  protected $_html = "";

  public function init($options = []){
    foreach($options as $k => $v){
      $this->$k = $v;
    }
  }
  protected function openToolStrip($attributes = []){
    return Html::openTag("ul", $attributes);
  }
  protected function createItems($items = []){
    $out = "";
    foreach($items as $item){
      $attributes = $this->itemAttributes;
      if(isset($item["attributes"])){
        $attributes = Html::mergeAttributes($attributes, $item["attributes"]);
      }

      $content = Html::tag("span", $item["label"]);
      if(isset($item["items"])){
        $attributes["class"][] = "has-children";
        $content .= Html::openTag("ul", []);
        $content .= $this->createItems($item["items"]);
        $content .= Html::closeTag("ul");
      }
      $out .= Html::tag("li", $content, $attributes);
    }
    return $out;
  }
  protected function closeToolStrip(){
    return Html::closeTag("ul");
  }
  public function toHtml(){
    $out = $this->openToolStrip($this->attributes);
    $out .= $this->createItems($this->items);
    $out .= $this->closeToolStrip();
    return $out;
  }
  public function run(){
    return $this->toHtml();
  }
}
