<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/user.php";
require_once "$root/lib/getUserPlaylists.php";
require_once "$root/lib/track.php";
require_once "$root/lib/dev.php";

$uname = User::getUsername();

if ( $uname == "anonymous" ){
  echo "403";
  http_response_code(401);
  die;
}

// source
$rng = rand(1,100);
if ($rng<=54){
  $src = "history";
}elseif ($rng<=97){
  $src = "favourite";
}else{
  $src = "any";
}
// level
$rng = rand(1,10);
if ($rng<=4){
  $level = "track";
}elseif ($rng<=8){
  $level = "artist";
}else{
  $level = "genre";
}




echo $src."<br>";
echo $level."<br>";
$retTrack = "na";
switch ($src){
  case "history":
    $tracks = File::getAsArray($root."/userdata/$uname/history.fpl");
    $retTrack = secondIteration($tracks,$level);
    break;
  case "favourite":
    $tracks = File::getAsArray($root."/userdata/$uname/favourite.fpl");
    $retTrack = secondIteration($tracks,$level);
    break;
  case "anyF":
    // $track = Track::getSameBy()
}
echo "track: $retTrack";

function secondIteration($tracks,$level){
  switch ($level){
    case "track":
      return $tracks[rngw(1,sizeof($tracks))];
      break;
    case "artist":
      $artist = Track::getMetaData($tracks[rngw(2,sizeof($tracks))])['artist'];
      $tracks = Track::getSameBy("artist",$artist);
      if (!isset($tracks['filename'])){
        return $tracks[rngw(1,sizeof($tracks))];
      }else{
        return $tracks['filename'];
      }
      break;
    case "genre":
      $genre = Track::getMetaData($tracks[rngw(2,sizeof($tracks))])['genre'];
      $tracks = Track::getSameBy("genre",$genre,rand(0,1) == 1);
      if (!isset($tracks['filename'])){
        return $tracks[rngw(1,sizeof($tracks))];
      }else{
        return $tracks['filename'];
      }
      break;
  }
}

function rngw($min,$max){
  return clamp(rand($min,$max)-1,0,$max);
}

