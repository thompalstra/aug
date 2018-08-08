<?php
return [
  "/" => "site/home",
  "/contact" => "site/contact",
  "/blog" => "blog/index",
  "/blog/<slug:(.*)>" => "blog/view",
];
