<?php
return [
  "web" => [
    "controllerClass" => "\\aug\\web\\Controller",
    "actionClass" => "\\aug\\web\\Action",
    "requestClass" => "\\aug\\web\\Request",
    "viewClass" => "\\aug\\web\\View",
    "identityClass" => "\\common\\models\\User",
    "default" => "site/index",
    "layout" => "main",
  ],
  "db" => [
    "connection" => [
      "dsn" => "mysql:dbname=testdb;host=localhost",
      "user" => "root",
      "pwd" => ""
    ],
  ],
  "sites" => [
    "localhost" => "main",
    "aug" => "main",
  ]
];
