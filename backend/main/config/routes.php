<?php
return [
  "/users" => "users/index",
  "/users/create" => "users/view",
  "/users/<id:(.*)>" => "users/view",
  "/users/delete/<id:(.*)>" => "users/delete",
  "/pages" => "pages/index",
  "/pages/create" => "pages/view",
  "/pages/<id:([0-9]+)>" => "pages/view",
  "/pages/delete/<id:(.*)>" => "pages/delete",
  "/roles" => "roles/index",
  "/roles/create" => "roles/view",
  "/roles/<id:([0-9]+)>" => "roles/view",
  "/roles/delete/<id:(.*)>" => "roles/delete",
  "/sites" => "sites/index",
  "/sites/create" => "sites/view",
  "/sites/<id:([0-9]+)>" => "sites/view",
  "/sites/delete/<id:(.*)>" => "sites/delete",
];
