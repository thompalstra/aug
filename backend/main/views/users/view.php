<?php
use aug\widgets\Form;
use aug\helpers\Html;
use aug\models\Role;
use aug\widgets\desktop\ToolStrip;

$form = new Form();
$flash = \Aug::$app->getFlash("saved");
$message = "";
if(!empty($flash)){
  if($user->hasErrors() || $account->hasErrors()){
    $message= "<div class='flash-wrapper'><span class='flash flash-error'><i class='material-icons'>clear</i>{$flash}</span></div>";
  } else {
    $message = "<div class='flash-wrapper'><span class='flash flash-success'><i class='material-icons'>check</i>{$flash}</span></div>";
  }
}
?>
<?=ToolStrip::widget([
  "items" => [
    [
      "label" => "File",
      "items" => [
        [
          "label" => $user->isNewRecord ? "Create" : "Update",
          "attributes" => [
            "class" => ["save"]
          ],
        ],
        [
          "label" => "Delete",
          "attributes" => [
            "class" => ["delete"],
            $user->isNewRecord ? ["disabled" => ""] : []
          ],
        ],
        [
          "label" => "Exit",
          "attributes" => [
            "class" => ["close"]
          ]
        ]
      ]
    ]
  ]
])?>
<?=$form->begin([
  "attributes" => [
    "id" => "form-user-view",
    "method" => "POST",
    "action" => \Aug::$app->request->url
  ]
])?>
<?=$message?>
<div class="columns">
  <div class="column">
    <div class="block">
      <?=$form->field($user, "username")->textInput()?>
      <?=$form->field($user, "password")->passwordInput()?>
      <?=$form->field($user, "is_enabled")->checkboxInput()?>
    </div>
    <div class="block">
      <?=$form->field($user, "roles_list[]")->selectInput(Role::getList(), [
        "multiple" => ""
      ])?>
    </div>
  </div>
  <div class="column">
    <div class="block">
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
    </div>
  </div>
</div>
<div class="button-row row-rtl" style="padding: 0 .5rem">
  <?=Html::button( $user->isNewRecord ? "Create" : "Update", [
    "class" => ["btn", "btn-default", "action"]
  ] )?>
  <?php if($user->isNewRecord == false) : ?>
  <?=Html::a( "Delete", ["/users/delete/{$user->id}"], [
    "class" => ["btn", "btn-default", "action"]
    ] )?>
<?php endif; ?>
</div>
<?=$form->end()?>
<script>
  this.setTitle("<i class='material-icons'>account_circle</i>&nbsp;<?=$user->isNewRecord ? "Create user" : "Edit $user->username"?>");
  this.setToolStrip(this.getNode().one(".toolstrip"));
  this.getNode().addEventListener("window-loaded", function(event){
    this.getNode().on("keypress", function(event){
      console.log(event);
    })
    this.getNode().on("click", ".close", function(event){
      this.getNode().do("window-close");
    });
    this.getNode().on("click", ".reload", function(event){
      this.getNode().do("window-reload");
    }.bind(this));
    this.getNode().on("click", ".save", function(event){
      this.getNode().one("#form-user-view").do("submit");
    }.bind(this));
    this.getNode().on("click", ".delete", function(event){
      if(confirm("Are you sure you want to delete this 'user'?") == true){
        this.loadFromURL("/users/delete/<?=$user->id?>")
          .then(function(){
            this.close();
          }.bind(this))
      }
    }.bind(this));
  }.bind(this));
</script>
