<?php
namespace backend\main\controllers;
use backend\main\models\LoginForm;
use common\models\User;
class SiteController extends \aug\web\Controller{
  public function rules(){
    return [
      "login" => [
        "allow" => true
      ],
      "*" => [
        "allow" => !\Aug::$app->user->isGuest && \Aug::$app->user->can("admin")
      ],
    ];
  }
  public function actionLogin(){
    $user = User::find()->one();

    $loginForm = new LoginForm();
    if($_POST && $loginForm->load($_POST) && $loginForm->login()){
      return $this->redirect("/");
    }
    return $this->render("login", [
      "loginForm" => $loginForm
    ]);
  }
  public function actionIndex(){
    return $this->render("index");
  }
  public function actionLogout(){
    \Aug::$app->user->logout();
    header("Location: /login");
  }
}
