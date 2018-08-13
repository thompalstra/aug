<?php
$options = [
  "" => "Other",
  "nl_NL" => "Netherlands",
  "en_GB" => "United Kingdom",
  "en_US" => "United States",
];
?>
<?=$form->field($account, "first_name")->textInput()?>
<?=$form->field($account, "last_name")->textInput()?>
<?=$form->field($account, "nationality")->selectInput($options, [])?>
