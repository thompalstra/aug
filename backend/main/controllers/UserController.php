<?php
namespace backend\main\controllers;
use aug\data\DataProvider;
use common\models\User;
use common\models\UserAccount;
use aug\models\Role;
use aug\models\UserRole;

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
  public function actionView($id = null){
    $user = $this->getModel($id);
    $account = $user->account;
    if($_POST){
      if(($user->isNewRecord && \Aug::$app->user->can("userCreate")) || \Aug::$app->user->can("userUpdate")){
        if($user->load($_POST) && $account->load($_POST)){
          if($user->validate() && $account->validate()){
            $redirect = false;
            if($user->isNewRecord){
              $user->created_at = time();
              $user->updated_at = time();
              $redirect = true;
            }
            if($user->save()){
              $account->user_id = $user->id;
              if($account->save()){
                \Aug::$app->setFlash("saved", "saved");
                if($redirect){
                  return $this->redirect("/user/{$user->id}");
                }
              }
            }
          } else if($user->hasErrors() || $account->hasErrors()){
            \Aug::$app->setFlash("saved", "error");
          }

        }
      }
    }

    return $this->renderPartial("view", [
      "user" => $user,
      "account" => $account
    ]);
  }
  public function actionIndex(){
    $dataProvider = new DataProvider([
      "query" => $this->getModels(),
      "pagination" => $this->getPagination()
    ]);
    return $this->renderPartial("index", [
      "dataProvider" => $dataProvider
    ]);
  }
  protected function getModel($id = null){
    if(!empty($id)){
      return User::find()->where([
        ["id"=>$id]
        ])->one();
    }
    return new User();
  }
  protected function getModels(){
    return User::find()->where([
      ["is_deleted"=>0]
    ]);
  }
  protected function getPagination(){
    return [
      "page" => isset($_GET["page"]) ? $_GET["page"] : 1,
      "pageSize" => isset($_GET["page-size"]) ? $_GET["page-size"] : 20
    ];
  }
}
