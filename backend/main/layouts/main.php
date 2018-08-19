<?php
use common\assets\CommonAsset;
use backend\main\assets\BackendAsset;

use aug\widgets\desktop\Taskbar;
use aug\widgets\desktop\Items;

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
    <main id="dt1" class="desktop desktop-default" style="background-image: url(http://getwallpapers.com/wallpaper/full/b/0/f/347985.jpg)">
      <?=Items::widget([
        "items" => [
          [
            "url" => "/user",
            "icon" => "<i class='material-icons'>account_circle</i>",
            "label" => "Users"
          ],
          [
            "url" => "/user",
            "icon" => "<i class='material-icons'>account_circle</i>",
            "label" => "Users"
          ],
          [
            "url" => "/user",
            "icon" => "<i class='material-icons'>account_circle</i>",
            "label" => "Users"
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
              "items" => [
                [
                  "label" => "CMS",
                  "items" => [
                    [
                      "label" => "Page",
                      "items" => [
                        [
                          "label" => "Manage pages",
                          "url" => "/page",
                          "attributes" => [
                            "class" => ["desktop-window-open"]
                          ]
                        ],
                        [
                          "label" => "Create page",
                          "url" => "/page/new",
                          "attributes" => [
                            "class" => ["desktop-window-open"]
                          ]
                        ]
                      ]
                    ]
                  ]
                ],
                [
                  "label" => "Users",
                  "items" => [
                    [
                      "label" => "Manage users",
                      "url" => "/user",
                      "attributes" => [
                        "class" => ["desktop-window-open"]
                      ]
                    ],
                    [
                      "label" => "Create user",
                      "url" => "/user/new",
                      "attributes" => [
                        "class" => ["desktop-window-open"]
                      ]
                    ],
                  ]
                ],
                [
                  "label" => "Roles",
                  "items" => [
                    [
                      "label" => "Manage roles",
                      "url" => "/role",
                      "attributes" => [
                        "class" => ["desktop-window-open"]
                      ]
                    ],
                    [
                      "label" => "Create role",
                      "url" => "/role/new",
                      "attributes" => [
                        "class" => ["desktop-window-open"]
                      ]
                    ],
                  ]
                ]
              ],
            ],
          ]
        ])?>
      <!-- </section> -->
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
