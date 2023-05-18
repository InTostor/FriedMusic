<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dev.php";
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/user.php";
require_once "$root/lib/fileWrapper.php";

$uname = User::getUsername();
$uroot = "$root/userdata/$uname/";

if ($uname == "anonymous"){
  header('Location: /login/');
}

?>
<!DOCTYPE html>
<html>
  <head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="/styles/98.css">
    <!-- <link rel="stylesheet" href="https://unpkg.com/xp.css" /> -->
    <!-- <link rel="stylesheet" href="https://unpkg.com/7.css"> -->
    <link rel="stylesheet" href="/styles/main.css">

  </head>
  <body>
  <script src = "/js/lib.js"></script>

  <div class="pageLimiter">


<?=html("$root/resources/userPanel.html");?>
<?=html("$root/resources/player.html");?>
<?=html("$root/resources/searcher.html");?>
<script src="/js/userpanel.js"></script>
  </div>

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