<?php
require_once "$root/lib/Locale.php";
require_once "$root/lib/user.php";
require_once "$root/lib/dev.php";
require_once "$root/lib/fileWrapper.php";
require_once "$root/lib/dbWrapper.php";

class Library{

  static function getTrackCount(){
    return Database::executeStmt("SELECT max(id) FROM fullmeta")[0]['max(id)'];
  }

  static function getGenres(){
    $out = [];
    foreach (Database::executeStmt("SELECT genre FROM fullmeta GROUP BY genre") as $line){
      array_push($out, $line['genre']);
    }
    return $out;
  }
  static function getCountOftracksOfGenres(){
    $sql = "SELECT genre, count(filename) FROM fullmeta GROUP BY genre ORDER BY count(filename) DESC";
    $out = [];
    foreach (Database::executeStmt($sql) as $line){
      array_push($out,['genre'=>$line['genre'],'count'=>$line['count(filename)']]);
    }
    return $out;
  }

  static function getCountOftracksOfArtists(){
    $sql = "SELECT artist, count(filename) FROM fullmeta GROUP BY artist ORDER BY count(filename) DESC";
    $out = [];
    foreach (Database::executeStmt($sql) as $line){
      array_push($out,['artist'=>$line['artist'],'count'=>$line['count(filename)']]);
    }
    return $out;
  }

  static function getDatasize(){
    $sql = "SELECT sum(filesize) from fullmeta";
    return Database::executeStmt($sql)[0]['sum(filesize)'];
  }

  static function getDuration(){
    $sql = "SELECT sum(duration) from fullmeta";
    return Database::executeStmt($sql)[0]['sum(duration)'];
  }

  static function getListOfArtists(){
    $sql = "SELECT artist from fullmeta GROUP BY artist";
    $out = [];
    foreach (Database::executeStmt($sql) as $line){
      array_push($out,$line['artist']);
    }
    return $out; 
  }

}