<?php
namespace common\models;
class Site extends \aug\db\Record{
  use \aug\components\traits\SoftDelete;
  public static function tableName(){
    return "site";
  }
}
