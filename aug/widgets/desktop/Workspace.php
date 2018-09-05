<?php
namespace aug\widgets\desktop;
use aug\helpers\Html;
class Workspace extends \aug\base\Widget{
  protected $attributes = [
    "class" => ["desktop-workspace"]
  ];
  protected $itemAttributes = [
    "class"=> ["shortcut", "open-win"],
  ];
  protected $items = [];
  public function init($options = []){
    foreach($options as $k => $v){
      $this->$k = $v;
    }
  }
  public function openItems($attributes = []){
    return Html::openTag("section", $attributes);
  }
  public function createItems($items = []){
    $out = [];
    foreach($items as $item){
      $attributes = $this->itemAttributes;
      if(isset($item["attributes"])){
        $attributes = $item["attributes"];
      }

      if(isset($item["content"])){
        $out[] = Html::tag("span", $item["content"], $attributes);
      } else if(isset($item["url"])){
        $attributes["href"] = $item["url"];
        $out[] = Html::tag("a", $item["icon"] . Html::tag("label", $item["label"]), $attributes);
      } else {
        $out[] = Html::tag("span", $item["icon"] . Html::tag("label", $item["label"]), $attributes);
      }
    }
    return implode($out);
  }
  public function closeItems(){
    return Html::closeTag("section");
  }
  public function toHtml(){
    $html = $this->openItems($this->attributes);
    $html .= $this->createItems($this->items);
    $html .= $this->closeItems();
    return $html;
  }
  public function run(){
    return $this->toHtml();
  }
}
