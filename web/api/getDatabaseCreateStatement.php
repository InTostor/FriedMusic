<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/dev.php";
require_once "$root/settings/config.php";

if (isset($_GET['typesonly'])){
  $sql = "DESCRIBE $trackMetadataTable";
  $answer = Database::executeStmt($sql);
  header("Content-Type: application/json");
  echo json_encode($answer);
}else{
  $sql = "show create table $trackMetadataTable";
  $answer = Database::executeStmt($sql);
  header("Content-Type: text/plain");
  echo $answer[0]['Create Table'];
}


