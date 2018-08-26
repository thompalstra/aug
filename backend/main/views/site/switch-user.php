<?php
use aug\widgets\Form;
use aug\helpers\Html;
$form = new Form();
?>

<?=$form->begin([
  "attributes" => [
    "id" => "form-switch-user",
    "method" => "POST",
    "action" => \Aug::$app->request->url
  ]
])?>
<?=$form->field($loginForm, "username")->textInput()?>
<?=$form->field($loginForm, "password")->passwordInput()?>
<?=Html::button("switch", [
    "class" => ["btn", "btn-default", "success"]
])?>
<?=$form->end()?>
