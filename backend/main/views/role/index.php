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
        "width" => "40",
        "style" => [
          "text-align" => "center"
        ]
      ],
      "value" => function($model){
        return Html::a("<i class='material-icons'>search</i>", [""],
          [
            "data-on" => "click",
            "data-do" => [
              "action" => "open-window",
              "params" => [
                "href" => "/role/{$model->id}",
                "title" => "<i class='material-icons'>account_circle</i>&nbsp;Role {$model->name}"
              ]
            ],
          ]
        );
      }
    ]
  ]
])?>
<?=Pager::widget([
  "dataProvider" => $dataProvider
])?>
