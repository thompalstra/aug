<?php
namespace common\models;
use common\models\UserAccount;
use aug\security\Security;
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
    $account = UserAccount::find()
      ->where([
        ["user_id"=>$this->id]
      ])->one();
    if(empty($account)){
      return new UserAccount();
    }
    return $account;
  }
  public static function findById($id){
    return self::findOne([
      ["id"=>$id],
      ["is_deleted"=>0]
    ]);
  }
  public function asPasswordValidator($model, $attribute, $rule){
    $password = $model->$attribute;
    $password_hash = $model->password_hash;
    if(empty($password) && empty($password_hash)){
      $model->addError($attribute, "Password is required");
    } else if(!empty($password)) {
        $min = isset($rule["min"]) ? $rule["min"] : null;
        $max = isset($rule["max"]) ? $rule["max"] : null;

        if($min && strlen($password) < $min){
          $model->addError($attribute, "Password must be more than {$min} characters");
        }
        if($max && strlen($password) > $max){
          $model->addError($attribute, "Password must be less than {$min} characters");
        }
        if(!$model->hasErrors()){
          $model->password_hash = Security::passwordHash($password);
        }
    } else {
      $model->addError($attribute, "Password is required");
    }
  }
}
