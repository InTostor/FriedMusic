<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/settings/config.php";
if ( isset($_GET['lang']) ){
  setcookie("lang", $_GET['lang'], time() +$cookieTime * 30,"/");
}
echo "ok";