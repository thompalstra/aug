<?php
namespace aug\widgets;
use aug\helpers\HtmlHelper;
class Table extends \aug\base\Widget{

  protected $attributes = [
    "class" => "table table-default"
  ];
  protected $headAttributes = [
    "class" => ""
  ];
  protected $rowAttributes = [
    "class" => "table row-default"
  ];
  protected $cellAttributes = [
    "class" => "table cell-default"
  ];
  protected $columns = [];
  protected $className;

  public function init($options = []){
    foreach($options as $k => $v){
      $this->$k = $v;
    }

    if(empty($this->columns)){
      throw new \Exception("Table columns cannot be empty.");
    }
    $this->className = $this->dataProvider->query->getClassName();
  }
  public function run(){
    return $this->toHtml();
  }
  public function toHtml(){
    $html = $this->openTable($this->attributes);
    $html .= $this->head($this->headAttributes);
    $html .= $this->rows($this->rowAttributes);
    $html .= $this->closeTable();
    return $html;
  }
  public function openTable($attributes = []){
    return HtmlHelper::openTag("table", $attributes);
  }
  public function head($attributes = []){
    $head = HtmlHelper::openTag("thead", $attributes);
    $head .= HtmlHelper::openTag("tr");
    foreach($this->columns as $column){
      $attribute = is_string($column) ? $column : $column["attribute"];
      $className = $this->className;
      $label = $className::getAttributeLabel($attribute);
      $head .= HtmlHelper::tag("td", [], $label);
    }
    $head .= HtmlHelper::closeTag("tr");
    $head .= HtmlHelper::closeTag("thead");

    return $head;
  }
  public function rows($attributes = []){
    $head = HtmlHelper::openTag("tbody", $attributes);
    foreach($this->dataProvider->getModels() as $model){
      $head .= HtmlHelper::openTag("tr");
      foreach($this->columns as $column){
        $cellAttributes = $this->cellAttributes;
        $attribute = is_string($column) ? $column : $column["attribute"];
        if(isset($column["value"])){
          $fn = $column["value"];
          $value = $fn($model);
        } else {
          $value = $model->$attribute;
        }
        $head .= HtmlHelper::tag("td", $cellAttributes, $value);
      }
      $head .= HtmlHelper::closeTag("tr");
    }
    $head .= HtmlHelper::closeTag("thead");

    return $head;
  }
  public function closeTable(){
    return HtmlHelper::closeTag("table");
  }
}
