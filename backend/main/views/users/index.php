<?php
use aug\helpers\Html;
use aug\widgets\Pager;
use aug\widgets\Table;
use aug\models\Role;
use aug\widgets\desktop\ToolStrip;
?>
<?=ToolStrip::widget([
  "items" => [
    [
      "label" => "File",
      "items" => [
        [
          "label" => "New",
          "attributes" => ["class" => ["new"]],
        ],
        [
          "label" => "Delete selected",
          "attributes" => ["class" => ["delete-selected"],"disabled" => ""],
        ],
        [
          "label" => "Exit",
          "attributes" => ["class" => ["close"]]
        ]
      ]
    ],
    [
      "label" => "Settings",
      "items" => [
        [
          "label" => "Refresh",
          "attributes" => ["class"=>["reload"]]
        ]
      ]
    ]
  ]
])?>
<?=Table::widget([
  "dataProvider" => $dataProvider,
  "columns" => [
    [
      "label" => "",
      "value" => function($model){
        return Html::checkboxInput([
          "name" => "ids[]",
          "value" => $model->id
        ]);
      }
    ],
    "id",
    "username",
    "account.first_name",
    "account.last_name",
    "account.nationality",
    [
      "attribute" => "is_enabled",
      "value" => function($model){
        return ($model->is_enabled) ? "Y" : "N";
      }
    ],
    [
      "label" => "",
      "value" => function($model){
        return Html::a("<i class='material-icons'>search</i>", [""],
          [
            "data-on" => "click",
            "data-do" => [
              "action" => "open-window",
              "params" => [
                "href" => "/users/{$model->id}",
              ]
            ],
          ]
        );
      }
    ]
  ]
])?>
<?=Pager::widget([
  "dataProvider" => $dataProvider
])?>
<script>
  this.setTitle("<i class='material-icons'>account_circle</i>&nbsp;Users");
  this.setToolStrip(this.getNode().one(".toolstrip"));
  this.getNode().addEventListener("window-loaded", function(event){
    this.getNode().on("click", ".close", function(event){
      this.getNode().do("window-close");
    }.bind(this));
    this.getNode().on("click", ".reload", function(event){
      this.getNode().do("window-reload");
    }.bind(this));
    this.getNode().on("click", ".new", function(event){
      this.getWorkspace().openWindow("/users/create");
    }.bind(this));
    this.getNode().on("click", ".delete-selected", function(event){
      let checked = this.getNode().find("[name='ids[]']:checked");
      let list = [];
      checked.forEach(function(node){
        list.push(node.value);
      });
      if(confirm("Are you sure you want to remove the select item(s): '" + list.join(", ") + "'?")){
        this.loadFromURL("/users/delete/" + list.join(","));
      }
    }.bind(this));
    this.getNode().on("change", "[name='ids[]']", function(event){
      let checked = this.getNode().find("[name='ids[]']:checked");
      let button = this.getNode().one(".delete-selected");
      if(checked.length > 0){
        button.removeAttribute("disabled");
      } else {
        button.setAttribute("disabled","");
      }
    }.bind(this))
  }.bind(this))
</script>
