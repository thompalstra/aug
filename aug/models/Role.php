<?php
namespace aug\models;
class Role extends \aug\db\Record{
  public static function tableName(){
    return "role";
  }
  public static function getList(){
    $out = [];
    foreach(self::find()->where([["is_deleted"=>0]])->all() as $role){
      $out[$role->id] = $role->name;
    }
    return $out;
  }
}
