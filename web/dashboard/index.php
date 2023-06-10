<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/Locale.php";
require_once "$root/lib/user.php";
require_once "$root/lib/dev.php";
require_once "$root/lib/fileWrapper.php";

$locale = new LocalString(User::getLaguage());
$uname = User::getUsername();

if ($uname == "anonymous"){
  header('Location: /login/');
}

function drawList($list,$listname){
  global $locale;
  if ($listname == "Playlists"){
    $type = "playlist";
  }else{
    $type = "blocklist";
  }

  echo "<table><tr><th>".$locale->get($listname)."</th><th>".$locale->get('edit')."</th><th>".$locale->get('delete')."</th></tr>";
  foreach ( $list as $playlistFilename ){
    echo "<tr>";
    echo "<td>$playlistFilename</td>";
    echo "<td><a href='/useractions/fileEditor.php?file=$playlistFilename'>editView</a></td>";
    echo "
    <td>
    <form target='void' method='get' action='/api/deleteFile.php'>
    <input type='hidden' name='file' value='$playlistFilename'>
    <button type='submit'>delete</button>
    </form>
    </td>
    ";
    echo "</tr>";
  }
  echo "<td>Create New?</td><td><a href='/useractions/fileEditor.php?type=$type'>create</a></td><td></td>";
  echo "</table>";
}




?>


<html lang="<?=$locale->locale?>">
<head>
  <title><?=$locale->get("DashboardPageTitle")?></title>
  <link rel="stylesheet" href="/styles/98.css">
  <link rel="stylesheet" href="/styles/main.css">
</head>
<body>
<iframe name="void" style="display: none;"></iframe>




<div class="window" style="width: 300px">
  <div class="title-bar">
    <div class="title-bar-text"><?=$locale->get('UserAccountWindowTitle')?></div>

  </div>
  <div class="window-body">
    Logged as: <?=$uname?>
    <button aria-label="Logout"><a href="/login/logout.php"> <?=$locale->get("Logout")?> </a> </button>
    <button aria-label="Go to player"><a href="/">Player</a></button>
  </div>
</div>



<div class="window" style="width: 300px">
  <div class="title-bar">
    <div class="title-bar-text"><?=$locale->get('Playlists')?></div>

  </div>
  <div class="window-body">
    <div class="sunken-panel">
      <?=drawList(User::getPlaylists($uname),"Playlists")?>
    </div>
  </div>
</div>

<div class="window" style="width: 300px">
  <div class="title-bar">
    <div class="title-bar-text"><?=$locale->get('Blocklists')?></div>

  </div>
  <div class="window-body">
    <div class="sunken-panel">
      <?=drawList(User::getBlocklists($uname),"Blocklists")?>
    </div>
  </div>
</div>

<div class="window" style="width: 300px">
  <div class="title-bar">
    <div class="title-bar-text"><?=$locale->get('LibraryStatsWindowTitle')?></div>

  </div>
  <div class="window-body">
    there will be statistics of the library
  </div>
</div>


</body>
</html>