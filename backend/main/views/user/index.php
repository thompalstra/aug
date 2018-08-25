<?php
use aug\helpers\Html;
use aug\widgets\Pager;
use aug\widgets\Table;
use aug\models\Role;
?>
<?=Html::tag("span", "New user",
  [
    "class"=>[
      "btn", "btn-default", "success"
    ],
    "data-on" => "click",
    "data-do" => [
      "action" => "open-window",
      "params" => [
        "href" => "/user/create",
        "title" => "<i class='material-icons'>account_circle</i>&nbsp;Create user"
      ]
    ],
  ]
);?>
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
        return Html::a("<i class='material-icons'>search</i>", [""],
          [
            "class"=>[
              "btn", "btn-default", "success"
            ],
            "data-on" => "click",
            "data-do" => [
              "action" => "open-window",
              "params" => [
                "href" => "/user/{$model->id}",
                "title" => "<i class='material-icons'>account_circle</i>&nbsp;User {$model->username}"
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
