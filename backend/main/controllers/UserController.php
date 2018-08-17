<?php
namespace backend\main\controllers;

use aug\data\DataProvider;

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
          $user->validate(); $account->validate();
          if(!$user->hasErrors() && !$account->hasErrors()){
            $redirect = false;
            if($user->isNewRecord){
              $user->created_at = time();
              $user->updated_at = time();
              $redirect = true;
            }
            $user->save(false);
            if($account->isNewRecord){
              $account->user_id = $user->id;
            }
            $account->save(false);
            if($redirect){
              return $this->redirect("/user/{$user->id}");
            }
          }
        }
      }
    }
    return $this->renderFile("view", [
      "user" => $user,
      "account" => $account
    ]);
  }
  public function actionIndex(){

    $dataProvider = new DataProvider([
      "query" => User::find([
        "is_deleted" => 0
      ]),
      "pagination" => [
        "page" => isset($_GET["page"]) ? $_GET["page"] : 1,
        "pageSize" => isset($_GET["page-size"]) ? $_GET["page-size"] : 1
      ]
    ]);

    return $this->renderPartial("index", [
      "dataProvider" => $dataProvider
    ]);
  }
}
