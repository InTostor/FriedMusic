<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
require_once "$root/settings/config.php";

function search($type,$q,$limit = 100){
  global $trackMetadataTable;
  $q="%$q%";
  if ($limit != 0 ){
    $limit = "LIMIT $limit";
  }else{
    $limit = "";
  }
  switch (strtolower($type)){
    case "album":
      $sql = "SELECT * FROM `$trackMetadataTable` where album like ? $limit";
      break;
    case "artist":
      $sql = "SELECT * FROM `$trackMetadataTable` where artist like ? $limit";
      break;
    case "genre":
      $sql = "SELECT * FROM `$trackMetadataTable` where genre like ? $limit";
      break;
    default:
      $sql = "SELECT * FROM `$trackMetadataTable` where filename like ? or artist like ? or album like ? or title like ? order by album, tracknumber, artist $limit";
      return Database::executeStmt($sql,"ssss",[$q,$q,$q,$q]);
  }
  
  return Database::executeStmt($sql,"s",[$q]);

}