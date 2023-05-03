<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/user.php";
require_once "$root/lib/fetchTracks.php";
require_once "$root/lib/fileWrapper.php";

$uname = User::getUsername();

// checking prequists
if ( $uname == "anonymous" ){
  echo "403";
  http_response_code(401);
  die;
}
$userRoot = "$root/$userData/$uname";

$query = $_GET['query'];
$type = $_GET['type'];

$sResult = search($type,$query);
$fNames = [];
foreach ($sResult as $track){
  array_push($fNames,$track['filename']);
}
$search = File::open("$userRoot/search.fpl","w");
fwrite($search,implode("\n",$fNames));
fclose($search);
