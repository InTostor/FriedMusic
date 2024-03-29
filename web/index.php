<?php
$root = $_SERVER['DOCUMENT_ROOT'];
// require_once "$root/lib/dev.php";
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/user.php";
require_once "$root/lib/fileWrapper.php";
require_once "$root/lib/Locale.php";

$uname = User::getUsername();
$uroot = "$root/userdata/$uname/";

if ($uname == "anonymous"){
  header('Location: /login/');
}

$locale = new LocalString(User::getLaguage());

?>

<!DOCTYPE html>
<html lang="<?=$locale->locale?>">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="/styles/98.css">
    <!-- <link rel="stylesheet" href="https://unpkg.com/xp.css" /> -->
    <!-- <link rel="stylesheet" href="https://unpkg.com/7.css"> -->
    <link rel="stylesheet" href="/styles/main.css">
    <noscript>
      Javascript should be enabled for player to be functional. If you can't, use static version instead. <a href="/noscript/">Go to static version</a>
    </noscript>

  </head>
  <body>
  <script src = "/js/lib.js"></script>

  <div class="pageLimiter">
    <?php include "$root/resources/userPanel.php";?>
    <?php include "$root/resources/player.php";?>
    <?php include "$root/resources/searcher.php";?>

    <script src="/js/userpanel.js"></script>
  </div>

</body>









<script>

let currPlaylist = []

function reqPlayTrack(key,src,type){
  switch (type){
    case "search":
      srcref = "/userdata/<?=$uname?>/search.fpl"
      currPlaylist = syncFetch(srcref).toString()
      currPlaylist = currPlaylist.split("\n")
      reqPlayList('/userdata/<?=$uname?>/search.fpl')
      a.use("playlist",currPlaylist,key,"search",srcref)
      break
  
  }
  
  a.loadTrackIntoMusician()
  a.play()
}


if (getObjectFromCookie("player") == null){
a.use("radio")
a.loadTrackIntoMusician()

}else{
  console.log("FFF")
  let [loop,shuffle,srcType,src,tracknumber] = getObjectFromCookie("player")
  a=null
  a = new AudioPlayer(loop,shuffle,srcType,src,tracknumber)
  // this is player update
  a.next()
  a.prev()
}

// patch for mobile devices
if (isMobile()){
a.setVolume(100)
}else{
  let cookieVol =  getCookie('volume')
  if (cookieVol != ""){
    a.setVolume(cookieVol)
  }
}

</script>

<style>
.pageLimiter{
  flex-wrap: wrap;
  max-height:100vh;
  max-width:100vw;
  width:100vw;
  display:flex;
  margin:0px;
  padding:0px;
}
.window{
  margin:10px
}
@media (max-width:600px){
  .window{
    margin:0px;
    width: calc(100% - 6px);
    max-width: calc(100% - 6px);
  }
  *{
    font-family: unset;
    font-size:12px;
  }
  div[class*="Holder"] {
  width: 100%;
  }
  .userpanelPlaylists{
    width:100%;
    display: flex;
    height:fit-content;
    overflow-x: unset;
  }
  .pageLimiter{
    justify-content: flex-start;
  }
  div[class*='Container']{
    width: 100%;
  }
  /* player fix */
  .playerTrackActionButton{
    width: 48px;
    height:48px;
  }
  .playerTrackActionIco{
    height:32px;
  }
  input[type="range"]::-moz-range-track{
    height:4px;
  }
  input[type="range"]::-moz-range-thumb{
    scale: 2 2;
  }
}

.userpanelHolder{
  width:fit-content;

  float: right;
}

div[class*='Holder']{
  height: fit-content;
}

</style>

</html>