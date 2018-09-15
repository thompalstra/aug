<?php
namespace backend\main\controllers;
use aug\data\DataProvider;
use common\models\User;
use common\models\UserAccount;
use aug\models\Role;
use aug\models\UserRole;

class UsersController extends \aug\web\Controller{
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
    if($user){
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
                    return $this->redirect("/users/{$user->id}");
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
    } else {
      return $this->renderPartial("/views/site/errors/no-such-model");
    }
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
  public function actionDelete($id){
    $ids = explode(",", $id);
    foreach($ids as $id){
      $model = $this->getModel($id);
      if($model){
        $model->delete();
      }
    }
    return $this->redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "/users");
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
      "pageSize" => isset($_GET["page-size"]) ? $_GET["page-size"] : 2
    ];
  }
}
