<?php
namespace aug\widgets\desktop;
use aug\helpers\Html;
class Workspace extends \aug\base\Widget{
  protected $attributes = [
    "class" => ["desktop-workspace"]
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
      $out[] = Html::tag("a", [
        "href"=>$item["url"],
        "class"=>["shortcut", "open-win"],
      ], $item["icon"] . Html::tag("label", [], $item["label"]));
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
