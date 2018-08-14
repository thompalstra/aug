<?php
namespace common\models;
use common\models\UserAccount;
class User extends \aug\security\Identity{

  protected $password;

  public static function rules(){
    return [
      [["username"], "required"],
      [["username"], "string", "min"=>2, "max"=>255, "skipOnEmpty"=>true],
      [["is_enabled", "is_deleted"], "boolean"],
      [["password"], "passwordValidator", "min"=>4,"max"=>32]
    ];
  }

  public static function tableName(){
    return "user";
  }
  public static function labels(){
    return [
      "id" => "#",
      "account.first_name" => "Lastname",
      "account.last_name" => "Firstname",
      "account.nationality" => "Nationality",
      "is_enabled" => "Enabled"
    ];

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
  public function asPasswordValidator($model, $attribute, $rule){
    $value = $model->$attribute;
    $password = $model->password;
    if(empty($value)){
      if(empty($password)){
        $model->addError($attribute, "Password is required.");
      } else {
        $min = isset($rule["min"]) ? $rule["min"] : null;
        $max = isset($rule["max"]) ? $rule["max"] : null;

        if($min && strlen($password) < $min){
          $model->addError($attribute, "Password must be more than {$min} characters");
        }
        if($max && strlen($password) > $max){
          $model->addError($attribute, "Password must be less than {$min} characters");
        }

        if(empty($model->getErrors($attribute))){
          $model->$attribute = Security::passwordHash($password);
        }
      }
    }
  }


}
