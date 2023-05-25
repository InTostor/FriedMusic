<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dev.php";
require_once "$root/lib/user.php";

$allowedExtensions = ["fpl","fbl"];
$MAXUPLOADSIZE = 5242880; // 2^16 * mean filename size * 2 bytes unicode character size
$MAXALLOWEDFILES = 64;

$uname = User::getUsername();
if ($uname == "anonymous"){die();}

$newPath = "$root/userdata/$uname/";



if (isset($_FILES['file'])){
$file = $_FILES['file'];

$extension = pathinfo($file['name'])['extension'];

// File check section. Checks are sorted from lightest to hardest (for computing)

// check extension
if (!in_array($extension,$allowedExtensions)){
  echo "400";
  http_response_code(400);
  die();
}

// check filesize
if ( filesize($file["tmp_name"]) >= $MAXUPLOADSIZE ){
  echo "413";
  http_response_code(413);
  die();
}

// check amount of files in user's directory
if ( count(scandir($newPath))-2 >= $MAXALLOWEDFILES ){
  echo "400";
  http_response_code(400);
  die();
}

// finally move file to user's directory
try{
  unlink($newPath."/".$file['name']);
}catch(Exception){
// do nothing
}


copy($file['tmp_name'],$newPath."/".$file['name']);
unlink($file['tmp_name']);
echo "ok";
}


?>
