<?php
use aug\widgets\Form;
use aug\helpers\Html;
$form = new Form();
$flash = \Aug::$app->getFlash("saved");
$message = "";
if(!empty($flash)){
  if($model->hasErrors()){
    $message= "<div class='flash-wrapper'><span class='flash flash-error'><i class='material-icons'>clear</i>{$flash}</span></div>";
  } else {
    $message = "<div class='flash-wrapper'><span class='flash flash-success'><i class='material-icons'>check</i>{$flash}</span></div>";
  }
}
?>
<?=$form->begin([
  "attributes" => [
    "id" => "form-user-view",
    "method" => "POST",
    "action" => \Aug::$app->request->url
  ]
])?>
<?=$message?>
<h1><?=($model->isNewRecord) ? "Create User" : "Update user '{$model->name}'"?></h1>
<?=$form->field($model, "id")->textInput(["disabled"=>""])?>
<?=$form->field($model, "name")->textInput()?>
<button type="submit">save</button>
<?=$form->end()?>
