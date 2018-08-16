<?php
namespace backend\main\assets;
class BackendAsset extends \aug\web\Asset{
  public static function js(){
    return [
      ["/backend/main/assets/js/main.js"],
    ];
  }
  public static function css(){
    return [
      ["/backend/main/assets/css/main.css"],
    ];
  }
}
