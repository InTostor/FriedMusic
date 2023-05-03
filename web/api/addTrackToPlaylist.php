<?php
// CODE DOUBLING ALERT
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/user.php";
require_once "$root/lib/fileWrapper.php";


$uname = User::getUsername();

// checking prequists
if ( $uname == "anonymous" ){
  echo "403";
  http_response_code(401);
  die;
}
if ( !isset($_GET['track']) and !isset($_GET['playlist']) ){
  echo "400";
  http_response_code(400);
  die;
}
$track = $_GET['track'];
$playlistG = $_GET['playlist'];

$userRoot = "$root/userdata/$uname";

// create user's directory if there is no
if ( !file_exists($userRoot) ){
  mkdir("$root/userdata/$uname");
}
// create favourites file

$playlistFile = File::open("$userRoot/$playlistG.fpl","r");
$hFileSize = filesize("$userRoot/$playlistG.fpl");
if ($hFileSize==0){
  $playlist = array("");
}else{
  $playlist = explode("\n",fread($playlistFile,$hFileSize));
}
fclose($playlistFile);

if (isset($_GET['remove'])){
  foreach (array_keys($playlist, $track) as $key) {
    unset($playlist[$key]);
}
}else{
  array_push($playlist,$track);
}


$playlistFile = File::open("$userRoot/$playlistG.fpl","w");
fwrite($playlistFile,implode("\n",$playlist));
fclose($playlistFile);
echo "OK";