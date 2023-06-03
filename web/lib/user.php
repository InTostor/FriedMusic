<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
$cookieTime = 157680000; // 5 years

class User{
  static function getUsername(){
    if ( isset($_GET['basicauth']) ){
      $uname = Self::getUsernameByMethod("basic");
    }else{
    $uname = Self::getUsernameByMethod("cookie");
    }
    return $uname;
  }
  static function getUsernameByMethod($authmethod = "cookie"){
    if ( $authmethod == "cookie" and isset($_COOKIE['who']) and isset($_COOKIE['what']) ){
      // by cookie
      $uname = $_COOKIE['who'];
      $upass = $_COOKIE['what'];
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
    global $root;
    mkdir("$root/userdata/$uname",);
  }

  static function rememberUser($uname,$upass){
  global $cookieTime;
  setcookie("who",$uname, time() +$cookieTime * 30,"/");
  setcookie("what",$upass, time() +$cookieTime * 30,"/");
  setcookie("slim_shady","chto_blya", time() +$cookieTime * 30,"/");
}


}