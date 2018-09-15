<?php
namespace backend\main\models;
use common\models\User;
use aug\security\Security;
class LoginForm extends \aug\base\Model{

  public $username;
  public $password;

  public function init($options = []){}
  public function run(){}

  public function login(){
    $user = User::find()
      ->where([
        ["username" => $this->username]
      ])->one();

    if($user && $user->can("admin") && Security::passwordVerify($this->password, $user->password_hash)){
      \Aug::$app->user->login($user);
      return true;
    }
    $this->addError("password", "Incorrect credentials");
    return false;
  }
}
