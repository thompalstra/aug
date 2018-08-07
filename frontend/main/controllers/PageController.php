<?php
namespace main\controllers;
use main\models\CmsPage;
class PageController extends \aug\web\Controller{
  public function actionView($slug){
    $this->layout = "uniek";
    $cmsPage = CmsPage::find()
      ->where([
        "and",
        "slug" => $slug,
        "is_enabled" => true,
        ["IN", "category_id", [1,2]]
      ])->one();
    if($cmsPage){
      return $this->render("view");
    } else {
      return $this->render("error");
    }
  }
}

?>
