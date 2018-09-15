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
<div class="columns">
  <div class="column">
    <div class="block">
      <?=$form->field($loginForm, "username")->textInput()?>
      <?=$form->field($loginForm, "password")->passwordInput()?>
    </div>
  </div>
</div>
<div class="row-rtl" style="padding: .5rem">
  <?=Html::button("OK", [
    "type" => "submit",
    "class" => ["btn", "btn-dialog", "success"]
  ])?>
  <?=Html::button("CANCEL", [
      "class" => ["btn", "btn-dialog", "success"],
      "data-on" => "click",
      "data-do" => [
        "action" => "dialog-hide",
        "params" => []
      ],
  ])?>
</div>
<?=$form->end()?>
<script>
  //this.setTitle("<i class='material-icons'>compare_arrows</i>&nbsp;Switch user");
</script>
