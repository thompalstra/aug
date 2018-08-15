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
    <button type="submit"><?=($user->isNewRecord) ? "Create" : "Update"?></button>
    <div class="tabcontrol">
      <ul class="controls">
        <li class="active"><a href="#tct1">User</a></li>
        <li><a href="#tct2">Account</a></li>
      </ul>
      <ul class="tabs">
        <?=Html::tag("li", [
          "id" => "tct1",
          "class" => ["tab","active"] + ($user->hasErrors() ? ["has-error"] : [])
        ], $this->renderPartial("partials/user", ["form" => $form,"user" => $user])
        )?>
        <?=Html::tag("li", [
          "id" => "tct2",
          "class" => ["tab"] + ($account->hasErrors() ? ["has-error"] : [])
        ], $this->renderPartial("partials/account", ["form" => $form,"account" => $account])
        )?>
      </ul>
    </div>
  <?=$form->end()?>
</section>
