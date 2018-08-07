<?php
return [
  "web" => [
    "controllerClass" => "\\aug\\web\\Controller",
    "actionClass" => "\\aug\\web\\Action",
    "requestClass" => "\\aug\\web\\Request",
    "default" => "/site/home"
  ],
  "db" => [
    "connection" => [
      "dsn" => "mysql:dbname=testdb;host=localhost",
      "user" => "root",
      "pwd" => ""
    ],
  ],
  "sites" => [
    "backend.localhost" => "backend",
    "localhost" => "main"
  ]
];
