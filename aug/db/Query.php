<?php
namespace aug\db;
class Query{

  protected $parts = [];
  protected $select;
  protected $from;
  protected $className;

  public function __construct($className = null){
    $this->className = $className;
  }

  public function select($select){
    $this->select = $select;
    return $this;
  }
  public function from($from){
    $this->from = $from;
    return $this;
  }
  public function limit($limit){
    $this->limit = $limit;
  }
  public function offset($offset){
    $this->offset = $offset;
  }
  public function where($where){
    $this->parts[] = [
      "WHERE" => $where
    ];
    return $this;
  }
  public function andWhere($andWhere){
    $this->parts[] = [
      "AND" => $andWhere
    ];
    return $this;
  }
  public function orWhere($orWhere){
    $this->parts[] = [
      "OR" => $orWhere
    ];
    return $this;
  }
  public function leftJoin($tableName, $on){
    $o = [];
    foreach($on as $k => $a){
      $o[] = "{$k} = ${a}";
    }
    $this->parts[] = [
      "LEFT JOIN" => "{$tableName} ON " . implode(" AND ", $o)
    ];
    return $this;
  }
  public function set($set){
    $this->parts[] = [
      "SET" => $set
    ];
    return $this;
  }
  public function one(){
    return $this->fetchOne($this->createCommand());
  }
  public function all(){
    return $this->fetchAll($this->createCommand());
  }
  public function exists(){
    return $this->fetchExists($this->createCommand());
  }
  public function count(){
    return $this->fetchCount($this->createCommand());
  }
  public function columns(){
    return $this->fetchColumns($this->createCommand());
  }
  public function fetchColumns($command){
    $sth = Connection::$dbh->prepare($command);
    $sth->execute();
    return $sth->fetchAll(\PDO::FETCH_ASSOC);
  }
  public function fetchAll($command){
    $sth = Connection::$dbh->prepare($command);
    $sth->execute();
    if($this->className){
      $sth->setFetchMode(\PDO::FETCH_CLASS, $this->className, [
        [],
        false
      ]);
    }
    return $sth->fetchAll();
  }
  public function fetchExists($command){
    $sth = Connection::$dbh->prepare($command);
    $sth->execute();
    return $sth->fetch() ? true : false;
  }
  public function fetchOne($command){
    $sth = Connection::$dbh->prepare($command);
    $sth->execute();
    if($this->className){
      $sth->setFetchMode(\PDO::FETCH_CLASS, $this->className, [
        [],
        false
      ]);
    }
    return $sth->fetch();
  }
  public function fetchCount($command){
    $sth = Connection::$dbh->prepare($command);
    $sth->execute();
    return $sth->rowCount();
  }
  public function insert($columns, $values){
    $className = $this->className;
    $tableName = $className::tableName();

    $columns = "{$tableName} (" . self::createColumns($columns) . ")";
    $values = "VALUES (" . self::createValues($values) . ")";

    $dbh = Connection::$dbh;
    $sth = $dbh->prepare("INSERT IGNORE INTO {$columns} {$values}");
    $sth->execute();
    if($sth->rowCount()){
      return $dbh->lastInsertId();
    }
    return false;
  }
  public function update($set, $where){
    $className = $this->className;
    $tableName = $className::tableName();

    $set = "SET " . self::createSet($set);
    $where = "WHERE (" . self::createWhere($where) . ")";

    $dbh = Connection::$dbh;
    $sth = $dbh->prepare("UPDATE {$tableName} {$set} {$where}");
    return $sth->execute();
  }
  public function createCommand(){
    $lines = [];
    $lines[] = "SELECT {$this->select}";
    $lines[] = "FROM {$this->from}";
    foreach($this->parts as $part){
      $type = key($part);
      switch($type){
        case "WHERE":
          $lines[] = "WHERE (" . self::createWhere($part[$type]) . ")";
        break;
        case "AND":
          $lines[] = "AND (" . self::createWhere($part[$type]) . ")";
        break;
        case "OR":
          $lines[] = "OR (" . self::createWhere($part[$type]) . ")";
        break;
        case "SET":
          $lines[] = "SET (" . self::createSet($part[$type]) . ")";
        break;
        case "LEFT JOIN":
          $lines[] = "LEFT JOIN " . $part[$type];
        break;
      }
    }

    if(!empty($this->limit)){
      $lines[] = "LIMIT {$this->limit}";
    }
    if(!empty($this->offset)){
      $lines[] = "OFFSET {$this->offset}";
    }

    return implode(" ", $lines);
  }
  public static function createValue($value){
    if(is_int($value)){
      return $value;
    } else if(is_string($value)){
      return '"'.$value.'"';
    } else if(is_array($value)){
      return implode(",",$value);
    } else if(is_bool($value)){
      return ($value) ? 1 : 0;
    } else if($value == NULL){
      return 'NULL';
    }
  }
  public static function createWhere($where){
    $glue;
    $lines = [];
    $glue = "AND";
    if(isset($where[0]) && is_string($where[0])){
      if(in_array(strtoupper($where[0]), ["AND", "OR"])){
        $glue = strtoupper($where[0]);
        array_shift($where);
      }
    }
    foreach($where as $parts){
      if(count($parts) === 1){
        $first = key($parts);
        $value = self::createValue($parts[$first]);
        if($parts[$first] === NULL){
          $lines[] = "{$first} IS {$value}";
        } else {
          $lines[] = "{$first}={$value}";
        }

      } else if(count($parts) === 3){
        if(strtoupper($parts[0]) === "LIKE"){
          $lines[] = self::like($parts);
        } else if(strtoupper($parts[0]) === "IN"){
          $lines[] = self::in($parts);
        }
      } else {
      }
    }
    return implode(" $glue ", $lines);
  }
  public static function createSet($set) {
    $lines = [];
    foreach($set as $parts){
      if(count($parts) == 1){
        $first = key($parts);
        $value = self::createValue($parts[$first]);
        $lines[] = "{$first}={$value}";
      }
    }
    return implode(", ", $lines);
  }
  public static function createColumns($columns) {
    $lines = [];
    foreach($columns as $column){
      $lines[] = $column;
    }
    return implode(", ", $lines);
  }
  public static function createValues($set) {
    $lines = [];
    foreach($set as $part){
      $lines[] = self::createValue($part);
    }
    return implode(", ", $lines);
  }
  public static function like($parts){
    $value = self::createValue($parts[2]);
    return "{$parts[1]} {$parts[0]} {$value}";
  }
  public static function in($parts){
    $value = self::createValue($parts[2]);
    return "{$parts[1]} {$parts[0]} ({$value})";
  }
  public function getClassName(){
    return $this->className;
  }
}
