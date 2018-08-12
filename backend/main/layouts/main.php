<?php

use aug\widgets\Nav;

use common\assets\CommonAsset;

CommonAsset::register();
?>
<html>
  <header>
    <title><?=\Aug::$app->controller->title?></title>
    <link rel="stylesheet" href=""/>
    <?=$this->head()?>
  </header>
  <body>
    <main class="columns">
      <section class="column sidebar">
        <?=Nav::widget([
          "attributes" => [
            "class" => "nav nav-sidebar left top bottom"
          ],
          "items" => [
            [
              "label" => "Home",
              "url" => "/"
            ],
            [
              "label" => "CMS",
              "items" => [
                [
                  "label" => "Users",
                  "items" => [
                    [
                      "label" => "Create User",
                      "url" => "/user/new"
                    ],
                    [
                      "label" => "View users",
                      "url" => "/user"
                    ],
                    [
                      "label" => "Roles",
                      "url" => "/roles"
                    ]
                  ]
                ]
              ]
            ]
          ]
        ])?>
      </section>
      <section class="column main">
        <?=$content?>
      </section>
    </main>
    <footer></footer>
    <script></script>
    <?=$this->footer()?>
  </body>
</html>