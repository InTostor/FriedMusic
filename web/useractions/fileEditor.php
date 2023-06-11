<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/Locale.php";
require_once "$root/lib/user.php";
require_once "$root/lib/dev.php";
require_once "$root/lib/fileWrapper.php";
require_once "$root/settings/config.php";

$locale = new LocalString(User::getLaguage());

if ( !isset($_GET['file']) and isset($_GET['type']) ){

  $type = $_GET['type'];

  if ($type == "fpl"){
  echo "
  <form method='get' action=''>
  <input type='hidden'name='type'value='$type'>
  <input name='file' type='text' placeholder='enter filename'>
  <input type='submit'>
  ";
  }else{
    echo "
    <form method='get' action=''>
    <input type='hidden'name='type'value='$type'>
    <select name='file'>
      <option value='artists'>artists</option>
      <option value='genres'> genres</option>
      <option value='tracks'> tracks</option>
    </select>
    <input type='submit'>
    "; 
  }
  die();
}elseif (isset($_GET['file'])){

}elseif ( !isset($_GET['type']) ){
  http_response_code(400);
  echo "400";
  die();
}




$uname = User::getUsername();
$uroot = User::getDirectory($uname);

$requestedFilename = $_GET['file'];


// if no extension, try to get extension from $_GET['type']
if ( isset($_GET['type']) and !isset(pathinfo($requestedFilename)['extension'])){
  $extension = $_GET['type'];
  $requestedFilename = "$requestedFilename.$extension";
}elseif ( isset(pathinfo($requestedFilename)['extension']) ){
  $extension = pathinfo($requestedFilename)['extension'];
}elseif ( isset($_GET['file']) and isset($_GET['type']) ) {

}else{
  http_response_code(400);
  echo "400";
  die();
}





if ( !in_array($extension,$allowedFileExtensions)){
  echo "wrong file format";
  die();
}



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
  <link rel="stylesheet" href="/styles/98.css">
</head>

<body>


<iframe name="void" style="display: none;"></iframe>



<div class="window" style="width: fit-content">
  <div class="title-bar">
    <div class="title-bar-text"><label for="plaintextFileContent"><?=$locale->get("Editing")?> <?=$requestedFilename?> </label> </div>

  </div>
  <div class="window-body">
    <button><a href="/dashboard/"><?=$locale->get("Return")?></a></button>
    <form id="fileEditorTextarea" method="post" action="/api/uploadFile.php" target="void" class="field-row-stacked" style="width:fit-content">
      <input type="hidden" name="plaintextFileName" value="<?=$requestedFilename?>" >
      <input type="submit" value="Upload content of textarea">
      <textarea id="plaintextFileContent" name="plaintextFileContent" id="filePlaintext" cols="<?=$textAreaColumns?>" rows="<?=$textAreaRows?>" spellcheck="false" ><?=$requestedFileContent?></textarea>

    </form>
  </div>
</div>


</body>
</html>


