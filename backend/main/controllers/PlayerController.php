<?php
namespace backend\main\controllers;
class PlayerController extends \aug\web\Controller{
  public function rules(){
    return [
      "*" => [
        "allow" => !\Aug::$app->user->isGuest,
        "onDeny" => function(){
          $this->redirect("/login");
        }
      ],
      "index" => ["allow" => \Aug::$app->user->can("playerIndex")],
      "view" => ["allow" => \Aug::$app->user->can("playerView")],
      "create" => ["allow" => \Aug::$app->user->can("playerCreate")]
    ];
  }
  public $className = "\\common\\models\\Player";
  public $shortClassName = "Player";
  public $indexUrl = "/player/index";
  public $viewUrl = "/player/view";

  protected function createModel(){
    $className = $this->className;
    return new $className();
  }
  protected function findModel($id){
    $className = $this->className;
    if(!empty($id)){
      $model = $className::findOne([
        ["id"=>$id],
        ["is_deleted"=>0]
      ]);
      if(!empty($model)){
        return $model;
      }
    }
    return $this->runError(new \Exception("{$this->shortClassName} with id '{$id}' does not exist."));
  }
  protected function findModels(){
    $className = $this->className;
    return $className::findOne([["is_deleted"=>0]]);
  }
  public function actionIndex(){
    return $this->render("index", [
      "models" > $this->findModels()
    ]);
  }
  public function actionCreate(){
    $model = $this->createModel();
    if($_POST && $model->load($_POST) && $model = $model->save()){
      return $this->redirect($this->viewUrl, ["id"=>$model->id]);
    }
    return $this->render("create",[
      "model" => $model
    ]);
  }
  public function actionDelete($id){
    if($model = $this->findModel($id) && $model->delete()){
      return $this->redirect($this->indexUrl);
    }
  }
  public function actionView($id){
    if($model = $this->findModel($id)){
      if($_POST && \Aug::$app->user->can("playerUpdate")){
        if($model->load($_POST) && $model->save()){
          \Aug::$app->setSplash("{$this->shortClassName} has been succesfully saved.");
        } else {
          \Aug::$app->setSplash("{$this->shortClassName} could not be saved.");
        }
      }
      return $this->render("view",[
        "model" => $model
      ]);
    }
  }
}
