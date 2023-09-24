<?php
$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once "$ROOT/settings/config.php";
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/user.php";

class apiCooldown{
  static function setCooldown($user,$apiMethod,$time){
    $cooldownTime = time() + $time;
    Database::executeStmt("replace into apicooldown values (?,?,?)","sss",[$user,$apiMethod,$cooldownTime]);
  }

  /**
   * returns time seconds before cooldown reset. Negative values means that cooldown has passed
   */
  static function checkCooldown($userID,$apiMethod){
    $response = Database::executeStmt("select cooldown_till from apicooldown where id = ? and api_method = ?","ss",[$userID,$apiMethod]);
    // if no entry => no cooldown
    
    if (sizeof($response) == 0){
      return -1;
    }else{
      // reset cooldown
      if ($response[0]['cooldown_till'] - time() < 0){
        Database::executeStmt("delete from apicooldown where id = ? limit 1","s",[$userID]);
      }
      return $response[0]['cooldown_till'] - time();
    }
  }
}