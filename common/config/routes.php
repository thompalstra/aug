<?php
return [
  "/" => "site/home"
  "/blog" => "blog/index",
  "/blog/<slug:(.*)>" => "blog/view", // /blog/het-heetste-nieuws
  "<slug:(.*)>" => "/page/view" // /test/best
];
