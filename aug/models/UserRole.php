<?php
namespace aug\models;
class UserRole extends \aug\db\Record{
  use \aug\components\traits\SoftDelete;
  public static function tableName(){
    return "user_role";
  }
}
