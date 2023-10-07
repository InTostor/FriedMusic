<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
require_once "$root/settings/config.php";
require_once "$root/lib/Locale.php";

class User{

  static function getUsername(){
    if ( isset($_GET['basicauth']) ){
      $uname = Self::getUsernameByMethod("basic");
    }else{
      $uname = Self::getUsernameByMethod("cookie");
    }
    return $uname;
  }

  static function getUserID(){
    return User::convertUsernameToId(User::getUsername())['id'];
  }

  static function convertIdToUsername($id){
    try{
      return Database::executeStmt("select username from users where id = ?","s",[$id])[0];
    }catch (Exception){
      return Null;
    }
  }
  static function convertUsernameToId($username){
    try{
      return Database::executeStmt("select id from users where username = ?","s",[$username])[0];
    }catch (Exception){
      return Null;
    }
  }

  static function getUsernameByMethod($authmethod = "cookie"){
    if ( $authmethod == "cookie" and isset($_COOKIE['authUsername']) and isset($_COOKIE['authToken']) ){
      // by cookie
      $uname = $_COOKIE['authUsername'];
      $upass = $_COOKIE['authToken'];
      $utoken = Self::credsToToken($uname,$upass);
      $res = Database::executeStmt("select count(*) from users where `username`= ? and `token`= ?","ss",[$uname,$utoken])[0]['count(*)'] >=1 ? $uname : "anonymous";
      return $res;
    }elseif ( $authmethod == "basic" and isset($_SERVER['PHP_AUTH_USER']) and isset($_SERVER['PHP_AUTH_PW']) ){
      // by basic HTTP auth
      $uname = $_SERVER['PHP_AUTH_USER'];
      $upass = $_SERVER['PHP_AUTH_PW'];
      $utoken = Self::credsToToken($uname,$upass);
      $res = Database::executeStmt("select count(*) from users where `username`= ? and `token`= ?","ss",[$uname,$utoken])[0]['count(*)'] >=1 ? $uname : "anonymous";
      return $res;
    }elseif ($authmethod == "certificate"){
      // not implemented
      return "anonymous";
    }else{
      return "anonymous";
    }
  }

  static function credsToToken($uname,$upass){
    return hash_pbkdf2(
      "sha256",
      $upass,
      $uname,
      10,
      45
    );
  }
  
  static function makeDirectory($uname){
    global $userData;
    if (!file_exists("$userData/$uname")){
      mkdir("$userData/$uname");
    }
  }

  static function getDirectory($uname){
    global $userData;
    return "$userData/$uname/";
  }

  static function rememberUser($uname,$upass){
    global $cookieTime;
    setcookie("authUsername",$uname, time() +$cookieTime * 30,"/");
    setcookie("authToken",$upass, time() +$cookieTime * 30,"/");
    setcookie("slim_shady","chto_blya", time() +$cookieTime * 30,"/");
  }
  
  static function getLaguage(){
    if (isset($_COOKIE['lang'])){
      return $_COOKIE['lang'];
    }else{
      $out =  User::getPrefferedLaguages();
      $out = key($out);
      return $out;
    }
  }

  static function getPrefferedLaguages() {
    $http_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    $available_languages = LocalString::getAvailableLocales();

    $available_languages = array_flip($available_languages);

    $langs = array();

    preg_match_all('~([\w-]+)(?:[^,\d]+([\d.]+))?~', strtolower($http_accept_language), $matches, PREG_SET_ORDER);

    foreach($matches as $match) {
      list($a, $b) = explode('-', $match[1]) + array('', '');
      $value = isset($match[2]) ? (float) $match[2] : 1.0;
      if(isset($available_languages[$match[1]])) {
        $langs[$match[1]] = $value;
        continue;
      }
      if(isset($available_languages[$a])) {
        $langs[$a] = $value - 0.1;
      }

    }
    arsort($langs);

    return $langs;
}

  static function getBannedStrings($uname,$fname){
    $bannedStringsFilePath = User::getDirectory($uname)."/$fname";
    $out = array();
    if (!file_exists($bannedStringsFilePath)){
      return $out;
    }else{
      $out = File::getAsArray($bannedStringsFilePath);
      return $out;
    }
  }

  static function getPlaylists($uname){
    $uroot = User::getDirectory($uname);
    $ret = [];
    foreach (new DirectoryIterator($uroot) as $file) {
      $filename = $file->getFilename();
      if($file->isDot()) continue;
      if (pathinfo($filename, PATHINFO_EXTENSION)=="fpl" ){
        array_push($ret, $filename);
      }
    }
    return $ret;
  }

  static function getBlocklists($uname){
    $uroot = User::getDirectory($uname);
    $ret = [];
    foreach (new DirectoryIterator($uroot) as $file) {
      $filename = $file->getFilename();
      if($file->isDot()) continue;
      if (pathinfo($filename, PATHINFO_EXTENSION)=="fbl" ){
        array_push($ret, $filename);
      }
    }
    return $ret;
  }


}