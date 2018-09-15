<?php
use common\assets\CommonAsset;
use backend\main\assets\BackendAsset;

use aug\widgets\desktop\Taskbar;
use aug\widgets\desktop\Workspace;

CommonAsset::register();
BackendAsset::register();
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?=\Aug::$app->controller->title?></title>
    <link rel="stylesheet" href=""/>
    <?=$this->head()?>
  </head>
  <body>
    <main id="dt1" class="desktop desktop-default layout-main">
      <?=Workspace::widget([
        "items" => [
          [
            "icon" => "<i class='material-icons'>account_circle</i>",
            "label" => "Users",
            "attributes" => [
              "class" => ["shortcut"],
              "data-on" => "click",
              "data-do" => [
                "action" => "open-window",
                "params" => [
                  "href" => "/users",
                ]
              ],
            ]
          ],
          \Aug::$app->user->can("siteIndex") ? [
            "icon" => "<i class='material-icons'>list</i>",
            "label" => "Sites",
            "attributes" => [
              "class" => ["shortcut"],
              "data-on" => "click",
              "data-do" => [
                "action" => "open-window",
                "params" => [
                  "href" => "/sites",
                ]
              ],
            ]
          ] : [],
          \Aug::$app->user->can("pageIndex") ? [
            "icon" => "<i class='material-icons'>pageview</i>",
            "label" => "Pages",
            "attributes" => [
              "class" => ["shortcut"],
              "data-on" => "click",
              "data-do" => [
                "action" => "open-window",
                "params" => [
                  "href" => "/pages",
                ]
              ],
            ]
          ] : [],
          \Aug::$app->user->can("roleIndex") ? [
            "icon" => "<i class='material-icons'>security</i>",
            "label" => "Roles",
            "attributes" => [
              "class" => ["shortcut"],
              "data-on" => "click",
              "data-do" => [
                "action" => "open-window",
                "params" => [
                  "href" => "/roles",
                ]
              ],
            ]
          ] : [],
          [
            "content" => \common\widgets\DesktopCountWidget::widget(),
            "attributes" => [
              "class" => ["widget", "widget-3x2"]
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
                        "action" => "open-dialog",
                        "params" => [
                          "href" => "/site/switch-user",
                        ]
                      ]
                    ]
                  ],
                  [
                    "label" => "<i class='material-icons'>exit_to_app</i>&nbsp;Log off",
                    "attributes" => [
                      "class" => ["nav-item"],
                      "data-on" => "click",
                      "data-do" => [
                        "action" => "open-dialog",
                        "params" => [
                          "href" => "/site/logout",
                        ]
                      ]
                    ]
                  ]
                ]
              ],
              \Aug::$app->user->can("userIndex") || \Aug::$app->user->can("roleIndex") ? [
                "label" => "Users",
                "attributes" => [
                  "class" => ["nav-item"]
                ],
                "items" => [
                  \Aug::$app->user->can("userIndex") ? [
                    "label" => "<i class='material-icons'>account_circle</i>&nbsp;Users",
                    "attributes" => [
                      "class" => ["nav-item"],
                      "data-on" => "click",
                      "data-do" => [
                        "action" => "open-window",
                        "params" => [
                          "href" => "/users",
                        ]
                      ]
                    ]
                  ] : [],
                  \Aug::$app->user->can("roleIndex") ? [
                    "label" => "<i class='material-icons'>security</i>&nbsp;Roles",
                    "attributes" => [
                      "class" => ["nav-item"],
                      "data-on" => "click",
                      "data-do" => [
                        "action" => "open-window",
                        "params" => [
                          "href" => "/roles",
                        ]
                      ]
                    ]
                  ] : []
                ]
              ] : [],
              \Aug::$app->user->can("siteIndex") || \Aug::$app->user->can("pageIndex") ? [
                "label" => "CMS",
                "attributes" => [
                  "class" => ["nav-item"]
                ],
                "items" => [
                  \Aug::$app->user->can("siteIndex") ? [
                    "label" => "<i class='material-icons'>list</i>&nbsp;Sites",
                    "attributes" => [
                      "class" => ["nav-item"],
                      "data-on" => "click",
                      "data-do" => [
                        "action" => "open-window",
                        "params" => [
                          "href" => "/sites",
                        ]
                      ]
                    ]
                  ] : [],
                  \Aug::$app->user->can("pageIndex") ? [
                    "label" => "<i class='material-icons'>pageview</i>&nbsp;Pages",
                    "attributes" => [
                      "class" => ["nav-item"],
                      "data-on" => "click",
                      "data-do" => [
                        "action" => "open-window",
                        "params" => [
                          "href" => "/pages",
                        ]
                      ]
                    ]
                  ] : [],
                ]
              ] : []
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
                "label" => "<i class='material-icons'>message</i>&nbsp;Messages"
              ]
            ]
          ]
        ]
      ])?>
    </main>
    <footer></footer>
    <?=$this->footer()?>
    <script>
      var dt = null;
      document.addEventListener("DOMContentLoaded", function(e){
        dt = new Desktop(document.getElementById("dt1"))
      })
    </script>
  </body>
</html>
