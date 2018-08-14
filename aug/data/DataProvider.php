<?php
namespace aug\data;
class DataProvider{

  protected $pagination = [
    "page" => 1,
    "pageSize" => 25
  ];

  public function __construct($options = []){
    foreach($options as $k => $v){
      $this->$k = $v;
    }

    // $query = clone $this->query;

    if($this->pagination["page"] == 1){
      $this->_offset = 0;
    } else {
      $this->_offset = $this->pagination["page"] * $this->pagination["pageSize"] -1;
    }
    $this->_limit = $this->_offset + $this->pagination["pageSize"];

    $this->_totalCount = $this->query->count();

    $this->query->limit($this->getPageSize());
    $this->query->offset($this->getOffset());

    $this->_currentCount = $this->query->count();
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
