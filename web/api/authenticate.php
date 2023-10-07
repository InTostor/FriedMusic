<?php


if (! isset($_SERVER['PHP_AUTH_USER'])  ) {
  header("WWW-Authenticate: Basic realm=\"Fried Music\"");
}


$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/user.php";
require_once "$root/settings/config.php";
require_once "$root/lib/dev.php";
require_once "$root/lib/Crypt.php";

$uname = $_SERVER['PHP_AUTH_USER'];
$upass = hash("md5",$_SERVER['PHP_AUTH_PW']);
$utoken = Cryptography::credentialsToToken($uname,$upass);


if ( Database::executeStmt("select count(*) from users where `username`= ? and `token`= ?","ss",[$uname,$utoken])[0]['count(*)'] >=1){
  User::rememberUser($uname,$upass);
  header('Content-Type: application/json');
  $returnArray = [
    "authUsername"=>$uname,
    "authToken"=>$upass,
  ];
  echo json_encode($returnArray);
}else{
  http_response_code(401);
  echo "401";
  die();
}



?>