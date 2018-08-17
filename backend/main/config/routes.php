<?php
return [
  "/user/<userId:(.*)>" => "user/view",
  "/user" => "user/index",
  "/page" => "page/index",
  "/page/<id:([0-9]+)>" => "page/view"
];
