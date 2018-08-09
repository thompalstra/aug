<?php
namespace aug\db;
class Connection{
  protected static $con;
  protected static $user;
  protected static $pass;
  public static $connection = [];
  /**
   * [this function should always be called statically with the correct params to globalize connection data]
   * @param  [string] $con  [connection string]
   * @param  [string] $user [username for mysql]
   * @param  [string] $pass [password for mysql]
   */
  public static function initialize($con, $user, $pass){
    self::$con = $con;
    self::$user = $user;
    self::$pass = $pass;

    $data = [];
    foreach(explode(";", $con) as $line){
      $parts = explode("=", $line);
      self::$connection[$parts[0]] = $parts[1];
    }
  }
  /**
   * [createDbh description]
   * @return [PDO] [a PDO instance based on the current initialized settings]
   */
  public static function createDbh(){
    return new \PDO(self::$con, self::$user, self::$pass);
  }
  /**
   * [getCon description]
   * @return [string] [connection string]
   */
  public static function getCon(){
    return self::$con;
  }
}
