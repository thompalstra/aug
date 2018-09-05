<?php
namespace common\widgets;

use common\models\User;
use common\models\Page;
use common\models\Site;

class DesktopCountWidget extends \aug\base\Widget{
  public function prepare($options = []){
    foreach($options as $k => $v){
      $this->$k = $v;
    }
  }
  public function run(){
    return $this->toHtml();
  }
  public function toHtml(){
    $totalUsersCount = User::find()->where([
      ["is_deleted" => 0]
    ])->count();
    $totalSitesCount = Site::find()->where([
      ["is_deleted" => 0]
    ])->count();
    $totalPagesCount = Page::find()->where([
      ["is_deleted" => 0]
    ])->count();
return <<<HTML
<div>
  <p><strong>Total users</strong>: $totalUsersCount</p>
  <p><strong>Total sites</strong>: $totalSitesCount</p>
  <p><strong>Total pages</strong>: $totalPagesCount</p>
</div>
HTML;
  }
}
