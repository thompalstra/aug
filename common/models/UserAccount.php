<?php
namespace common\models;
class UserAccount extends \aug\db\Record{
  public static function rules(){
    return [
      [["first_name", "last_name", "nationality"], "required"],
      [["first_name", "last_name"], "string", "min"=>2, "max"=>255, "skipOnEmpty"=>true],
    ];
  }
  public static function tableName(){
    return "user_account";
  }
  public static function findbyUserId($userId){
    return UserAccount::find()
      ->where([
        ["user_id"=>$userId]
      ])->one();
  }
}
