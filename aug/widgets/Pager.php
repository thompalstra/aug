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
    "class" => ["page-item"]
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
    if($endOffset == 0){
      $endOffset = 1;
    }


    $uri = $_SERVER["REQUEST_URI"];
    if(strpos($uri, "?")){
      $uri = substr($uri, 0, strpos($uri, "?"));
    }
    $params = $_GET;

    $items = [];

    if($this->first){
      $items[$this->firstText] = [
        "href" => Request::toUrl([$uri, ["page" => 1, "page-size" => $pageSize] + $params]),
        "class" => ($currentPage == 1) ? ["current"] : []
      ];
    }
    foreach(range($startOffset, $endOffset) as $pageNumber){
      $items[$pageNumber] = [
        "href" => Request::toUrl([$uri, ["page" => $pageNumber, "page-size" => $pageSize] + $params]),
        "class" => ($currentPage == $pageNumber) ? ["current"] : []
      ];
    }
    if($this->last){
      $items[$this->lastText] = [
        "href" => Request::toUrl([$uri, ["page" => ($lastPage), "page-size" => $pageSize] + $params]),
        "class" => ($currentPage == $lastPage) ? ["current"] : []
      ];
    }

    $out = [];
    $out[] = Html::openTag("ul", $this->attributes);
    foreach($items as $k => $v){
      $out[] = Html::tag("li", Html::tag("a", $k, $v), $this->itemAttributes);
    }
    if($this->pageSizes){
      $p = $params + ["page" => ($lastPage)];
      if(isset($p["page-size"])){
        unset($p["page-size"]);
      }
      $out[] = Html::tag("li", Html::select($this->pageSizes, $pageSize, [
        "name" => "page-size",
        "href" => Request::toUrl([$uri, $p]),
        "class" => "pagination pagesize",
        "onchange" => '
        var a = this.parentNode.appendChild(document.createElement("a"));
        a.href = this.getAttribute("href") + "&page-size=" + this.value;
        a.click();'
      ]), $this->itemAttributes);
    }
    $out[] = Html::closeTag("ul");


    return implode($out);
  }
}
