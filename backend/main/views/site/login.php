<?php
use aug\widgets\Form;
use aug\helpers\Html;
?>
<section class="section section-login">
  <?php $form = new Form(); ?>
  <?=$form->begin([

  ])?>
  <?=$form->field($loginForm, "username")->textInput([
    "autocomplete"=>"off",
    "tabindex" => 1
  ])?>
  <?=$form->field($loginForm, "password")->passwordInput([
    "tabindex" => 2
  ])?>
  <div class="button-row row-rtl">
    <?=Html::button("Login", [
        "class" => ["btn", "btn-default", "action"],
        "tabindex" => 3
    ])?>
  </div>
</section>
<script>
  document.addEventListener("DOMContentLoaded", function(event){
    document.getElementById("loginform-username").focus();
  })
</script>
