<?php

use aug\widgets\Nav;

use common\assets\CommonAsset;

CommonAsset::register();
?>
<html>
  <head>
    <title><?=\Aug::$app->controller->title?></title>
    <link rel="stylesheet" href=""/>
    <?=$this->head()?>
  </head>
  <body>
    <main class="columns">
      <section class="column main">
        <?=$content?>
      </section>
    </main>
    <footer></footer>
    <script></script>
    <?=$this->footer()?>
  </body>
</html>
