<?php
use common\assets\CommonAsset;
use backend\main\assets\BackendAsset;

use aug\widgets\desktop\Taskbar;
use aug\widgets\desktop\Workspace;

CommonAsset::register();
BackendAsset::register();
?>
<html>
  <header>
    <title><?=\Aug::$app->controller->title?></title>
    <link rel="stylesheet" href=""/>
    <?=$this->head()?>
  </header>
  <body  style="overflow: hidden">
    <main id="dt1" class="desktop desktop-default">
      <?=Workspace::widget([
        "items" => [
          [
            // "url" => "/user",
            "icon" => "<i class='material-icons'>account_circle</i>",
            "label" => "Users",
            "attributes" => [
              "data-href" => "/user",
              "data-title" => "&lt;i class=&quot;material-icons&quot;&gt;account_circle&lt;/i&gt;&nbsp;Users"
            ]
          ]
        ]
      ])?>
      <?=Taskbar::widget([
        "attributes" => [
          "class" => "desktop-taskbar"
        ],
        "items" => [
          [
            "label" => "<i class='material-icons'>home</i>",
            "attributes" => [
              "class" => ["home"]
            ],
            "items" => [
              [
                "label" => "CMS",
                "items" => [
                  [
                    "label" => "<i class='material-icons'>pageview</i>&nbsp;Pages",
                    "attributes" => [
                      "class" => ["open-win"],
                      "data-href" => "/page",
                      "data-title" => "&lt;i class=&quot;material-icons&quot;&gt;pageview&lt;/i&gt;&nbsp;Pages"
                    ]
                  ],
                  [
                    "label" => "<i class='material-icons'>account_circle</i>&nbsp;Users",
                    "attributes" => [
                      "class" => ["open-win"],
                      "data-href" => "/user",
                      "data-title" => "&lt;i class=&quot;material-icons&quot;&gt;account_circle&lt;/i&gt;&nbsp;Users"
                    ]
                  ],
                  [
                    "label" => "<i class='material-icons'>security</i>&nbsp;Roles",
                    "attributes" => [
                      "class" => ["open-win"],
                      "data-href" => "/role",
                      "data-title" => "&lt;i class=&quot;material-icons&quot;&gt;security&lt;/i&gt;&nbsp;Roles"
                    ]
                  ]
                ]
              ]
            ]
          ]
        ]
      ])?>
    </main>
    <footer></footer>
    <script>
      var dt = null;
      document.addEventListener("DOMContentLoaded", function(e){
        dt = new Desktop(document.getElementById("dt1"))
      })
    </script>
    <?=$this->footer()?>
  </body>
</html>
