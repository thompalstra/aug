<?php
use aug\widgets\Form;
use aug\helpers\Html;
$form = new Form();
$flash = \Aug::$app->getFlash("saved");
$message = "";
if(!empty($flash)){
  if($account->hasErrors() || $user->hasErrors()){
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
<h1><?=($user->isNewRecord) ? "Create User" : "Update user '{$user->username}'"?></h1>
<div class="columns">
  <div class="column">
  <!-- <h2>User</small> -->
    <div class="block block-bordered">
      <?=$this->renderPartial("partials/user", ["form" => $form,"user" => $user])?>
    </div>
  <!-- <h2>Roles</h2> -->
    <div class="block block-bordered">
      <?=$this->renderPartial("partials/roles", ["form" => $form, "user" => $user])?>
    </div>
  </div>
  <div class="column">
    <!-- <h2>Account</h2> -->
    <div class="block block-bordered">
      <?=$this->renderPartial("partials/account", ["form" => $form,"account" => $account])?>
    </div>
  </div>
</div>
<button type="submit">save</button>
<?=$form->end()?>
