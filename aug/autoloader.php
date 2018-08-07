<?php
spl_autoload_register(function($className){
  $root = dirname(__DIR__);
  $ds = DIRECTORY_SEPARATOR;
  $classPath = str_replace("/", $ds, str_replace("\\", $ds, $className));
  $fp = "{$root}{$ds}{$classPath}.php";
  if(file_exists($fp)){
    require_once($fp);
  }
});
