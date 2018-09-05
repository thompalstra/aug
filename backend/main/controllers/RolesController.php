<?php
namespace backend\main\controllers;
use aug\data\DataProvider;
use aug\models\Role;
use aug\models\UserRole;
class RolesController extends \aug\web\Controller{
  public function rules(){
    return [
      "index" => [
        "allow" => !\Aug::$app->user->isGuest && \Aug::$app->user->can("roleIndex"),
        "onDeny" => function($rule){
          $this->redirect("/");
        }
      ],
      "view" => [
        "allow" => !\Aug::$app->user->isGuest && \Aug::$app->user->can("roleView"),
        "onDeny" => function($rule){
          $this->redirect("/");
        }
      ],
    ];
  }
  public function actionIndex(){
    return $this->renderPartial("index", [
      "dataProvider" => new DataProvider([
        "query" => $this->getModels(),
        "pagination" => $this->getPagination()
      ])
    ]);
  }
  public function actionView($id = null){
    $model = $this->getModel($id);
    if($_POST){
      if(($model->isNewRecord && \Aug::$app->user->can("roleCreate")) || \Aug::$app->user->can("roleUpdate")){
        if($model->load($_POST) && $model->save()){
          \Aug::$app->setFlash("saved", "saved");
        } else {
          \Aug::$app->setFlash("saved", "error");
        }
      }
    }
    return $this->renderPartial("view", [
      "model" => $model
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
    return $this->redirect("/roles");
  }
  protected function getModel($id = null){
    if(!empty($id)){
      return Role::find()->where([
        ["id"=>$id]
        ])->one();
    }
    return new Role();
  }
  protected function getModels(){
    return Role::find()->where([
      ["is_deleted"=>0]
    ]);
  }
  protected function getPagination(){
    return [
      "page" => isset($_GET["page"]) ? $_GET["page"] : 1,
      "pageSize" => isset($_GET["page-size"]) ? $_GET["page-size"] : 10
    ];
  }
}
