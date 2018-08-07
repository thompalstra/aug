<?php
namespace main\controllers;
use main\models\Blog;
class BlogController extends \aug\web\Controller{
  public function actionIndex(){
    $blogs = Blog::find()->all();
    return $this->render("index", [
      "blogs" => $blogs
    ]);
  }
  public function actionView($slug){
    $blog = Blog::find()
      ->where([
        ["slug" => $slug]
      ])->one();
    return $this->render("view", [
      "blog" => $blog
    ]);
  }
}
