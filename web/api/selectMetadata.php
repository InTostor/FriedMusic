<?php
$root = $_SERVER['DOCUMENT_ROOT'];

require_once "$root/lib/getUserPlaylists.php";
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/apiCooldown.php";


if ( User::getUsername() == "anonymous" ){
  header('Content-Type: text/plain');
  echo "403";
  http_response_code(403);
  die;
}


if (!isset($_GET['sql'])){
  header('Content-Type: text/plain');
  http_response_code(400);
  echo "400";
}


if (apiCooldown::checkCooldown(User::getUserID(),"selectDB")<=0){
  $sql = "select * from `fullmeta` ".$_GET['sql'];
  $response = Database::executeUserSelect($sql);
  apiCooldown::setCooldown(User::getUserID(),"selectDB",floor(sizeof($response)/200));
  header('Content-Type: application/json');
  echo json_encode($response);
}else{
  header('Content-Type: text/plain');
  header('Retry-After: '.apiCooldown::checkCooldown(User::getUserID(),"selectDB"));
  http_response_code(429);
  echo "400";
}