<?php
$root = $_SERVER['DOCUMENT_ROOT'];

require_once "$root/lib/getUserPlaylists.php";
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/apiCooldown.php";


if ( User::getUsername() == "anonymous" ){
  $useLimit = true;
}else{
  $useLimit = false;
}


if (!isset($_GET['sql'])){
  header('Content-Type: text/plain');
  http_response_code(400);
  echo "400";
}


if (apiCooldown::checkCooldown(User::getUserID(),"selectDB")<=0){
  $sql = "select * from `fullmeta` ".$_GET['sql'];
  if ($useLimit){
    $sql = preg_replace('/limit\s*\d*/mi', "LIMIT 50", $sql);
  }
  $response = Database::executeUserSelect($sql);

  $columns = sizeof($response[0]);
  $rows = sizeof($response);

  $cooldown = floor($columns * $rows)/2000;

  apiCooldown::setCooldown(User::getUserID(),"selectDB",$cooldown);
  header('Content-Type: application/json');
  if (sizeof($response)!=0){
    echo json_encode($response);
  }else{
    header('Content-Type: text/plain');
    http_response_code(404);
  }
}else{
  header('Content-Type: text/plain');
  header('Retry-After: '.apiCooldown::checkCooldown(User::getUserID(),"selectDB"));
  http_response_code(429);
  echo "400";
}