<?php
use aug\widgets\Form;
use aug\helpers\Html;
use aug\widgets\desktop\ToolStrip;

$form = new Form();
$flash = \Aug::$app->getFlash("saved");
$message = "";
if(!empty($flash)){
  if($model->hasErrors()){
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
          "label" => $model->isNewRecord ? "Create" : "Update",
          "attributes" => [
            "class" => ["save"]
          ],
        ],
        [
          "label" => "Delete",
          "attributes" => [
            "class" => ["delete"],
            $model->isNewRecord ? ["disabled" => ""] : []
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
    "id" => "form-role-view",
    "method" => "POST",
    "action" => \Aug::$app->request->url
  ]
])?>
<?=$message?>
<div class="columns">
  <div class="column">
    <div class="block">
      <?=$form->field($model, "id")->textInput(["disabled"=>""])?>
      <?=$form->field($model, "name")->textInput()?>
    </div>
  </div>
</div>
<div class="button-row row-rtl" style="padding: 0 .5rem">
  <?=Html::button( $model->isNewRecord ? "Create" : "Update", [
    "class" => ["btn", "btn-default", "action"]
  ] )?>
</div>
<?=$form->end()?>
<script>
  this.setTitle("<i class='material-icons'>security</i>&nbsp;<?=$model->isNewRecord ? "Create role" : "Edit $model->name"?>");
  this.setToolStrip(this.getNode().one(".toolstrip"));
  this.getNode().addEventListener("window-loaded", function(event){
    this.getNode().on("click", ".close", function(event){
      this.getNode().do("window-close");
    });
    this.getNode().on("click", ".reload", function(event){
      this.getNode().do("window-reload");
    }.bind(this));
    this.getNode().on("click", ".save", function(event){
      this.getNode().one("#form-role-view").do("submit");
    }.bind(this));
    this.getNode().on("click", ".delete", function(event){
      if(confirm("Are you sure you want to delete this 'role'?") == true){
        this.loadFromURL("/roles/delete/<?=$model->id?>")
          .then(function(){
            this.close();
          }.bind(this))
      }
    }.bind(this));
  }.bind(this));
</script>
