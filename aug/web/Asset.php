<?php
namespace aug\web;
class Asset{
  public static function register(){
    $jsFiles = get_called_class()::js();
    $cssFiles = get_called_class()::css();
    foreach($jsFiles as $jsFile){
      AssetManager::registerJsFile($jsFile);
    }
    foreach($cssFiles as $cssFile){
      AssetManager::registerCssFile($cssFile);
    }
  }
}
