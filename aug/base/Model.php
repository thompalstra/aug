<?php
namespace aug\base;
use aug\db\Query;
use aug\security\Validator;
class Model extends Base{
  protected $_attributes = [];
  protected $_oldAttributes = [];
  protected $_errors = [];
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
  public static function find(){
    $className = get_called_class();
    return (new Query($className))
      ->select("*")
      ->from($className::tableName());
  }
  public static function findOne($where = []){
    $className = get_called_class();
    return (new Query(get_called_class()))
      ->select("*")
      ->from($className::tableName())
      ->where($where)
      ->one();
  }
  public static function getColumns(){
    $className =  get_called_class();
    return (new Query(get_called_class()))
      ->select("column_name, column_type")
      ->from("information_schema.columns")
      ->where([
        ["table_schema" => \aug\db\Connection::$connection["dbname"]],
        ["table_name" =>$className::tableName()]
      ])
      ->columns();
  }
  public function addError($attr, $message){
    if(!isset($this->_errors[$attr])){
      $this->_errors[$attr] = [];
    }
    $this->_errors[$attr][] = $message;
  }
  public function hasErrors(){
    return !empty($this->_errors);
  }
  public function getErrors($attribute = null){
    if(!empty($attribute)){
      if(isset($this->_errors[$attribute])){
        return $this->_errors[$attribute];
      } else {
        return [];
      }
    }
    return $this->_errors;
  }
  public function validate(){
    if(method_exists($this, "rules")){
      Validator::validate($this, $this->rules());
    }
    return !$this->hasErrors();
  }
  public function save($validate = true){
    if($validate && !$this->validate()){
      return;
    }
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
      if(is_bool($value)){
        $value = ($value) ? 1 : 0;
      }
      if($value == NULL){
        $value = "NULL";
      }
      $values[] = $value;
    }
    $result = (new Query(get_called_class()))
      ->insert($columns, $values);


    // var_dump($result); die;
    if($result !== null){
      return self::find()
        ->where([
          ["id"=>$result]
        ])->one();
    }
    return false;
  }
  public function updateRecord(){
    $set = [];
    $where = [];
    foreach($this->_attributes as $key => $value){
      $set[] = [$key => $value];
      if($this->_oldAttributes[$key] != null){
        $where[] = [$key => $this->_oldAttributes[$key]];
      }
    }
    return (new Query(get_called_class()))
      ->update($set, $where);
  }
}
