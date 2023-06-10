<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/Locale.php";
require_once "$root/lib/user.php";
require_once "$root/lib/dev.php";
require_once "$root/lib/fileWrapper.php";
require_once "$root/lib/Locale.php";

$locale = new LocalString(User::getLaguage());

if (!isset($_GET['file'])){
  http_response_code(400);
  echo "400";
  die();
}

if (!isset($_GET['file'])){
  http_response_code(401);
  echo "401";
  die();
}



$uname = User::getUsername();
$uroot = User::getDirectory($uname);

$requestedFilename = $_GET['file'];

if (file_exists("$uroot/$requestedFilename")){
  $requestedFileContent = File::getAsString("$uroot/$requestedFilename");

}else{
  $requestedFileContent = "";
}



// get length of longest string in file to set the width of textarea
$textAreaColumns = max(array_map('mb_strlen', explode("\n",$requestedFileContent),["UTF-8"])) + 2 ;
$textAreaRows = sizeof(explode("\n",$requestedFileContent)) + 3 ;
?>

<html lang="<?=$locale->locale?>">

<head>
  <title><?=$locale->get("editing")?> <?=$requestedFilename?></title>
</head>

<link rel="stylesheet" href="/styles/98.css">

<style>


</style>

<iframe name="void" style="display: none;"></iframe>

<label for="plaintextFileContent"><?=$locale->get("Editing")?> <?=$requestedFilename?> </label> 

<form id="fileEditorTextarea" method="post" action="/api/uploadFile.php" target="void" class="field-row-stacked" style="width:fit-content">
  <input type="hidden" name="plaintextFileName" value="<?=$requestedFilename?>" >
  <input type="submit" value="Upload content of textarea">
  <textarea id="plaintextFileContent" name="plaintextFileContent" id="filePlaintext" cols="<?=$textAreaColumns?>" rows="<?=$textAreaRows?>" spellcheck="false" ><?=$requestedFileContent?></textarea>
  
</form>




</html>