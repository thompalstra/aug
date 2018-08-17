<?php
namespace aug\base;
use aug\db\Query;
use aug\security\Validator;
class Model extends Base{
  protected $_errors = [];

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

}
