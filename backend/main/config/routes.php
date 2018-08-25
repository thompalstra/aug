<?php
return [
  "/user" => "user/index",
  "/user/create" => "user/view",
  "/user/<id:(.*)>" => "user/view",
  "/page" => "page/index",
  "/page/create" => "page/view",
  "/page/<id:([0-9]+)>" => "page/view",
  "/role" => "role/index",
  "/role/create" => "role/view",
  "/role/<id:([0-9]+)>" => "role/view"
];
