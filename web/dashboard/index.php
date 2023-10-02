<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/Locale.php";
require_once "$root/lib/user.php";
require_once "$root/lib/dev.php";
require_once "$root/lib/fileWrapper.php";
require_once "$root/lib/Library.php";

$locale = new LocalString(User::getLaguage());
$uname = User::getUsername();

if ($uname == "anonymous"){
  header('Location: /login/');
}

function drawList($list,$listname){
  global $locale;
  if ($listname == "Playlists"){
    $type = "fpl";
  }else{
    $type = "fbl";
  }

  echo "<table><tr><th>".$locale->get($listname)."</th><th>".$locale->get('edit')."</th><th>".$locale->get('delete')."</th></tr>";
  foreach ( $list as $playlistFilename ){
    echo "<tr>";
    echo "<td>$playlistFilename</td>";
    echo "<td><button class='actionButton' ><a  href='/useractions/fileEditor.php?file=$playlistFilename'><img class='actionIcon' src='/resources/rename-2.png' alt='edit playlist'></a></button></td>";
    echo "
    <td>
    <form class='actionButtonContainer' target='void' method='get' action='/api/deleteFile.php'>
    <input type='hidden' name='file' value='$playlistFilename'>
    <button class='actionButton' type='submit'><img class='actionIcon' src='/resources/recycle_bin_empty-4.png' alt='delete'></button>
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
<div class="pageLimiter">
<iframe name="void" style="display: none;"></iframe>
<style>
.actionIcon{
  height: 22px;
  width: 22px;
  margin: 2px;
}
.actionButton{
  width:fit-content;
  height: fit-content;
  min-width: unset;
  padding: 2px;
  margin: auto 0px auto 0px;
}
.actionButtonContainer{
  display: flex;
  margin:0px;
}
.window{
  height:fit-content;
}
</style>

<div class="window" style="width: 300px">
  <div class="title-bar">
    <div class="title-bar-text"><?=$locale->get('UserAccountWindowTitle')?></div>
  </div>
  <div class="window-body">
    <div class="field-row-stacked">
      <button style="margin-top:auto" aria-label="Go to player"><a href="/"><?=$locale->get("ReturnToPlayerButton")?></a></button>
      <div class="field-row">
          Logged as: <?=$uname?>
        <button aria-label="Logout"><a href="/login/logout.php"> <?=$locale->get("Logout")?> </a> </button>
      </div>
      <div class="field-row-stacked">
        <form action="/api/setLanguage.php" method="get" target="void">
          <label for="langSelect" ><?=$locale->get("SelectLanguage")?></label> 
          <select name="lang" id="langSelect" aria-label="<?=$locale->get("SelectLanguage")?>">
            <?php
              foreach ( LocalString::getAvailableLocales() as $lang ){
                if ( $lang == $locale->locale ){
                  echo "<option selected>$lang</option>";
                }else{
                  echo "<option>$lang</option>";
                }                
              }
            ?>
          </select>
          <input type="submit" value="save">
        </form>
      </div>
    </div>
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
    <?=(sizeof(User::getPlaylists($uname))."/".$MAXALLOWEDFILES-3)?>
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
    <div class="sunken-panel">
    <table>
      <tr>
        <th>key</th>
        <th>value</th>
      </tr>
      <tr>
        <td><?=$locale->get("LibraryDatasize")?></td>
        <td><?=round(Library::getDatasize()/1073741824,2)?> GB</td>
      </tr>
      <tr>
        <td><?=$locale->get("LibraryTableSize")?></td>
        <td><?=((Library::getTableSize()/1024)." KB")?></td>
      </tr>
      <tr>
        <td><?=$locale->get("LibraryDuration")?></td>
        <td><?=gmdate("H:i:s", Library::getDuration())?></td>
      </tr>
      <tr>
        <td><?=$locale->get("LibraryTrackCount")?></td>
        <td><?=Library::getTrackCount()?></td>
      </tr>
      <tr>
        <td><?=$locale->get("LibraryUsersCount")?></td>
        <td><?=Library::getUsersCount()?></td>
      </tr>
    </table>
    </div>
  </div>
</div>

<div class="window" style="width: 300px">
  <div class="title-bar">
    <div class="title-bar-text"><?=$locale->get('System')?></div>

  </div>
  <div class="window-body">
    <?php
    fout(shell_exec("git -h"));
    ?>
  </div>
</div>

</div>
</body>
</html>
