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
  <body>
    <!-- <main id="dt1" class="desktop desktop-default" style="background-image: url(http://getwallpapers.com/wallpaper/full/b/0/f/347985.jpg)"> -->
    <main id="dt1" class="desktop desktop-default">
      <?=Workspace::widget([
        "items" => [
          // [
          //   "url" => "/user",
          //   "icon" => "<i class='material-icons'>account_circle</i>",
          //   "label" => "Users"
          // ],
          // [
          //   "url" => "/user",
          //   "icon" => "<i class='material-icons'>account_circle</i>",
          //   "label" => "Users"
          // ],
          // [
          //   "url" => "/user",
          //   "icon" => "<i class='material-icons'>account_circle</i>",
          //   "label" => "Users"
          // ]
        ]
      ])?>
      <?=Taskbar::widget([
        "attributes" => [
          "class" => "desktop-taskbar"
        ],
        "items" => [
          [
            "label" => "<i class='material-icons'>home</i>",
            "items" => [
              [
                "label" => "CMS",
                "items" => [
                  [
                    "label" => "Pages",
                    "attributes" => [
                      "class" => ["open-win"],
                      "data-href" => "/page",
                      "data-title" => "Manage pages"
                    ]
                  ],
                  [
                    "label" => "Users",
                    "attributes" => [
                      "class" => ["open-win"],
                      "data-href" => "/user",
                      "data-title" => "Manage users"
                    ]
                  ],
                  [
                    "label" => "Roles",
                    "attributes" => [
                      "class" => ["open-win"],
                      "data-href" => "/role",
                      "data-title" => "Manage roles"
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
