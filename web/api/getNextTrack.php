<?php
// this is most complex script in whole project. At some point it will require
// elastic search for fuzzy track generating, more lists of track (white/black)
// and maybe machine learning algohritms for most accurate track generation.
// Achtung! this script should be re-written because at the moment, it is using 'goto' 
// and somewhere it can cause ass pain

error_reporting(1);

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
require_once "$root/lib/user.php";
require_once "$root/lib/track.php";
require_once "$root/lib/dev.php";
require_once "$root/lib/fileWrapper.php";
require_once "$ROOT/settings/config.php";

$uname = User::getUsername();
$uroot = "$userData/$uname/";

if ( $uname == "anonymous" ){
  echo "403";
  http_response_code(401);
  die;
}

if ( isset($_GET['h']) and isset($_GET['r']) and isset($_GET['t']) and isset($_GET['a']) ){
  $historyChance = $_GET['h']/($_GET['h'] + $_GET['r'])*100;
  $randomChance = $_GET['r']/($_GET['h'] + $_GET['r'])*100;
  $trackChance = $_GET['t']/($_GET['t'] + $_GET['a'])*100;
  $artistChance = $_GET['a']/($_GET['t'] + $_GET['a'])*100;
}else{
  $historyChance = 54;
  // favourite chance is calculated as it is in middle of range
  $randomChance = 0;
  
  $trackChance = 20;
  $artistChance = 40;
}


header('Content-Type: text/plain');

$oldRolls = File::getAsArray("$uroot/oldRoll.frf");

// track roulette
reroll:
// source
$rng = rand(1,100);
if ($rng<=$historyChance){
  $src = "history";
}elseif ($rng>$historyChance and $rng<100-$randomChance){
  $src = "favourite";
}else{
  $src = "any";
}
// level
$rng = rand(1,100);
if ($rng<=$trackChance){
  $level = "track";
}elseif ($rng<=100-$artistChance){
  $level = "artist";
}else{
  $level = "genre";
}


$retTrack = "na";
switch ($src){
  case "history":
    try{
      $tracks = array_filter(File::getAsArray("$uroot/history.fpl"));
    }catch(Exception){
      goto reroll;
    }
    
    $retTrack = secondIteration($tracks,$level);
    break;
  case "favourite":
    try{
      $tracks = array_filter(File::getAsArray("$uroot/favourite.fpl"));
    }catch(Exception){
      goto reroll;
    }
    
    $retTrack = secondIteration($tracks,$level);
    break;
  case "anyF":
    // $track = Track::getSameBy()
}


if ( is_array($retTrack) ){
  $retTrack = $retTrack['filename'];
}


if ($retTrack == "na" or $retTrack == "" or in_array($retTrack,$oldRolls)){
goto reroll;
}
File::addToLimited("$uroot/oldRoll.frf",10,$retTrack);
echo($retTrack);


function secondIteration($tracks,$level){
  reroll2:
  if ($level=="artist" or $level=="genre"){
    global $uname;
    $bannedArtists = User::getBannedStrings($uname,"artists.fbl");
    $bannedGenres = User::getBannedStrings($uname,"genres.fbl");
    $bannedTracks = User::getBannedStrings($uname,"tracks.fbl");
  }

  switch ($level){
    case "track":
      try{
        return $tracks[rngw(1,sizeof($tracks))];
      }catch(Exception){
        goto reroll2;
      }
      break;

    case "artist":
      $artist = Track::getMetaData($tracks[rngw(2,sizeof($tracks))])['artist'];

      $tracks = Track::getSameByExclude("artist",$artist,false,$bannedArtists,$bannedGenres, $bannedTracks);
      if (!isset($tracks['filename'])){
        return $tracks[rngw(1,sizeof($tracks))];
      }else{
        return $tracks['filename'];
      }
      break;

    case "genre":
      $genre = Track::getMetaData($tracks[rngw(2,sizeof($tracks))])['genre'];

      $tracks = Track::getSameByExclude("genre",$genre,true,$bannedArtists,$bannedGenres, $bannedTracks);
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

