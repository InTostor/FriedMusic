<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/user.php";
require_once "$ROOT/settings/config.php";

global $maxHistoryFileLines;

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

$userRoot = User::getDirectory($uname);

// create user's directory if there is no
User::makeDirectory($uname);

File::addToLimited("$userRoot/history.fpl",$maxHistoryFileLines,$track);
echo "OK";