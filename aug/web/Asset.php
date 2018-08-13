<?php
namespace aug\web;
class Asset{
  public static function register(){
    $className = get_called_class();
    $jsFiles = $className::js();
    $cssFiles = $className::css();
    foreach($jsFiles as $jsFile){
      AssetManager::registerJsFile($jsFile);
    }
    foreach($cssFiles as $cssFile){
      AssetManager::registerCssFile($cssFile);
    }
  }
}
