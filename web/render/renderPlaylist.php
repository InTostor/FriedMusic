<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/fetchTracks.php";
require_once "$root/lib/dev.php";
require_once "$root/lib/fileWrapper.php";
require_once "$root/lib/user.php";

if (isset($_GET['src'])){
  if ( isset($_GET['type']) and isset($_GET['query']) and $_GET['src']=="search"){
    $type = $_GET['type'];
    $musicList = search($type,$_GET['query']);
    $playListType = "search";
  }elseif ($_GET['src']!="search"){
    $musicList = File::getAsArray($root."/".$_GET['src']);
    $playListType = "general";
  }

}else{
  http_response_code(400);
  die();
}

?>


<table class="renderedPlaylist" style="white-space: initial ">

<style>
.trackActionsDiv{
  min-width:100px;
  width:100px;
  display:flex;
  flex-direction:row;
  justify-content:space-around;
}
.trackActionButton{
  height:28px;
  width:28px;
  min-width:unset;
  padding:0px;
  margin:0px
}
.actionIco{
  height:22px;
  margin:2px;
}
</style>

<tbody style="white-space:unset;">
<tr>
  <?php if ( $playListType == "search"){echo"
  <th>artist</th>
  <th>title</th>
  <th>Album</th>
  <th>actions</th>
  <th>duration</th>
  <th>genre</th>";
  }else{echo"
    <th>filename</th>
    <th>actions</th>";
  }?>
</tr>
<?php
foreach ($musicList as $key=>$track){
  if ( $playListType == "search"){
  $tid = $track['id'];
  $duration = gmdate("i:s", $track['duration']);
  $filename = $track['filename'];
  $genre = $track['genre'];
  // $title = preg_replace("/\(.+\)/m",'',$track['title']);
  $title = $track['title']; //beacuse some songs have (name) and other (metadata)
  $album = $track['album'];
  $artists=[];
  $fav = '/resources/directory_favorites-2.png';
  if (File::isInFile($filename,$root."/userdata/".User::getUsername()."/favourite.fpl")){
    $fav = '/resources/directory_favorites-remove-2.png';
  }
  foreach (explode(", ",$track["artist"]) as $artist){array_push($artists,$artist);}
  echo"<tr>";

  // artists
  echo"<td>";
  echo implode(", ",$artists);
  echo"</td>";

  // title
  echo"<td>".$title."</td>";
  echo"<td><a href='javascript:s.search(\"Album\",\"$album\");'>$album</a></td>";
  }else{
    echo"<td>$track</td>";
    $filename = $track;
  }
  // buttons
  echo "<td><div class='trackActionsDiv'>";
  echo "<button class='trackActionButton'onclick=\"reqPlayTrack($key,'$filename','search')\" title='play this track'><img class='actionIco'src='/resources/loudspeaker_rays-0.png' alt='play this track'></button>";
  echo "<button class='trackActionButton'onclick=\"a.toFavourite('$filename')\" title='add/remove track from favourites'>       <img class='actionIco'src='$fav' alt='add/remove track from favourites'></button>";
  echo "<button class='trackActionButton'onclick=\"a.addToPlaylist('$filename')\" title='add this track to the playlist'>        <img class='actionIco'src='/resources/directory_open_file_mydocs-4.png' alt='add this track to the playlist'></button>";
  echo "</div></td>";

  if ( $playListType == "search"){
  echo"<td>$duration</td>";
  echo"<td><a href='javascript:s.search(\"Genre\",\"$genre\");'>$genre</a></td>";
  }

  echo"</tr>";
  
}

?>
</tbody>

max 100 tracks
</table>