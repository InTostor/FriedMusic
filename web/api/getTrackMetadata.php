<?php
$root = $_SERVER['DOCUMENT_ROOT'];

require_once "$root/lib/getUserPlaylists.php";


header('Content-Type: text/plain');


echo $_GET;