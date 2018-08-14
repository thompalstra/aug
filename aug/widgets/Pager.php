<?php
namespace aug\widgets;
use aug\helpers\HtmlHelper;
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

    $out[] = HtmlHelper::openTag("ul", $this->attributes);

    $uri = $_SERVER["REQUEST_URI"];
    if(strpos($uri, "?")){
      $uri = substr($uri, 0, strpos($uri, "?"));
    }

    if($this->first){
      $params = $_GET;
      $params["page"] = 1;
      $params["page-size"] = $pageSize;
      $liParams = [];
      if($currentPage == 1){
        $liParams["class"] = "active";
      }
      $out[] = HtmlHelper::tag("li", $liParams, HtmlHelper::tag("a", ["href"=> Request::toUrl($uri, $params) ], $this->firstText));
    }
    if($this->next && $startPage > $startOffset){
      $params = $_GET;
      $params["page"] = $currentPage - 1;
      $params["page-size"] = $pageSize;
      $liParams = [];
      if($currentPage == $currentPage - 1){
        $liParams["class"] = "active";
      }
      $out[] = HtmlHelper::tag("li", $liParams, HtmlHelper::tag("a", ["href"=> Request::toUrl($uri, $params) ], $this->previousText));
    }
    foreach(range($startOffset, $endOffset) as $pageNumber){
      $params = $_GET;
      $params["page"] = $pageNumber;
      $params["page-size"] = $pageSize;
      $liParams = [];
      if($currentPage == $pageNumber){
        $liParams["class"] = "active";
      }
      $out[] = HtmlHelper::tag("li", $liParams, HtmlHelper::tag("a", ["href"=> Request::toUrl($uri, $params)], $pageNumber));
    }
    if($this->previous && $currentPage < $lastPage){
      $params = $_GET;
      $params["page"] = $currentPage + 1;
      $params["page-size"] = $pageSize;
      $liParams = [];
      if($currentPage == $currentPage + 1){
        $lastPage["class"] = "active";
      }
      $out[] = HtmlHelper::tag("li", $liParams, HtmlHelper::tag("a", ["href"=> Request::toUrl($uri, $params) ], $this->nextText));
    }
    if($this->last){
      $params = $_GET;
      $params["page"] = $lastPage;
      $params["page-size"] = $pageSize;
      $liParams = [];
      if($currentPage == $lastPage){
        $liParams["class"] = "active";
      }
      $out[] = HtmlHelper::tag("li", $liParams, HtmlHelper::tag("a", ["href"=> Request::toUrl($uri, $params) ], $this->lastText));
    }

    if($this->pageSizes){
      $out[] = HtmlHelper::select($this->pageSizes, $currentPage, [
        "class" => "pagination pagesize"
      ]);
    }

    $out[] = HtmlHelper::closeTag("ul");


    return implode($out);
  }
}
