<?php
namespace aug\web;
class Request implements RequestInterface{
  public static function handleRequest($uri){
    $default = \Aug::$app->web["default"];
    $defaultParts = explode("/",trim($default, "/"));
    $uriParts = explode("/",trim($uri, "/"));

    $params = [];

    $action = $defaultParts[count($defaultParts)-1];
    array_pop($defaultParts);
    $controller = $defaultParts[count($defaultParts)-1];
    array_pop($defaultParts);
    $path = implode("/",$defaultParts);


    // var_dump(\Aug::$app->routes, $uri); die;

    foreach(\Aug::$app->routes as $toMatch => $matchedRoute){
      $matchCount = 0;
      $matchedVar = $params;
      if($toMatch == $uri){
        // direct match
        $uri = $matchedRoute;
        break;
      } else {
        $matchParts = explode("/", trim($toMatch, "/"));
        if(count($matchParts) == count($uriParts)){
          for($i = 0; $i < count($uriParts); $i++){
            preg_match('/(<.*:.*>)/', $matchParts[$i], $matches);

            if(isset($matches[0])){
              $m = str_replace(["<", ">"], "", $matches[0]);
              $matchExplode = explode(":",$m);
              $var = $matchExplode[0];
              $reg = $matchExplode[1];
              preg_match("/$reg/", $uriParts[$i], $uriMatches);
              if(!empty($uriMatches)){
                $matchCount++;
                $matchedVar[$var] = $uriParts[$i];
              }
            } else if($uriParts[$i] == $matchParts[$i]){
              $matchCount++;
            }
          }
          if($matchCount == count($uriParts)){
            $params = $matchedVar;
            $uri = $matchedRoute;
            break;
          }
        }
      }
    }
    return [$uri, $params];
  }
}
