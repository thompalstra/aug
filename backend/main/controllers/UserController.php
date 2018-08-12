<?php
namespace backend\main\controllers;
use common\models\User;
use common\models\UserAccount;
class UserController extends \aug\web\Controller{
  public function rules(){
    return [
      "index" => [
        "allow" => !\Aug::$app->user->isGuest && \Aug::$app->user->can("userIndex"),
        "onDeny" => function($rule){
          $this->redirect("/");
        }
      ],
      "view" => [
        "allow" => !\Aug::$app->user->isGuest && \Aug::$app->user->can("userView"),
        "onDeny" => function($rule){
          $this->redirect("/");
        }
      ],
    ];
  }
  public function actionView($userId = null){
    $user = !empty($userId) && $userId !== "new" ? User::findById($userId) : new User();
    $account = !empty($userId) && $userId !== "new" ? $user->account : new UserAccount();
    if($_POST){
      if(($user->isNewRecord && \Aug::$app->user->can("userCreate")) || \Aug::$app->user->can("userUpdate")){
        if($user->load($_POST) && $account->load($_POST)){
          if($user->validate() && $account->validate()){
            if($user->isNewRecord){
              
              $user->created_at = time();
              $user->updated_at = time();
              $user = $user->save(false);
              $account->user_id = $user->id;
              $account->save();

              return $this->redirect("/user/{$user->id}");
            } else  {
              $user->save(false);
              $account->save(false);
            }
          }
        }
      }
    }
    return $this->render("view", [
      "user" => $user,
      "account" => $account
    ]);
  }
  public function actionIndex(){
    return $this->render("index", [
      "query" => User::find([
        ["is_deleted"=>0]
      ])
    ]);
  }
}
