<?php
namespace common\assets;
class CommonAsset extends \aug\web\Asset{
  public static function js(){
    return [
      ["/common/assets/js/polyfill.js"],
      ["/common/assets/js/core.js"],
      ["/common/assets/js/dom.js"],
      ["/common/assets/js/widgets.js"],
      ["/common/assets/js/desktop/Desktop.js"],
      ["/common/assets/js/desktop/Taskbar.js"],
      ["/common/assets/js/desktop/Workspace.js"]
    ];
  }
  public static function css(){
    return [
      ["/common/assets/css/bundle.css"],
    ];
  }
}
