<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dev.php";


fout($_FILES);

fout($_FILES[0]);


?>
<form action="" method="post" enctype="multipart/form-data">
    Upload a File:
    <input type="file" name="the_file" id="fileToUpload">
    <input type="submit" name="submit" value="Start Upload">
</form>