<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/user.php";
require_once "$ROOT/settings/config.php";


function getPlaylists($user){
  global $userData;
  $ret = [];
  foreach (new DirectoryIterator("/$userData/$user/") as $file) {
    $filename = $file->getFilename();
    if($file->isDot()) continue;
    if (pathinfo($filename, PATHINFO_EXTENSION)=="fpl" ){
      array_push($ret, $filename);
    }
    
  }
  return $ret;
}
