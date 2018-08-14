<?php
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
    ]
  ]
])?>
<?=Pager::widget([
  "dataProvider" => $dataProvider
])?>
