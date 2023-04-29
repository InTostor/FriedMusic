<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";

class Track{
  static function getMetaData($filename){
    $ret = Database::executeStmt("select * from fullmeta where filename = ?","s",[$filename]);
    if (isset($ret[0])){
      return $ret[0];
    }else{
      return $ret;
    }
  }
  static function getSameBy($column,$as,$fuzzy=false){
    if ($fuzzy){
      $ret = Database::executeStmt("select * from fullmeta where $column like ?","s",["%$as%"]);
    }else{
      $ret = Database::executeStmt("select * from fullmeta where $column = ?","s",[$as]);
    }
    if (isset($ret[0])){
      return $ret[0];
    }else{
      return $ret;
    }

  }

}