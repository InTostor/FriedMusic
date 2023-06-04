<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/user.php";
require_once "$ROOT/settings/config.php";

$maxHistoryLength = 100;

$uname = User::getUsername();

// checking prequists
if ( $uname == "anonymous" ){
  echo "403";
  http_response_code(401);
  die;
}
if ( !isset($_GET['track']) ){
  echo "400";
  http_response_code(400);
  die;
}
$track = $_GET['track'];

$userRoot = "$userData/$uname";

// create user's directory if there is no
if ( !file_exists($userRoot) ){
  mkdir("$userData/$uname");
}

$historyFile = fopen("$userRoot/history.fpl","r");
$hFileSize = filesize("$userRoot/history.fpl");
if ($hFileSize==0){
  $history = array("");
}else{
  $history = explode("\n",fread($historyFile,$hFileSize));
}
fclose($historyFile);

array_push($history,$track);
// remove limit exceeding element
if ( sizeof($history) > $maxHistoryLength ){
  $history = array_slice($history,1,-1);
}
// if ( $history[0]==""){$history = array_slice($history,1,-1);}
$historyFile = fopen("$userRoot/history.fpl","w");
fwrite($historyFile,implode("\n",$history));
fclose($historyFile);
echo "OK";