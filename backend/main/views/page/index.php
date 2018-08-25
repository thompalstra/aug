<?php
use aug\helpers\Html;
use aug\widgets\Pager;
use aug\widgets\Table;
?>
<?=Table::widget([
  "dataProvider" => $dataProvider,
  "columns" => [
    "id",
    "name",
    [
      "label" => "",
      "attributes" => [
        "width"=>"80",
        "style" => [
          "text-align" => "center"
        ]
      ],
      "value" => function($model){
        return Html::a("<i class='material-icons'>search</i>", ["/page/{$model->id}"]);
      }
    ]
  ]
])?>
<?=Pager::widget([
  "dataProvider" => $dataProvider
])?>
