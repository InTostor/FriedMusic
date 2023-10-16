<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/user.php";
require_once "$root/lib/getUserPlaylists.php";

$uname = User::getUsername();

header('Content-Type: text/plain');



if ( $uname == "anonymous" ){
  echo "403";
  http_response_code(403);
  die;
}

echo (implode("\n",User::getBlocklists($uname)));