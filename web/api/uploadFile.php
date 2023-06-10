<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dev.php";
require_once "$root/lib/user.php";
require_once "$ROOT/settings/config.php";


$uname = User::getUsername();
if ($uname == "anonymous"){
  http_response_code(401);
  echo "401";
  die();
}

$uroot = User::getDirectory($uname);


if (isset($_FILES['file'])){
  $file = $_FILES['file'];

  $extension = pathinfo($file['name'])['extension'];

  // File check section. Checks are sorted from lightest to hardest (for computing)

  // check extension
  if (!in_array($extension,$allowedFileExtensions)){
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
  if ( count(scandir($uroot))-2 >= $MAXALLOWEDFILES ){
    echo "400";
    http_response_code(400);
    die();
  }

  // finally move file to user's directory
  try{
    unlink($uroot."/".$file['name']);
  }catch(Exception){
  // do nothing
  }


  copy($file['tmp_name'],$uroot."/".$file['name']);
  unlink($file['tmp_name']);
  echo "ok";
}elseif ( isset($_POST['plaintextFileContent']) and isset($_POST['plaintextFileName']) ){
  $filename = $_POST['plaintextFileName'];
  $fileContent = $_POST['plaintextFileContent'];
  $fileNewPath = "$uroot/$filename";
  
  // check extension
  $extension = pathinfo($filename)['extension'];
  if (!in_array($extension,$allowedFileExtensions)){
    echo "400";
    http_response_code(400);
    die();
  }

  File::stringToFile($fileNewPath,$fileContent);
  echo "ok";
}


?>
