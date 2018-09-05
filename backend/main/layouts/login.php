<?php

use aug\widgets\Nav;

use common\assets\CommonAsset;
use backend\main\assets\BackendAsset;

CommonAsset::register();
BackendAsset::register();
?>
<html>
  <head>
    <title><?=\Aug::$app->controller->title?></title>
    <link rel="stylesheet" href=""/>
    <?=$this->head()?>
  </head>
  <body>
    <main class="columns layout-login">
      <section class="column main">
        <?=$content?>
      </section>
    </main>
    <footer></footer>
    <script></script>
    <?=$this->footer()?>
  </body>
</html>
