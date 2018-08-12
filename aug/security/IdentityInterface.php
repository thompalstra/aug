<?php
namespace aug\security;
interface IdentityInterface{
  public function login($user);
  public function logout();
  public function can($role);
  public function restore();
}
