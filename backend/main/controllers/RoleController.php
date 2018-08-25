<?php
namespace backend\main\controllers;
use aug\data\DataProvider;
use aug\models\Role;
use aug\models\UserRole;
class RoleController extends \aug\web\Controller{
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
      if($model->load($_POST) && $model->save()){
        \Aug::$app->setFlash("saved", "saved");
      } else {
        \Aug::$app->setFlash("saved", "error");
      }
    }
    return $this->renderPartial("view", [
      "model" => $model
    ]);
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
      "pageSize" => isset($_GET["page-size"]) ? $_GET["page-size"] : 20
    ];
  }
}
