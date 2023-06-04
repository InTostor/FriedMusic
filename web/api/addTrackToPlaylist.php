<?php
// CODE DOUBLING ALERT
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/user.php";
require_once "$root/lib/fileWrapper.php";
require_once "$root/lib/dev.php";
require_once "$ROOT/settings/config.php";

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

if (!str_ends_with($playlistG,".fpl")){
  echo "400";
  http_response_code(400);
  die;
}

$userRoot = "$userData/$uname";

// create user's directory if there is no
if ( !file_exists($userRoot) ){
  mkdir("$userData/$uname");
}
// create favourites file

$playlistFile = File::open("$userRoot/$playlistG","r");
$hFileSize = filesize("$userRoot/$playlistG");
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

$playlist = array_filter($playlist);

$playlistFile = File::open("$userRoot/$playlistG","w");
fwrite($playlistFile,implode("\n",$playlist));
fclose($playlistFile);
echo "OK";