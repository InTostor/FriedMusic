<?php
$root = $_SERVER['DOCUMENT_ROOT'];

require_once "$root/lib/getUserPlaylists.php";
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/apiCooldown.php";
require_once "$root/settings/config.php";
require_once "$root/lib/dev.php";

$useLimit = false;
if ( User::getUsername() == "anonymous" ){
  $useLimit = true;
  $cooledDown = false;
}else{
  $useLimit = false;
  $cooledDown = apiCooldown::checkCooldown(User::getUserID(),"selectDB")>0;
}

if (!isset($_GET['sql']) and !isset($_POST['sql'])){
  header('Content-Type: text/plain');
  http_response_code(400);
  echo "400";
  die();
}
if (isset($_POST['sql'])){
  $sqlRequested = $_POST['sql'];
}else{
  $sqlRequested = $_GET['sql'];
}

if (isset($_GET['what'])){
  $what = $_GET['what'];
}else{
  $what = "*";
}

if (!$cooledDown){
  $sql = "select $what from `$trackMetadataTable` ".$sqlRequested;
  if ($useLimit){
    $sql = preg_replace('/limit\s*\d*/mi', "LIMIT 50", $sql);
  }
  $response = Database::executeUserSelect($sql);
  if (sizeof(($response))==0){
    header('Content-Type: text/plain');
    http_response_code(404);
    echo "404";
    die();
  }

  $columns = sizeof($response[0]);
  $rows = sizeof($response);

  $cooldown = floor($columns * $rows)/2000;

  apiCooldown::setCooldown(User::getUserID(),"selectDB",$cooldown);
  header('Content-Type: application/json');
  if (sizeof($response)!=0){
    echo json_encode($response, JSON_NUMERIC_CHECK);
  }else{
    header('Content-Type: text/plain');
    http_response_code(404);
  }
}else{
  header('Content-Type: text/plain');
  header('Retry-After: '.apiCooldown::checkCooldown(User::getUserID(),"selectDB"));
  http_response_code(429);
  echo "429";
}