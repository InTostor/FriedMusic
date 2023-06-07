<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/user.php";

class Track{
  static function getMetaData($filename){
    $ret = Database::executeStmt("select * from fullmeta where filename = ?","s",[$filename]);
    if (isset($ret[0])){
      $ret =  $ret[0];
    }else{
      $ret =  $ret;
    }
    return $ret;
  }
  static function getSameBy($column,$as,$fuzzy=false){
    if ($fuzzy){
      $ret = Database::executeStmt("select * from fullmeta where $column like ?","s",["%$as%"]);
    }else{
      $ret = Database::executeStmt("select * from fullmeta where $column = ?","s",[$as]);
    }

    return $ret;

  }

  static function getSameByExclude($column,$as,$fuzzy=false, array $excludeArtists = array(), array $excludeGenres = array()){
    if ($excludeArtists == array()){$excludeArtists = array("");}
    if ($excludeGenres == array()){$excludeGenres = array("");}
    $artistString = array_map(function($val){return "'$val'";}, $excludeArtists);
    $genreString = array_map(function($val){return "'$val'";}, $excludeGenres);

    $artistString = implode(", ",$artistString);
    $genreString = implode(", ",$genreString);

    $baseSqlStart = "select * from fullmeta where $column";
    $baseSqlEnd = " and artist not in ($artistString) and genre not in ($genreString)";

    if ($fuzzy){
      $ret = Database::executeStmt("$baseSqlStart like ? $baseSqlEnd","s",["%$as%"]);
    }else{
      $ret = Database::executeStmt("$baseSqlStart = ? $baseSqlEnd","s",[$as]);
    }
    
    return $ret;
  }


}