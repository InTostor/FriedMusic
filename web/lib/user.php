<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
$cookieTime = 157680000; // 5 years

class User{
  static function getUsername($authmethod = "cookie"){
    if ( $authmethod == "cookie" ){
      if ( isset($_COOKIE['who']) ){
        $uname = $_COOKIE['who'];
        $upass = $_COOKIE['what'];
        $utoken = hash_pbkdf2(
          "sha256",
          $upass,
          $uname,
          10,
          45
        );
        $res = Database::executeStmt("select count(*) from users where `username`= ? and `token`= ?","ss",[$uname,$utoken])[0]['count(*)'] >=1 ? $uname : "anonymous";
        return $res;
      }else{
        return "anonymous";
      }

    }
  }

  static function rememberUser($uname,$upass){
  global $cookieTime;
  setcookie("who",$uname, time() +$cookieTime * 30,"/");
  setcookie("what",$upass, time() +$cookieTime * 30,"/");
  setcookie("slim_shady","chto_blya", time() +$cookieTime * 30,"/");
}


}