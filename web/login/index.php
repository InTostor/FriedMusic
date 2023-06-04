<?php
ini_set('display_errors', '1');
$root = $_SERVER['DOCUMENT_ROOT'];

require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/user.php";
require_once "$root/settings/config.php";
require_once "$root/lib/dev.php";

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
    $error = "Wrong login or password or something broke";
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
    $error = "This user already registered";
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
    <div class="title-bar-text">Login to your account</div>

  </div>
    <div class="window-body" >

      <form action="" method="POST">
        <div class="field-row-stacked" style="width: 200px">
          <label for="login-username">Username</label>
          <input id="login-username" type="text" name="login-username" placeholder="Франсуа Стасье Жопьен">
        </div>
        <div class="field-row-stacked" style="width: 200px">
          <label for="login-password">Password</label>
          <input id="login-password" type="password" name="login-password" placeholder="Oralcumshot">
        </div>
        <section class="field-row" style="justify-content: flex-end">
          <?=$error?>
          <input type="submit" id="login-button"  name="login" value="Login">
        </section>

    </form>

  </div>
</div>


<div class="window register-form" style="width: max(50%,217px)">
  <div class="title-bar">
    <div class="title-bar-text">Register in the system</div>

  </div>
    <div class="window-body" >
      <form action="" method="POST">

        <div class="field-row-stacked" style="width: 200px">
          <label for="register-username">Username</label>
          <input id="register-username" type="text" name="register-username" placeholder="Франсуа Стасье Жопьен">
        </div>
        <div class="field-row-stacked" style="width: 200px">
          <label for="register-password">Password</label>
          <input id="register-password" type="password" name="register-password" placeholder="Oralcumshot">
        </div>
        <div class="field-row-stacked" style="width: 200px">
          <label for="register-gender">gender</label>
          
          <select id="register-gender" name="register-gender">
            <option selected>Genderfluid helisexual</option>
            <?php foreach ($genderList as $gender){echo "<option>$gender</option>";}?>
          </select>
        </div>
        <section class="field-row" style="justify-content: flex-end">
          <?=$error?>
          <input type="submit" id="register-button"  name="register" value="register">
        </section>

    </form>

  </div>
</div>





</body>
</html>