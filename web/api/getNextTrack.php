<?php
// this is most complex script in whole project. At some point it will require
// elastic search for fuzzy track generating, more lists of track (white/black)
// and maybe machine learning algohritms for most accurate track generation.

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/user.php";
require_once "$root/lib/track.php";
require_once "$root/lib/dev.php";
require_once "$root/lib/fileWrapper.php";

$uname = User::getUsername();
$uroot = "$root/userdata/$uname/";

if ( $uname == "anonymous" ){
  echo "403";
  http_response_code(401);
  die;
}
// header('Content-Type: text/plain');

$oldRolls = File::getAsArray("$uroot/oldRoll.frf");

// track roulette
reroll:
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
if ($rng<=2){
  $level = "track";
}elseif ($rng<=6){
  $level = "artist";
}else{
  $level = "genre";
}


$level = "genre";


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

$retTrack = $retTrack['filename'];

if ($retTrack == "na" or in_array($retTrack,$oldRolls)){
goto reroll;
}
File::addToLimited("$uroot/oldRoll.frf",10,$retTrack);
echo($retTrack);

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
      $tracks = Track::getSameBy("genre",$genre,true);
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

