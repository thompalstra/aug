<?php
namespace common\models;
class User extends \aug\security\Identity{

  protected $password;

  public static function rules(){
    return [
      [["username"], "required"],
      [["username"], "string", "min"=>2, "max"=>255, "skipOnEmpty"=>true],
      [["password"], "string", "min"=>6, "max"=>32, "skipOnEmpty"=>true],
      [["is_enabled", "is_deleted"], "boolean"],
    ];
  }

  public static function tableName(){
    return "user";
  }
  public function getAccount(){
    return UserAccount::find()
      ->where([
        ["user_id"=>$this->id]
      ])->one();
  }
  public static function findById($id){
    return self::findOne([
      ["id"=>$id],
      ["is_deleted"=>0]
    ]);
  }
}
