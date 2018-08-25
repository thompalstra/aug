<?php
namespace backend\main\controllers;
use aug\data\DataProvider;
use common\models\Page;
class PageController extends \aug\web\Controller{
  public function actionIndex(){
    return $this->renderPartial("index", [
      "dataProvider" => new DataProvider([
        "query" => $this->getModels(),
        "pagination" => $this->getPagination()
      ])
    ]);
  }
  public function actionView($id = null){
    $page = $this->getModel($id);
    return $this->renderPartial("view", [
      "page" => $page,
      "content" => $page->content
    ]);
  }
  protected function getModel($id = null){
    if(!empty($id)){
      return Page::find()->where([
        ["id"=>$id]
        ])->one();
    }
    return new Page();
  }
  protected function getModels(){
    return Page::find()->where([
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
