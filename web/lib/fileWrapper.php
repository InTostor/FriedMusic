<?php

class File{

  static function getAsArray($path){
    $f = fopen($path,"r");
    $arr = explode("\n",fread($f,filesize($path)));
    fclose($f);
    return $arr;
  }
  static function open($path,$mode="r"){
    if ( !file_exists($path) ){
      fclose(fopen($path,"x"));
    }
    return fopen($path,$mode);
  }

}