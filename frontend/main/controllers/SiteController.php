<?php
namespace frontend\main\controllers;
class SiteController extends \aug\web\Controller{
  public function actionHome(){
    return $this->render("home", [
      "a" => "b"
    ]);
  }
  public function actionContact(){
    echo "<h2>site/contact</h2>";
  }
}
