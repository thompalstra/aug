<?php
namespace aug\widgets;
use aug\helpers\HtmlHelper;
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
    // return HtmlHelper::openTag("nav", $options);
  }
  public function createItems($items = [], $attributes = []){
    $out = [];

    $out[] = HtmlHelper::openTag("ul", $attributes);
    foreach($items as $item){
      $out[] = HtmlHelper::openTag("li");

      if(isset($item["url"])){
        $out[] = HtmlHelper::tag("a", ["href"=>$item["url"]], $item["label"]);
      } else {
        $out[] = HtmlHelper::tag("span", [], $item["label"]);
      }

      if(isset($item["items"])){
        $out[] = $this->createItems($item["items"]);
      }

      $out[] = HtmlHelper::closeTag("li");
    }
    $out[] = HtmlHelper::closeTag("ul");
    return implode("", $out);
  }
  public function closeNav(){
    return HtmlHelper::closeTag("nav");
  }
  public function toHtml(){
    // $html = $this->openNav($this->attributes);
    $html = $this->createItems($this->items, $this->attributes);
    // $html .= $this->closeNav();
    return $html;
  }
  public function run(){
    return $this->toHtml();
  }
}
