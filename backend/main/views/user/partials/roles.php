<?php
use aug\models\Role;
?>
<?=$form->field($user, "roles_list[]")->selectInput(Role::getList(), [
  "multiple" => ""
])?>
