<?php
namespace aug\security;
class Security{
  public static function passwordVerify($password, $hash){
    return password_verify($password, $hash);
  }
}
