<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/user.php";


function getPlaylists($user){
  $root = $_SERVER['DOCUMENT_ROOT'];
  $ret = [];
  foreach (new DirectoryIterator("$root/userdata/$user/") as $file) {
    $filename = $file->getFilename();
    if($file->isDot()) continue;
    if (pathinfo($filename, PATHINFO_EXTENSION)=="fpl" ){
      array_push($ret, $filename);
    }
    
  }
  return $ret;
}
