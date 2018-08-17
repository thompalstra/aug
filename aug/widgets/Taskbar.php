<?php
namespace aug\widgets;
use aug\helpers\Html;
class Taskbar extends \aug\base\Widget{

  protected $items = [];
  protected $attributes = [
    "class" => ["taskbar", "taskbar-default"]
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
  public function createItems($items = [], $attributes = []){
    $out = [];

    $out[] = Html::openTag("ul", $attributes);
    foreach($items as $item){
      if(empty($item)){
        continue;
      }

      if(isset($item["attributes"])){
        $attributes = Html::mergeAttributes($this->itemAttributes, $item["attributes"]);
      } else {
        $attributes = $this->itemAttributes;
      }

      if(isset($item["items"])){
        $attributes["class"][] = "has-children";
      }

      $out[] = Html::openTag("li",$attributes);
      if(isset($item["url"])){
        $out[] = Html::tag("a", ["href"=>$item["url"]], $item["label"]);
      } else {
        $out[] = Html::tag("span", [], $item["label"]);
      }
      if(isset($item["items"])){
        $out[] = $this->createItems($item["items"]);
      }
      $out[] = Html::closeTag("li");
    }
    $out[] = Html::closeTag("ul");
    return implode("", $out);
  }
  public function toHtml(){
    $html = $this->createItems($this->items, $this->attributes);
    return $html;
  }
  public function run(){
    return $this->toHtml();
  }
}
