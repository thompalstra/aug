<?php
namespace aug\db;
class Record extends \aug\base\Model{
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
  public function beforeSave(){return true;}
  public function afterSave(){}
  public function save($validate = true){
    if($validate && !$this->validate()){
      return;
    }
    if($this->beforeSave()){
      if($this->isNewRecord){
        if($this->insertRecord()){
          $this->afterSave();
          return true;
        }
        return false;
      } else {
        if($this->updateRecord()){
          $this->afterSave();
          return true;
        }
        return false;
      }
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
    $id = (new Query(get_called_class()))
      ->insert($columns, $values);

    if($id !== false){
      $model = self::find()->where([
        ['id'=>$id]
      ])->one();
      foreach($model as $k => $v){
        $this->$k = $v;
      }
      return true;
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
