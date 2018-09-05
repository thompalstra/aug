<?php
namespace common\models;
class Page extends \aug\db\Record{
  use \aug\components\traits\SoftDelete;
  public static function tableName(){
    return "page";
  }
  public static function rules(){
    return [
      [["name"], "required"],
      [["name"], "string", "min"=>2, "max"=>255, "skipOnEmpty"=>true],
      [["is_enabled", "is_deleted"], "boolean"]
    ];
  }
}
