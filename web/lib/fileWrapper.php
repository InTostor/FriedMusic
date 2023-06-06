<?php

class File{

  static function getAsArray($path){
    $f = File::open($path,"r");
    $fSize = filesize($path);
    if (!$fSize==0){
    $arr = explode("\n",fread($f,$fSize));
    }else{
      $arr = [""];
    }
    fclose($f);
    return $arr;
  }

  static function getAsString($path){
    if (file_exists($path)){
    $f = File::open($path,"r");
    $out = fread($f,filesize($path));
    fclose($f);
    return $out;
    }else{
      return "";
    }
  }

  static function arrayToFile($path,$array){
    // file_put_contents();
  }

  static function open($path,$mode="r"){
    if ( !file_exists($path) ){
      fclose(fopen($path,"x"));
    }
    return fopen($path,$mode);
  }

  static function addToLimited($path,$limit,$add){
    $fArr = File::getAsArray($path);
    array_push($fArr,$add);
    if ( sizeof($fArr) > $limit ){
      $fArr = array_slice($fArr,1,-1);
    }
    $f = File::open($path,"w");
    fwrite($f,implode("\n",$fArr));
  }
  static function isInFile($string,$path){
    $List = File::getAsArray($path);

    if ( in_array($string,$List) ){
      return true;
    }else{
      return false;
    }
  }

  static function openJson($path){
    return json_decode(File::getAsString($path), true);
  }
}