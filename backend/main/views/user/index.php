<?php

use aug\helpers\Html;

use aug\widgets\Pager;
use aug\widgets\Table;
?>

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
        return Html::a("View", ["/user/{$model->id}"]);
      }
    ]
  ]
])?>
<?=Pager::widget([
  "dataProvider" => $dataProvider
])?>
