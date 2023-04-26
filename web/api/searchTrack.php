<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
require_once "$root/settings/config.php";
require_once "$root/lib/renderMusicTable.php";
require_once "$root/lib/fetchTracks.php";


if (isset($_GET['type']) and isset($_GET['query'])){
  $type = $_GET['type'];
  $musicList = search($type,$_GET['query']);
}
header('Content-Type: application/json');
print_r(json_encode($musicList));