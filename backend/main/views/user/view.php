<?php
use aug\widgets\Form;
use aug\helpers\Html;
$form = new Form();
?>
<?=\Aug::$app->getFlash("user-view-saved");?>
<?=$form->begin([
  "attributes" => [
    "id" => "form-user-view",
    "method" => "POST",
    "action" => \Aug::$app->request->url
  ]
])?>
<div class="page-module module-white">
  <h1><?=($user->isNewRecord) ? "Create User" : "Update user '{$user->username}'"?></h1>
</div>
<div class="page-module module-white">
  <h2>User</h2>
  <?=$this->renderPartial("partials/user", ["form" => $form,"user" => $user])?>
</div>
<div class="page-module module-white">
  <h2>Account</h2>
  <?=$this->renderPartial("partials/account", ["form" => $form,"account" => $account])?>
</div>
<button type="submit">save</button>
<?=$form->end()?>
