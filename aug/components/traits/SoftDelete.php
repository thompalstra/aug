<?php
namespace aug\components\traits;
trait SoftDelete{
  function delete(){
    $this->is_deleted = 1;
    $this->save(false);
    return true;
  }
}
