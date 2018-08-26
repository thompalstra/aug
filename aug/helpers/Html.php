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
    $out = $original;
    foreach($new as $k => $v){
      if(isset($out[$k])){
        if(is_array($out[$k])){
          $out[$k] = array_merge_recursive($out[$k], $new[$k]);
        }
      } else {
        $out[$k] = $new[$k];
      }
    }
    return $out;
  }
  public static function createStyleAttributes($options = []){
    $opt = [];
    foreach($options as $k => $v){
      $opt[] = "{$k}:{$v}";
    }
    return implode("; ", $opt);
  }
  public static function createJsonAttributes($options = []){
    return htmlentities(json_encode($options), ENT_QUOTES, "UTF-8");
  }
  public static function openTag($tag, $options = []){
    $attr = self::createAttributes($options);
    return "<{$tag} {$attr}>";
  }
  public static function closeTag($tag){
    return "</{$tag}>";
  }
  public static function tag($tag, $content, $options = []){
    $attr = self::createAttributes($options);
    return "<{$tag} {$attr}>{$content}</{$tag}>";
  }
  public static function input($attributes){
    return self::tag("input", "", $attributes);
  }
  public static function select($options, $value, $attributes){
    $out = [];
    foreach($options as $k => $v){
      $optionAttributes = [];
      $optionAttributes["value"] = $k;
      if(is_array($value) && isset($value[$k])){
        $optionAttributes["selected"] = "";
      } else if(!is_array($value) && $k == $value){
        $optionAttributes["selected"] = "";
      }
      $out[] = Html::tag("option", $v, $optionAttributes);
    }

    $out = implode("", $out);

    return self::tag("select", $out, $attributes);
  }
  public static function a($content, $url, $options = []){
    $url = Request::toUrl($url);
    $options["href"] = $url;
    return Html::tag("a",$content, $options);
  }
  public static function button($content, $options = []){
    return Html::tag("button", $content, $options);
  }
}
