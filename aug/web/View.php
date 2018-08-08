<?php
namespace aug\web;
class View implements ViewInterface{
  public function render($viewName, $data = []){
    $viewPath = \Aug::$app->controller->viewPath;
    $layoutPath = \Aug::$app->controller->layoutPath;
    $layoutName = \Aug::$app->controller->layout;

    $layoutFile = "{$layoutPath}{$layoutName}.php";
    $viewFile = "{$viewPath}{$viewName}.php";

    echo $this->renderFile($layoutFile, [
      "content" => $this->renderFile($viewFile, $data)
    ]);
    exit();
  }
  protected function renderFile($file, $data = []){
    $root = \Aug::$app->root;
    $fp = "{$root}{$file}";
    if(file_exists($fp)){
      extract($data);
      ob_start();
      include($fp);
      $content = ob_get_contents();
      ob_end_clean();
      return $content;
    } else {
      return "{$file} not found";
    }
  }
}
