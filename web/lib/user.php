<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";

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
  setcookie("who",$uname, time() +86400 * 30,"/");
  setcookie("what",$upass, time() +86400 * 30,"/");
  setcookie("slim_shady","chto_blya", time() +86400 * 30,"/");
}


}