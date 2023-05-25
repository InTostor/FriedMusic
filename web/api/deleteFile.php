<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dev.php";
require_once "$root/lib/user.php";

$allowedExtensions = ["fpl","fbl"];

$uname = User::getUsername();
if ($uname == "anonymous"){die();}
$uroot = "$root/userdata/$uname";

if (isset($_GET['file'])){
  $filename = basename($_GET['file']);
  $extension = pathinfo($_GET['file'])['extension'];
  if ( !in_array($extension,$allowedExtensions) ){
    echo 400;
    http_response_code(400);
    die();
  }
  try{
    unlink("$uroot/$filename");
  }catch(Exception){
    // do nothing
  }
}