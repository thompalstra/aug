<?php
use aug\helpers\Html;
use aug\widgets\Pager;
use aug\widgets\Table;
?>
<div class="window-bar">
  <span>Manage users</span>
  <div class="window-actions">
    <i class="window-action material-icons minimize">minimize</i>
    <i class="window-action material-icons maximize">fullscreen</i>
    <i class="window-action material-icons close">close</i>
  </div>
</div>
<div class="window-content">
  <?=Pager::widget([
    "dataProvider" => $dataProvider
  ])?>
  <?=Table::widget([
    "dataProvider" => $dataProvider,
    "columns" => [
      "id",
      "username",
      "account.first_name",
      "account.last_name",
      "account.nationality",
      [
        "attribute" => "is_enabled",
        "value" => function($model){
          return ($model->is_enabled) ? "Y" : "N";
        }
      ],
      [
        "label" => "das",
        "value" => function($model){
          return Html::a("<i class='material-icons'>search</i>", ["/user/{$model->id}"], ["class"=>["desktop-window-open"]]);
        }
      ]
    ]
  ])?>
  <?=Pager::widget([
    "dataProvider" => $dataProvider
  ])?>
</div>
