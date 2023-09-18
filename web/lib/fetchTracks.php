<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
function search($type,$q,$limit = 100){
  $q="%$q%";
  if ($limit != 0 ){
    $limit = "LIMIT $limit";
  }else{
    $limit = "";
  }
  switch (strtolower($type)){
    case "album":
      $sql = "SELECT * FROM `fullmeta` where album like ? $limit";
      break;
    case "artist":
      $sql = "SELECT * FROM `fullmeta` where artist like ? $limit";
      break;
    case "genre":
      $sql = "SELECT * FROM `fullmeta` where genre like ? $limit";
      break;
    default:
      $sql = "SELECT * FROM `fullmeta` where filename like ? or artist like ? or album like ? or title like ? $limit";
      return Database::executeStmt($sql,"ssss",[$q,$q,$q,$q]);
  }
  
  return Database::executeStmt($sql,"s",[$q]);

}