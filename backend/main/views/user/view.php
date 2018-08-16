<?php
use aug\widgets\Form;
use aug\helpers\Html;
$form = new Form();
?>

<section class="container">
  <?=$form->begin([
    "attributes" => [
      "id" => "form-user-view",
      "method" => "POST",
    ]
  ])?>
  <div class="page page-header page-section">
    <h1><?=($user->isNewRecord) ? "Create User" : "Update user '{$user->username}'"?></h1>
  </div>
  <div class="page-section user">
    <h2>User</h2>
    <?=$this->renderPartial("partials/user", ["form" => $form,"user" => $user])?>
  </div>
  <div class="account page-section">
    <h2>Account</h2>
    <?=$this->renderPartial("partials/account", ["form" => $form,"account" => $account])?>
  </div>
  <?=$form->end()?>
</section>
