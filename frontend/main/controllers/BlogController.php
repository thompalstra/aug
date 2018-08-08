<?php
namespace frontend\main\controllers;
use main\models\Blog;
class BlogController extends \aug\web\Controller{
  public function actionIndex(){
    return $this->render("index");
  }
  public function actionView($slug){
      return $this->render("view", [
        "slug" => $slug
      ]);
  }
}
