<?php
namespace aug\widgets;
use aug\helpers\Html;
class Nav extends \aug\base\Widget{

  protected $items = [];
  protected $attributes = [];
  protected $_html = "";

  public function init($options = []){
    foreach($options as $k => $v){
      $this->$k = $v;
    }
  }
  public function openNav($options = []){
    // return Html::openTag("nav", $options);
  }
  public function createItems($items = [], $attributes = []){
    $out = [];

    $out[] = Html::openTag("ul", $attributes);
    foreach($items as $item){
      if(empty($item)){
        continue;
      }
      $attributes = isset($item["attributes"]) ? $item["attributes"] : [];

      if(isset($item["items"])){
        if(isset($attributes["class"])){
          $attribute["class"] .= " has-children";
        } else {
          $attribute["class"] = "has-children";
        }
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
  public function closeNav(){
    return Html::closeTag("nav");
  }
  public function toHtml(){
    $html = $this->createItems($this->items, $this->attributes);
    return $html;
  }
  public function run(){
    return $this->toHtml();
  }
}
