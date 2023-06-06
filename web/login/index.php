<?php
ini_set('display_errors', '1');
$root = $_SERVER['DOCUMENT_ROOT'];

require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/user.php";
require_once "$root/settings/config.php";
require_once "$root/lib/dev.php";
require_once "$root/lib/Locale.php";

$error = "";
$isWeb = !isset($_GET['client']);

if(User::getUsername() != "anonymous"){
  if ($isWeb){
    header("location: /");
  }else{
    echo "ok";
    die();
  }
}

$locale = new LocalString(User::getLaguage());

if (isset($_POST['login-username'])){
  $uname = $_POST['login-username'];
  $upass = hash('md5',$_POST['login-password']);
  $utoken = hash_pbkdf2(
    "sha256",
    $upass,
    $uname,
    10,
    45
  );

  if ( Database::executeStmt("select count(*) from users where `username`= ? and `token`= ?","ss",[$uname,$utoken])[0]['count(*)'] >=1){
    User::rememberUser($uname,$upass);
    if ($isWeb){
      header("location: /");
    }else{
      echo "ok";
    }
  }else{
    $error = $locale->get("LoginFailedWrongCredentials");
    if(!$isWeb){echo "failed";}
  }

}elseif (isset($_POST['register-username'])){
  $uname = $_POST['register-username'];
  //  check if username is free
  if ( Database::executeStmt("select count(*) from users where username=?","s",[$uname])[0]['count(*)'] =='0'){
    $upass = hash('md5',$_POST['register-password']);
    $utoken = hash_pbkdf2(
      "sha256",
      $upass,
      $uname,
      10,
      45
    );
    $ugender = $_POST['register-gender'];
    Database::executeStmt("insert into users (`username`,`token`,`gender`) values (?,?,?)","sss",[$uname,$utoken,$ugender]);
    User::rememberUser($uname,$upass);
    User::makeDirectory($uname);
    if ($isWeb){
      header("location: /");
    }else{
      echo "ok";
    }
  }else{
    $error = $locale->get("RegisterFailedExists");
    if(!$isWeb){echo "failed";}
  }

}

if(!$isWeb){
  die();
}
$genderList = file("$root/data/genders.txt");

if(User::getUsername() != "anonymous"){header("location: /");}
// don't show html if page is requested by standalone client

?>


<DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
  <link rel="stylesheet"href="/styles/98.css">
  <title>Login into account</title>
</head>

<body>

<div class="window login-form" style="width: max(50%,217px)">
  <div class="title-bar">
    <div class="title-bar-text"><?=$locale->get("LoginWindowTitle")?></div>

  </div>
    <div class="window-body" >

      <form action="" method="POST">
        <div class="field-row-stacked" style="width: 200px">
          <label for="login-username"><?=$locale->get("Username")?></label>
          <input id="login-username" type="text" name="login-username" placeholder="Франсуа Стасье Жопьен">
        </div>
        <div class="field-row-stacked" style="width: 200px">
          <label for="login-password"><?=$locale->get("Password")?></label>
          <input id="login-password" type="password" name="login-password" placeholder="Oralcumshot">
        </div>
        <section class="field-row" style="justify-content: flex-end">
          <?=$error?>
          <input type="submit" id="login-button"  name="login" value="<?=$locale->get("Login")?>">
        </section>

    </form>

  </div>
</div>


<div class="window register-form" style="width: max(50%,217px)">
  <div class="title-bar">
    <div class="title-bar-text"><?=$locale->get("RegisterWindowTitle")?></div>

  </div>
    <div class="window-body" >
      <form action="" method="POST">

        <div class="field-row-stacked" style="width: 200px">
          <label for="register-username"><?=$locale->get("Username")?></label>
          <input id="register-username" type="text" name="register-username" placeholder="Франсуа Стасье Жопьен">
        </div>
        <div class="field-row-stacked" style="width: 200px">
          <label for="register-password"><?=$locale->get("Password")?></label>
          <input id="register-password" type="password" name="register-password" placeholder="Oralcumshot">
        </div>
        <div class="field-row-stacked" style="width: 200px">
          <label for="register-gender"><?=$locale->get("Gender")?></label>
          
          <select id="register-gender" name="register-gender">
            <option selected>Genderfluid helisexual</option>
            <?php foreach ($genderList as $gender){echo "<option>$gender</option>";}?>
          </select>
        </div>
        <section class="field-row" style="justify-content: flex-end">
          <?=$error?>
          <input type="submit" id="register-button"  name="register" value="<?=$locale->get("Register")?>">
        </section>

    </form>

  </div>
</div>





</body>
</html>