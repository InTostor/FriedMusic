<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/dev.php";
require_once "$root/settings/config.php";


$sql = "show create table $trackMetadataTable";

$answer = Database::executeStmt($sql);
echo $answer[0]['Create Table'];