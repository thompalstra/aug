<?php
use aug\helpers\Html;
use aug\widgets\Pager;
use aug\widgets\Table;
use aug\widgets\desktop\ToolStrip;
?>
<?=ToolStrip::widget([
  "items" => [
    [
      "label" => "File",
      "attributes" => [
        "class" => ["test"]
      ],
      "items" => [
        [
          "label" => "New",
          "attributes" => [
            "class" => ["new"]
          ],
        ],
        [
          "label" => "Delete selected",
          "attributes" => [
            "class" => ["delete-selected"],
            "disabled" => ""
          ],
        ],
        [
          "label" => "Exit",
          "attributes" => [
            "class" => ["close"]
          ]
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
    "name",
    [
      "label" => "",
      "attributes" => [
        "width" => "40",
        "style" => [
          "text-align" => "center"
        ]
      ],
      "value" => function($model){
        return Html::a("<i class='material-icons'>search</i>", [""],
          [
            "data-on" => "click",
            "data-do" => [
              "action" => "open-window",
              "params" => [
                "href" => "/roles/{$model->id}",
                "title" => "<i class='material-icons'>account_circle</i>&nbsp;Role {$model->name}"
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
  this.setTitle("<i class='material-icons'>security</i>&nbsp;Roles");
  this.setToolStrip(this.getNode().one(".toolstrip"));
  this.getNode().addEventListener("window-loaded", function(event){
    this.getNode().on("click", ".close", function(event){
      this.close();
    }.bind(this));
    this.getNode().on("click", ".reload", function(event){
      this.reload()
    }.bind(this));
    this.getNode().on("click", ".new", function(event){
      this.getWorkspace().openWindow("/roles/create");
    }.bind(this));
    this.getNode().on("click", ".delete-selected", function(event){
      let checked = this.getNode().find("[name='ids[]']:checked");
      let list = [];
      checked.forEach(function(node){
        list.push(node.value);
      });
      if(confirm("Are you sure you want to remove the select item(s): '" + list.join(", ") + "'?")){
        this.loadFromURL("/roles/delete/" + list.join(","));
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
