<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/user.php";
require_once "$root/lib/fileWrapper.php";
require_once "$ROOT/settings/config.php";

$uname = User::getUsername();
$uroot = "$userData/$uname/";

if ( $uname == "anonymous" ){
  echo "403";
  http_response_code(401);
  die;
}
header('Content-Type: text/plain');

if( File::isInFile($_GET['track'],"$uroot/favourite.fpl") == 1){
echo "true";
}else{
  echo "false";
}