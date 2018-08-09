<?php
namespace backend\main\controllers;

use common\models\CmsUser;

class SiteController extends \aug\web\Controller{
  public function actionIndex(){
    $user = CmsUser::find()
      ->where([
        ["id" => 1]
      ])->one();
    var_dump($user->roles); die;
  }
}
