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
            "icon" => "<i class='material-icons'>account_circle</i>",
            "label" => "Users",
            "attributes" => [
              "data-on" => "click",
              "data-do" => [
                "action" => "open-window",
                "params" => [
                  "href" => "/user",
                  "title" => "<i class='material-icons'>account_circle</i>&nbsp;Users"
                ]
              ],
            ]
          ],
          [
            "icon" => "<i class='material-icons'>pageview</i>",
            "label" => "Sites",
            "attributes" => [
              "data-on" => "click",
              "data-do" => [
                "action" => "open-window",
                "params" => [
                  "href" => "/site",
                  "title" => "<i class='material-icons'>pageview</i>&nbsp;Sites"
                ]
              ],
            ]
          ],
          [
            "icon" => "<i class='material-icons'>security</i>",
            "label" => "Roles",
            "attributes" => [
              "data-on" => "click",
              "data-do" => [
                "action" => "open-window",
                "params" => [
                  "href" => "/role",
                  "title" => "<i class='material-icons'>security</i>&nbsp;Roles"
                ]
              ],
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
              "class" => ["nav-item home"]
            ],
            "items" => [
              [
                "label" => "CMS",
                "attributes" => [
                  "class" => ["nav-item"]
                ],
                "items" => [
                  [
                    "label" => "<i class='material-icons'>pageview</i>&nbsp;Pages",
                    "attributes" => [
                      "class" => ["nav-item"],
                      "data-on" => "click",
                      "data-do" => [
                        "action" => "open-window",
                        "params" => [
                          "href" => "/page",
                          "title" => "<i class='material-icons'>pageview</i>&nbsp;Pages"
                        ]
                      ]
                    ]
                  ],
                  [
                    "label" => "<i class='material-icons'>account_circle</i>&nbsp;Users",
                    "attributes" => [
                      "class" => ["nav-item"],
                      "data-on" => "click",
                      "data-do" => [
                        "action" => "open-window",
                        "params" => [
                          "href" => "/user",
                          "title" => "<i class='material-icons'>account_circle</i>&nbsp;Users"
                        ]
                      ]
                    ]
                  ],
                  [
                    "label" => "<i class='material-icons'>security</i>&nbsp;Roles",
                    "attributes" => [
                      "class" => ["nav-item"],
                      "data-on" => "click",
                      "data-do" => [
                        "action" => "open-window",
                        "params" => [
                          "href" => "/role",
                          "title" => "<i class='material-icons'>security</i>&nbsp;Roles"
                        ]
                      ]
                    ]
                  ]
                ]
              ],
              [
                "label" => "<i class='material-icons'>account_circle</i>&nbsp;" . \Aug::$app->user->username,
                "attributes" => [
                  "class" => ["nav-item"]
                ],
                "items" => [
                  [
                    "label" => "<i class='material-icons'>compare_arrows</i>&nbsp;Switch user",
                    "attributes" => [
                      "class" => ["nav-item"],
                      "data-on" => "click",
                      "data-do" => [
                        "action" => "open-window",
                        "params" => [
                          "href" => "/site/switch-user",
                          "title" => "<i class='material-icons'>compare_arrows</i>&nbsp;Switch user"
                        ]
                      ]
                    ]
                  ],
                  [
                    "label" => "<i class='material-icons'>exit_to_app</i>&nbsp;Log off",
                    "url" => "/site/logout",
                    "attributes" => [
                      "class" => ["nav-item"]
                    ]
                  ]
                ]
              ]
            ],

          ],
          [
            "attributes" => [
              "class" => ["nav-item", "tasks"]
            ]
          ],
          [
            "label" => "<i class='material-icons'>assistant_photo</i>",
            "attributes" => [
              "class" => ["nav-item", "tray"],
            ],
            "items" => [
              [
                "label" => "<i class='material-icons'>message</i>"
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
