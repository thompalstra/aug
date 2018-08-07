<?php
namespace aug\web;
class Request implements RequestInterface{
  public static function handleRequest($requestUri){
    return $requestUri;
  }
}
