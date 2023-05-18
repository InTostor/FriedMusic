<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
function search($type,$q){
  $q="%$q%";
  switch ($type){
    default:
      $sql = "SELECT * FROM `fullmeta` where filename like ? or artist like ? or album like ? or title like ? LIMIT 100";
      return Database::executeStmt($sql,"ssss",[$q,$q,$q,$q]);
    case "album":
      $sql = "SELECT * FROM `fullmeta` where album like ? LIMIT 100";
      break;
    case "artist":
      $sql = "SELECT * FROM `fullmeta` where artist like ? LIMIT 100";
      break;
    case "genre":
      $sql = "SELECT * FROM `fullmeta` where genre like ? LIMIT 100";
      break; 
  }
  
  return Database::executeStmt($sql,"s",[$q]);

}