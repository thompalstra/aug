<?php
use aug\widgets\Taskbar;
use common\assets\CommonAsset;
use backend\main\assets\BackendAsset;

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
    <main class="rows">
      <section class="row main">
        <?=$content?>
      </section>
      <section class="row menu">
        <?=Taskbar::widget([
          "attributes" => [
            "class" => "taskbar taskbar-default left top bottom"
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
                          "url" => "/page"
                        ],
                        [
                          "label" => "Create page",
                          "url" => "/page/new"
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
                      "url" => "/user"
                    ],
                    [
                      "label" => "Create user",
                      "url" => "/user/new"
                    ],
                  ]
                ],
                [
                  "label" => "Roles",
                  "items" => [
                    [
                      "label" => "Manage roles",
                      "url" => "/role"
                    ],
                    [
                      "label" => "Create role",
                      "url" => "/role/new"
                    ],
                  ]
                ]
              ],
            ],
          ]
        ])?>
      </section>
    </main>
    <footer></footer>
    <script></script>
    <?=$this->footer()?>
  </body>
</html>
