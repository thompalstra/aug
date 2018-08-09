<?php
namespace common\models;



class CmsUser extends \aug\db\Model{
  public static function tableName(){
    return "cms_user";
  }
  public function getUserAccount(){
    return CmsUserAccount::find()
      ->where([
        ["user_id"=>$this->id]
      ])->one();
  }
  public function getRoles(){
    return CmsRole::find()
      ->leftJoin("cms_user_role", ['cms_role.id' => 'cms_user_role.role_id'])
      ->where([
        ["cms_user_role.user_id" => $this->id],
        ["cms_user_role.is_enabled" => 1]
      ])->all();
  }
}
