<?php
use aug\widgets\Form;
use aug\helpers\Html;
$form = new Form();
?>
<section class="container">
  <?=$form->begin([
    "attributes" => [
      "id" => "form-page-view",
      "method" => "POST",
    ]
  ])?>
  <div class="page-module module-white">
    <h1><?=($page->isNewRecord) ? "Create Page" : "Update page '{$page->name}'"?></h1>
  </div>
  <div class="page-module module-white">
    <h2>Page</h2>
    <?=$this->renderPartial("partials/page", ["form" => $form,"page" => $page])?>
  </div>
  <div class="page-module module-white">
    <h2>Content</h2>
    <?=""//$this->renderPartial("partials/account", ["form" => $form,"account" => $account])?>
  </div>
  <?=$form->end()?>
</section>
