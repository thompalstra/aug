<?php
namespace aug\db;
class Model extends \aug\db\Base{
  protected $_attributes = [];
  protected $_oldAttributes = [];
  public function __construct($params = [], $isNewRecord = true){
    $this->isNewRecord = $isNewRecord;
    foreach( $this->getColumns() as $column ){
      $name = $column["column_name"];
      $type = $column["column_type"];
      if(!property_exists($this, $name)){
        $this->$name = null;
      }
      if($this->$name == "NULL"){
        $this->$name = NULL;
      }
      $this->_attributes[$name] = &$this->$name;
      $this->_oldAttributes[$name] = $this->$name;
    }
  }

  public function __get($name){
    $fn = "get" . str_replace(" ", "",ucwords(str_replace("_", " ",$name)));
    if(method_exists($this, $fn)){
        return call_user_func_array([$this, $fn], []);
    }
    return $this->$name;
  }

  public static function find(){
    return (new Query(get_called_class()))
      ->select("*")
      ->from(get_called_class()::tableName());
  }
  public static function getColumns(){
    return (new Query(get_called_class()))
      ->select("column_name, column_type")
      ->from("information_schema.columns")
      ->where([
        ["table_schema" => \aug\db\Connection::$connection["dbname"]],
        ["table_name" => get_called_class()::tableName()]
      ])
      ->columns();
  }
  public function save(){
    if($this->isNewRecord){
      return $this->insertRecord();
    } else {
      return $this->updateRecord();
    }
  }
  public function insertRecord(){
    $columns = [];
    $values = [];

    foreach($this->_attributes as $key => $value){
      $columns[] = $key;
      if($value == NULL){
        $value = "NULL";
      }
      $values[] = $value;
    }

    return (new Query(get_called_class()))
      ->insert($columns, $values);
  }
  public function updateRecord(){
    $set = [];
    $where = [];

    foreach($this->_attributes as $key => $value){
      $set[] = [$key => $value];
      $where[] = [$key => $this->_oldAttributes[$key]];
    }

    return (new Query(get_called_class()))
      ->update($set, $where);
  }
}
