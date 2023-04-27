<?php

class File{

  static function getAsArray($path){
    $f = fopen($path,"r");
    $arr = explode("\n",fread($f,filesize($path)));
    fclose($f);
    return $arr;
  }

}