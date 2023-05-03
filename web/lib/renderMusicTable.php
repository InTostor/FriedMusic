<?php

function renderMusicTable(array $musicList, string $musicPath, $listOfColumns, string $attributes){
  echo "
  <table class=\"interactive\" $attributes>";
// draw header
echo "<tr>";
  foreach ($listOfColumns as $col){echo "<th>$col</th>";}
  echo "</tr>";
  foreach ($musicList as $mus){
    $file = "$musicPath/".$mus['filename'];
    $filesize = round($mus['filesize']/1048576,2)."MB";
    $duration = gmdate("i:s", $mus['duration']);
    $artists=[];
    foreach (explode(", ",$mus["artist"]) as $artist){
      $artStr = "<a href=?type=artist&query=".str_replace(" ","+",$artist).">$artist</a> ";
      array_push($artists,$artStr);
    }
    $tableRow = [
      "id"=>$mus['id'],
      "filesize"=>$mus['id'],
      "filename"=>$mus['id'],
      "title"=>$mus['id'],
      "duration"=>$mus['id'],
      "album"=>$mus['id'],
      "genre"=>$mus['id'],
      "artist"=>$mus['id'],
      "year"=>$mus['year'],

    ];

    echo "<tr>";
    echo "<td>".$mus['id']                  . "</td>";
    echo "<td>$filesize</td>";
    echo "<td><a href='$file'>".$mus['filename']. "</a></td>";
    echo "<td>".$mus['title']               . "</td>";
    echo "<td>$duration</td>";
    echo "<td><a href='?type=album&query=".$mus['album']."'>".$mus['album']   . "</a></td>";
    echo "<td><a href='?type=genre&query=".$mus['genre']."'>".$mus['genre']   . "</a></td>";
    echo "<td>";foreach ($artists as $artist){echo $artist;}"</td>";
    echo "<td>".$mus['year']                . "</td>";
    echo "</tr>";
  }
  
}