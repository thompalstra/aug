<?php
namespace aug\web;
class AssetManager{

  protected static $_assets = [
    0 => [
      "js" => [],
      "css" => []
    ],
    1 => [
      "js" => [],
      "css" => []
    ]
  ];

  const ASSET_POS_HEAD = 0;
  const ASSET_POS_FOOTER = 1;

  public static function registerJsFile($js){
    if(!isset($js["pos"])){
      $js["pos"] = self::ASSET_POS_FOOTER;
    }
    self::$_assets[$js["pos"]]["js"][] = $js;
  }
  public static function registerCssFile($css){
    if(!isset($css["pos"])){
      $css["pos"] = self::ASSET_POS_HEAD;
    }
    self::$_assets[$css["pos"]]["css"][] = $css;
  }
  public static function getHead(){
    return self::sortAssets(self::mergeAssets(self::$_assets[self::ASSET_POS_HEAD]));
  }
  public static function getFooter(){
    return self::sortAssets(self::mergeAssets(self::$_assets[self::ASSET_POS_FOOTER]));
  }
  public static function mergeAssets($asset){
    $assets = [];
    foreach($asset["js"] as $k => $js){
      $js["type"] = "js";
      $assets[] = $js;
    }
    foreach($asset["css"] as $k => $js){
      $js["type"] = "css";
      $assets[] = $js;
    }
    return $assets;
  }
  public static function sortAssets($assets){
    // usort($assets, function($a, $b){
    //   if(isset($a["depends"])){
    //     $changed = true;
    //     return ($a["depends"] == $b[0]) ? -1 : 1;
    //   }
    // });
    return $assets;
  }
}
