<?php
use aug\widgets\Nav;
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
            (!\Aug::$app->user->isGuest) ? [
              "label" => "Me (" . \Aug::$app->user->username . ")",
              "items" => [
                [
                  "label" => "Logout",
                  "url" => "/logout"
                ]
              ]
            ] : [],
            [
              "label" => "",
              "attributes" => [
                "class" => "separator"
              ]
            ],
            [
              "label" => "CMS",
              "items" => [
                (\Aug::$app->user->can("userCreate")) ? [
                  "label" => "Create User",
                  "url" => "/user/new"
                ] : [],
                (\Aug::$app->user->can("userView")) ? [
                  "label" => "View users",
                  "url" => "/user"
                ] : [],
                (\Aug::$app->user->can("roleView")) ? [
                  "label" => "Roles",
                  "url" => "/roles"
                ] : []
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
