<?php
namespace backend\main\controllers;
use aug\data\DataProvider;
use common\models\Site;
class SitesController extends \aug\web\Controller{
  public function rules(){
    return [
      "index" => [
        "allow" => !\Aug::$app->user->isGuest && \Aug::$app->user->can("siteIndex"),
        "onDeny" => function($rule){
          $this->redirect("/");
        }
      ],
      "view" => [
        "allow" => !\Aug::$app->user->isGuest && \Aug::$app->user->can("siteView"),
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
      if(($model->isNewRecord && \Aug::$app->user->can("siteCreate")) || \Aug::$app->user->can("siteUpdate")){
        if($model->load($_POST) && $model->validate()){
          $redirect = false;
          if($model->isNewRecord){
            $model->created_at = time();
            $model->updated_at = time();
            $redirect = true;
          } else {
            $model->updated_at = time();
          }
          if($model->save()){
            \Aug::$app->setFlash("saved", "saved");
            if($redirect){
              return $this->redirect("/sites/{$model->id}");
            }
          }
        }
        if($model->hasErrors()){
          \Aug::$app->setFlash("saved", "error");
        }
      }
    }
    return $this->renderPartial("view", [
      "model" => $model,
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
    return $this->redirect("/sites");
  }
  protected function getModel($id = null){
    if(!empty($id)){
      return Site::find()->where([
        ["id"=>$id]
        ])->one();
    }
    return new Site();
  }
  protected function getModels(){
    return Site::find()->where([
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
