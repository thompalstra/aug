<?php
namespace aug\base;
interface WidgetInterface{
  public function init($options = []);
  public function run();
}
