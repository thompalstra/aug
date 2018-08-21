<?php
namespace aug\widgets;
use aug\helpers\Html;
use aug\web\Request;
class Pager extends \aug\base\Widget{

  protected $first = true;
  protected $firstText = "<i class='material-icons'>first_page</i>";
  protected $next = true;
  protected $nextText = "<i class='material-icons'>navigate_next</i>";
  protected $offset = 2;
  protected $previous = true;
  protected $previousText = "<i class='material-icons'>navigate_before</i>";
  protected $last = true;
  protected $lastText = "<i class='material-icons'>last_page</i>";
  protected $attributes = [
    "class" => "pager pager-default"
  ];
  protected $itemAttributes = [
    "class" => ["btn btn-default"]
  ];
  protected $pageSizes = [
    1 => 1,
    2 => 2,
    10 => 10,
    20 => 20
  ];

  public function init($options = []){
    foreach($options as $k => $v){
      $this->$k = $v;
    }
  }
  public function run(){
    return $this->toHtml();
  }
  public function toHtml(){
    $totalCount = $this->dataProvider->getTotalCount();
    $startPage = $this->dataProvider->getPage();

    $startOffset = $startPage - $this->offset;
    if($startOffset < 1){
      $startOffset = 1;
    }

    $currentPage = $this->dataProvider->getPage();
    $pageSize = $this->dataProvider->getPageSize();
    $lastPage = ceil($totalCount / $pageSize);
    $endOffset = $currentPage + $this->offset;
    if($endOffset > $totalCount / $pageSize){
      $endOffset = ceil($totalCount / $pageSize);
    }
    $out = [];

    $out[] = Html::openTag("form", [
      "method"=>"GET",
      "action" => $_SERVER["REQUEST_URI"]
    ]);

    $out[] = Html::openTag("div", $this->attributes);

    $uri = $_SERVER["REQUEST_URI"];
    if(strpos($uri, "?")){
      $uri = substr($uri, 0, strpos($uri, "?"));
    }

    if($this->first){
      $params = $_GET;
      $params["page"] = 1;
      $params["page-size"] = $pageSize;
      $attributes = $this->itemAttributes;
      if($currentPage == 1){
        $attributes["class"][] = "active";
      }

      $attributes["class"][] = "first";
      $attributes["name"] = "page";
      $attributes["value"] = 1;
      $out[] = Html::tag("button", $attributes, $this->firstText);
    }
    if($this->next && $startPage > $startOffset){
      $params = $_GET;
      $attributes = $this->itemAttributes;
      if($currentPage == $currentPage - 1){
        $attributes["class"][] = "active";
      }
      $attributes["class"][] = "prev";
      $attributes["name"] = "page";
      $attributes["value"] = $currentPage - 1;
      $out[] = Html::tag("button", $attributes, $this->previousText);
    }
    foreach(range($startOffset, $endOffset) as $pageNumber){
      $params = $_GET;

      $attributes = $this->itemAttributes;
      if($currentPage == $pageNumber){
        $attributes["class"][] = "active";
      }
      $attributes["class"][] = "page";
      $attributes["name"] = "page";
      $attributes["value"] = $pageNumber;
      $out[] = Html::tag("button", $attributes, $pageNumber);
    }
    if($this->previous && $currentPage < $lastPage){
      $params = $_GET;

      $attributes = $this->itemAttributes;
      if($currentPage == $currentPage + 1){
        $lastPage["class"][] = "active";
      }

      $attributes["class"][] = "next";
      $attributes["name"] = "page";
      $attributes["value"] = $currentPage + 1;
      $out[] = Html::tag("button", $attributes, $this->nextText);
    }
    if($this->last){
      $params = $_GET;

      $attributes = $this->itemAttributes;
      if($currentPage == $lastPage){
        $attributes["class"][] = "active";
      }

      $attributes["class"][] = "last";
      $attributes["name"] = "page";
      $attributes["value"] = $lastPage;
      $out[] = Html::tag("button", $attributes, $this->lastText);
    }
    if($this->pageSizes){
      $out[] = Html::select($this->pageSizes, $pageSize, [
        "name" => "page-size",
        "class" => "pagination pagesize",
        // "onchange" => "this.form.dispatchEvent(new CustomEvent('submit'),{cancelable:true,bubbles: true})"
      ]);
    }
    $out[] = Html::closeTag("div");
    $out[] = Html::closeTag("form");
    return implode($out);
  }
}
