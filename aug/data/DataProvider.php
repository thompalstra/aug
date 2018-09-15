<?php
namespace aug\data;
class DataProvider{

  protected $_offset = 0;
  protected $_totalCount = 0;

  protected $pagination = [
    "page" => 1,
    "pageSize" => 25
  ];

  public function __construct($options = []){
    foreach($options as $k => $v){
      $this->$k = $v;
    }

    $this->_totalCount = $this->query->count();

    if($this->pagination["page"] == 1){
      $this->_offset = 0;
    } else {
      $this->_offset = $this->pagination["page"] * $this->pagination["pageSize"] - $this->pagination["pageSize"];
    }

    if($this->_offset < 0){
      $this->_offset = 0;
    } else if($this->_offset >= $this->_totalCount){
      $this->_offset -= $this->getPageSize();
    }

    $this->_limit = $this->_offset + $this->pagination["pageSize"];
    $this->_totalCount = $this->query->count();

    $this->query->limit($this->getPageSize());
    $this->query->offset($this->getOffset());
  }
  public function getModels(){
    return $this->query->all();
  }
  public function getPage(){
    return $this->pagination["page"];
  }
  public function getPageSize(){
    return $this->pagination["pageSize"];
  }
  public function getLimit(){
    return $this->_limit;
  }
  public function getOffset(){
    return $this->_offset;
  }
  public function getTotalCount(){
    return $this->_totalCount;
  }
  public function getCurrentCount(){
    return $this->_currentCount;
  }
}
