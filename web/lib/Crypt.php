<?php


class Cryptography{

  /**
  * $upass should be hash of the real password 
  */
  static function credentialsToToken($uname,$upass): string{
    $utoken = hash_pbkdf2(
      "sha256",
      $upass,
      $uname,
      10,
      45
    );
    return $utoken;
  }
}