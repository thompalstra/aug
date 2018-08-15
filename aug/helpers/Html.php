<?php
namespace aug\helpers;
use aug\web\Request;
class Html{
  public static function createAttributes($options = []){
    $opt = [];
    foreach($options as $k => $v){
      if(is_array($v)){
        if($k == "style"){
          $style = self::createStyleAttributes($v);
          $opt[] = "{$k}='{$style}'";
        } else if($k == "class"){
          $opt[] = "{$k}='".implode(" ", $v)."'";
        } else {
          $json = self::createJsonAttributes($v);
          $opt[] = "{$k}='{$json}'";
        }
      } else {
        $opt[] = "{$k}='{$v}'";
      }
    }
    return implode(" ", $opt);
  }
  public static function mergeAttributes($original, $new){
    $out = [];
    foreach($new as $k => $v){

      if(is_array($v) && isset($original[$k])){
        $out[] = self::mergeAttributes($original[$k], $v);
      }

      // var_dump($k);
      // if(is_array($v) && isset($original[$k])){
      //   // $out[] = self::mergeAttributes($original[$k], $new[$k]);
      //   $out[] = $original[$k] + $new[$k];
      // } else if(!is_array($v)) {
      //   if(!isset($original[$k])){
      //     $out[$k] = $v;
      //   } else {
      //     $out[$k] = [$original[$k] + $v];
      //   }
      // }
    }
    return array_merge($original, $new);
  }
  public static function createStyleAttributes($options = []){
    $opt = [];
    foreach($options as $k => $v){
      $opt[] = "{$k}:{$v}";
    }
    return implode("; ", $opt);
  }
  public static function createJsonAttributes($options = []){
    return json_encode($options);
  }
  public static function openTag($tag, $options = []){
    $attr = self::createAttributes($options);
    return "<{$tag} {$attr}>";
  }
  public static function closeTag($tag){
    return "</{$tag}>";
  }
  public static function tag($tag, $options = [], $content = ""){
    $attr = self::createAttributes($options);
    return "<{$tag} {$attr}>{$content}</{$tag}>";
  }
  public static function input($attributes){
    return self::tag("input", $attributes);
  }
  public static function select($options, $value, $attributes){

    $out = [];
    foreach($options as $k => $v){
      $optionAttributes = [];
      $optionAttributes["value"] = $k;
      if($k == $value){
        $optionAttributes["selected"] = "";
      }
      $out[] = Html::tag("option", $optionAttributes, $v);
    }

    $out = implode("", $out);

    return self::tag("select", $attributes, $out);
  }
  public static function a($content, $url, $options = []){
    $url = Request::toUrl($url);
    $options["href"] = $url;
    return Html::tag("a", $options, $content);
  }
}
