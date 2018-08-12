<?php
namespace aug\security;
use aug\models\Role;
class Identity extends \aug\base\Model implements IdentityInterface{

  public $isGuest = true;

  public function login($user){
    $user->isGuest = false;
    $_SESSION["identity"] = [];
    foreach($user as $k => $v){
      $_SESSION["identity"][$k] = $v;
    }
    \Aug::$app->user = $user;
  }
  public function logout(){
    $className = get_called_class();
    $user = new $className();
    $_SESSION["identity"] = [];
    foreach($user as $k => $v){
      $_SESSION["identity"][$k] = $v;
    }
  }
  public function can($role){
    return Role::find()
      ->leftJoin("user_role", ['role.id' => 'user_role.role_id'])
      ->where([
        ["user_role.user_id" => $this->id],
        ["user_role.is_enabled" => 1],
        ["role.name" => $role]
      ])->exists();
  }
  public function getRoles(){
    return Role::find()
      ->leftJoin("user_role", ['role.id' => 'user_role.role_id'])
      ->where([
        ["user_role.user_id" => $this->id],
        ["user_role.is_enabled" => 1]
      ])->all();
  }
  public function restore(){
    $className = get_called_class();
    \Aug::$app->user = new $className();
    if(!isset($_SESSION["identity"])){
      foreach(\Aug::$app->user as $k => $v){
        $_SESSION["identity"][$k] = $v;
      }
    } else {
      foreach($_SESSION["identity"] as $k => $v){
        \Aug::$app->user->$k = $v;
      }
    }
  }
}
