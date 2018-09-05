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
          [
            "icon" => "<i class='material-icons'>list</i>",
            "label" => "Sites",
            "attributes" => [
              "class" => ["shortcut"],
              "data-on" => "click",
              "data-do" => [
                "action" => "open-window",
                "params" => [
                  "href" => "/sites",
                  // "title" => "<i class='material-icons'>list</i>&nbsp;Sites"
                ]
              ],
            ]
          ],
          [
            "icon" => "<i class='material-icons'>pageview</i>",
            "label" => "Pages",
            "attributes" => [
              "class" => ["shortcut"],
              "data-on" => "click",
              "data-do" => [
                "action" => "open-window",
                "params" => [
                  "href" => "/pages",
                  // "title" => "<i class='material-icons'>pageview</i>&nbsp;Pages"
                ]
              ],
            ]
          ],
          [
            "icon" => "<i class='material-icons'>security</i>",
            "label" => "Roles",
            "attributes" => [
              "class" => ["shortcut"],
              "data-on" => "click",
              "data-do" => [
                "action" => "open-window",
                "params" => [
                  "href" => "/roles",
                  // "title" => "<i class='material-icons'>security</i>&nbsp;Roles"
                ]
              ],
            ]
          ],
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
                        "action" => "open-window",
                        "params" => [
                          "href" => "/site/switch-user",
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
              ],
              [
                "label" => "Users",
                "attributes" => [
                  "class" => ["nav-item"]
                ],
                "items" => [
                  [
                    "label" => "<i class='material-icons'>account_circle</i>&nbsp;Users",
                    "attributes" => [
                      "class" => ["nav-item"],
                      "data-on" => "click",
                      "data-do" => [
                        "action" => "open-window",
                        "params" => [
                          "href" => "/users",
                          // "title" => "<i class='material-icons'>account_circle</i>&nbsp;Users"
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
                          "href" => "/roles",
                          // "title" => "<i class='material-icons'>security</i>&nbsp;Roles"
                        ]
                      ]
                    ]
                  ]
                ]
              ],
              [
                "label" => "CMS",
                "attributes" => [
                  "class" => ["nav-item"]
                ],
                "items" => [
                  [
                    "label" => "<i class='material-icons'>list</i>&nbsp;Sites",
                    "attributes" => [
                      "class" => ["nav-item"],
                      "data-on" => "click",
                      "data-do" => [
                        "action" => "open-window",
                        "params" => [
                          "href" => "/sites",
                          // "title" => "<i class='material-icons'>list</i>&nbsp;Sites"
                        ]
                      ]
                    ]
                  ],
                  [
                    "label" => "<i class='material-icons'>pageview</i>&nbsp;Pages",
                    "attributes" => [
                      "class" => ["nav-item"],
                      "data-on" => "click",
                      "data-do" => [
                        "action" => "open-window",
                        "params" => [
                          "href" => "/pages",
                          // "title" => "<i class='material-icons'>pageview</i>&nbsp;Pages"
                        ]
                      ]
                    ]
                  ],
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
