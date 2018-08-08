<?php
namespace frontend\main\controllers;
use main\models\Blog;
class CustomController extends \aug\web\Controller{
  public function actionBlog(){
    echo "<h2>customer/blog</h2>";
  }
}
