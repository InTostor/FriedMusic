<?php


if (! (isset($_SERVER['PHP_AUTH_USER']) & isset($_SERVER['PHP_AUTH_PW'])) ) {
  header("WWW-Authenticate: Basic realm=\"Fried Music\"");
  http_response_code(401);
  echo "401";
  die();
}


$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/user.php";
require_once "$root/settings/config.php";
require_once "$root/lib/dev.php";

if ( Database::executeStmt("select count(*) from users where `username`= ? and `token`= ?","ss",[$uname,$utoken])[0]['count(*)'] >=1){
  User::rememberUser($uname,$upass);
}else{
  http_response_code(401);
  echo "401";
  die();
}


// header('Content-Type: application/json');
?>