<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include( dirname(__FILE__) . DIRECTORY_SEPARATOR . "aug" . DIRECTORY_SEPARATOR . "Application.php");
return Application::init()
  ->prepare()
  ->run();
