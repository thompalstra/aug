<?php
return [
  "/" => "site/index",
  "/contact" => "site/contact",
  "/blog" => "blog/index",
  "/blog/<slug:(.*)>" => "blog/view",
];
