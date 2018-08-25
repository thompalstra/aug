<?php
namespace aug\widgets;
use aug\helpers\Html;
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
    return Html::openTag("table", $attributes);
  }
  public function head($attributes = []){
    $head = Html::openTag("thead", $attributes);
    $head .= Html::openTag("tr");
    $className = $this->className;
    foreach($this->columns as $column){
      if(is_string($column)){
        $label = $className::getAttributeLabel($column);
      }
      else if(is_array($column) && isset($column["attribute"])){
        $label = $className::getAttributeLabel($column["attribute"]);
      }
      else if(is_array($column) && isset($column["label"])){
        $label = $column["label"];
      }

      $attributes = [];
      if(is_array($column) && isset($column["attributes"])){
        $attributes = $column["attributes"];
      }

      $head .= Html::tag("td", $label, $attributes);
    }
    $head .= Html::closeTag("tr");
    $head .= Html::closeTag("thead");

    return $head;
  }
  public function rows($attributes = []){
    $head = Html::openTag("tbody", $attributes);
    foreach($this->dataProvider->getModels() as $model){
      $head .= Html::openTag("tr");
      foreach($this->columns as $column){
        if(is_string($column)){
          $value = $model->$column;
        } else if(is_array($column) && isset($column["value"])){
          $fn = $column["value"];
          $value = $fn($model);
        } else if(is_array($column) && isset($column["attribute"])){
          $attribute = $column["attribute"];
          $value = $model->$attribute;
        }

        $attributes = [];
        if(is_array($column) && isset($column["attributes"])){
          $attributes = $column["attributes"];
        }

        $attributes = Html::mergeAttributes($this->cellAttributes, $attributes);

        $head .= Html::tag("td", $value, $attributes);
      }
      $head .= Html::closeTag("tr");
    }
    $head .= Html::closeTag("thead");

    return $head;
  }
  public function closeTable(){
    return Html::closeTag("table");
  }
}
