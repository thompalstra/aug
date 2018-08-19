<?php
namespace aug\widgets\desktop;
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
  public function openTaskbar($attributes = []){
    return Html::openTag("ul", $attributes);
  }
  public function createItems($items = []){
    $out = [];

    foreach($items as $item){
      if(empty($item)){
        continue;
      }

      $attributes = $this->itemAttributes;

      if(isset($item["items"])){
        $attributes["class"][] = "has-children";
      }

      $itemAttributes = [];
      if(isset($item["attributes"])){
        $itemAttributes = $item["attributes"];
      }


      $out[] = Html::openTag("li",$attributes);
      if(isset($item["url"])){
        $itemAttributes["href"] = $item["url"];
        $out[] = Html::tag("a", $itemAttributes, $item["label"]);
      } else {
        $out[] = Html::tag("span", $itemAttributes, $item["label"]);
      }
      if(isset($item["items"])){
        $out[] = Html::openTag("ul", []);
        $out[] = $this->createItems($item["items"]);
        $out[] = Html::closeTag("ul");
      }
      $out[] = Html::closeTag("li");
    }
    //
    return implode("", $out);
  }
  public function closeTaskbar(){
    return Html::closeTag("ul");
  }
  public function toHtml(){
    $html = $this->openTaskbar($this->attributes);
    $html .= $this->createItems($this->items);
    $html .= $this->closeTaskbar();
    return $html;
  }
  public function run(){
    return $this->toHtml();
  }
}
