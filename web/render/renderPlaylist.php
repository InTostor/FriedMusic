<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/fetchTracks.php";
require_once "$root/lib/dev.php";
require_once "$root/lib/fileWrapper.php";

if (isset($_GET['src'])){
  if ( isset($_GET['type']) and isset($_GET['query']) and $_GET['src']=="search"){
    $type = $_GET['type'];
    $musicList = search($type,$_GET['query']);
    $playListType = "search";
  }else{
    $musicList = File::getAsArray($root."/".$_GET['src']);
    $playListType = "general";
  }

}else{
  http_response_code(400);
  die();
}

?>

<link rel="stylesheet" href="/styles/98.css">
<link rel="stylesheet" href="/styles/main.css">

<table>

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

<tbody>
<tr>
  <?php if ( $playListType == "search"){echo"
  <th>artist</th>
  <th>title</th>
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
  $title = preg_replace("/\(.+\)/m",'',$track['title']);
  $artists=[];
  foreach (explode(", ",$track["artist"]) as $artist){array_push($artists,$artist);}
  echo"<tr>";

  // artists
  echo"<td>";
  echo implode(", ",$artists);
  echo"</td>";

  // title
  echo"<td>".$title."</td>";
  }else{
    echo"<td>$track</td>";
    $filename = $track;
  }
  // buttons
  $filename = "Linkin Park - Numb.mp3";
  echo "<td><div class='trackActionsDiv'>";
  echo "<button class='trackActionButton'onclick=\"reqPlayTrack($key,'search')\"><img class='actionIco'src='/resources/loudspeaker_rays-0.png'></button>";
  echo "<button class='trackActionButton'onclick=\"addToFavourite('$filename')\">       <img class='actionIco'src='/resources/directory_favorites-2.png'></button>";
  echo "<button class='trackActionButton'onclick=\"addToPlaylist('$filename')\">        <img class='actionIco'src='/resources/directory_open_file_mydocs-4.png'></button>";
  echo "</div></td>";

  if ( $playListType == "search"){
  echo"<td>$duration</td>";
  echo"<td>$genre</td>";
  }

  echo"</tr>";
}

?>
</tbody>


</table>