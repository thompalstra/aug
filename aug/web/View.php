<?php
namespace aug\web;
use aug\helpers\FileHelper;
use aug\helpers\ClassHelper;
use aug\web\AssetManager;
class View implements ViewInterface{
  public function render($viewName, $data = []){
    $viewPath = \Aug::$app->controller->viewPath;
    $layoutPath = \Aug::$app->controller->layoutPath;
    $layoutName = \Aug::$app->controller->layout;

    $layoutFile = FileHelper::path("{$layoutPath}{$layoutName}.php");
    $viewFile = FileHelper::path("{$viewPath}{$viewName}.php");
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
  public function renderPartial($viewName, $data = []){
    $viewPath = \Aug::$app->controller->viewPath;
    $viewFile = FileHelper::path("{$viewPath}{$viewName}.php");
    return $this->renderFile($viewFile, $data);
  }

  public function head(){
    return $this->outputAssets(AssetManager::getHead());
  }
  public function outputAssets($files){
    $out = [];
    foreach($files as $file){
      if($file["type"] == "css"){
        $out[] = "<link rel='stylesheet' href='{$file[0]}'/>";
      } else {
        $out[] = "<script src='{$file[0]}'></script>";
      }
    }
    return implode("",$out);
  }
  public function footer(){
    return $this->outputAssets(AssetManager::getFooter());
  }
}