<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dev.php";
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/user.php";
require_once "$root/lib/fileWrapper.php";

$uname = User::getUsername();
$uroot = "$root/userdata/$uname/";

?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="/styles/98.css">
    <!-- <link rel="stylesheet" href="https://unpkg.com/xp.css" /> -->
    <!-- <link rel="stylesheet" href="https://unpkg.com/7.css"> -->
    <link rel="stylesheet" href="/styles/main.css">
  </head>
  <body>


<?=html("$root/resources/player.html");?>
<?=html("$root/resources/searcher.html");?>
<?=html("$root/resources/userPanel.html");?>

</body>




<script>
let currPlaylist = []

function reqPlayTrack(key,src,type){
  switch (type){
    case "search":
      currPlaylist = syncFetch("/userdata/<?=$uname?>/search.fpl").toString()
      currPlaylist = currPlaylist.split("\n")
      a.use("playlist",currPlaylist,key,"search")
      break
  
  }
  
  a.loadTrackIntoMusician()
}


a.use("radio")
a.loadTrackIntoMusician()

</script>

</html>