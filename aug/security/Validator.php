<?php
namespace aug\security;
use aug\helpers\ClassHelper;
class Validator{
  public static function validate($model, $rules){
    foreach($rules as $rule){
      self::validateRule($model, $rule);
    }
    return !$model->hasErrors();
  }
  public static function validateRule($model, $rule){
    $validator = "as $rule[1]";
    foreach($rule[0] as $attribute){
      $fn = ClassHelper::toCamelCase($validator);
      if(method_exists(get_called_class(), $fn)){
        call_user_func_array([get_called_class(), $fn], [$model, $attribute, $rule]);
      } else if(method_exists($model, $fn)){
        call_user_func_array([$model, $fn], [$model, $attribute, $rule]);
      } else {
        echo "No such validator: {$fn}";
      }
    }
  }

  public static function asRequired($model, $attribute, $rule){
    $value = $model->$attribute;
    if(empty($value)){
      if(isset($rule["skipOnEmpty"]) && $rule["skipOnEmpty"] == true){
        return;
      }
      $model->addError($attribute, "{$attribute} is required.");
    }
  }
  public static function asString($model, $attribute, $rule){
    $value = $model->$attribute;
    if(empty($value)){
      if(isset($rule["skipOnEmpty"]) && $rule["skipOnEmpty"] == true){
        return;
      }
    }
    if(isset($rule["min"])){
      $min = $rule["min"];
      if(strlen($value) < $min){
        $model->addError($attribute, "{$attribute} cannot be shorter than {$min}");
      }
    }
    if(isset($rule["max"])){
      $max = $rule["max"];
      if(strlen($value) > $max){
        $model->addError($attribute, "{$attribute} cannot be longer than {$max}");
      }
    }
  }
  public static function asBoolean($model, $attribute, $rule){
    $model->$attribute = (bool) $model->$attribute;
  }
}
