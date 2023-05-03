<?php
ini_set('display_errors', '1');

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/dbWrapper.php";
require_once "$root/settings/config.php";
require_once "$root/lib/renderMusicTable.php";

function search($type,$q){
  $q="%$q%";
  switch ($type){
    default:
      $sql = "SELECT * FROM `fullmeta` where filename like ? or artist like ? or album like ? or title like ?";
      return Database::executeStmt($sql,"ssss",[$q,$q,$q,$q]);
    case "album":
      $sql = "SELECT * FROM `fullmeta` where album like ?";
      break;
    case "artist":
      $sql = "SELECT * FROM `fullmeta` where artist like ?";
      break;
    case "genre":
      $sql = "SELECT * FROM `fullmeta` where genre like ?";
      break; 
  }
  
  return Database::executeStmt($sql,"s",[$q]);

}

$searched=false;
if (isset($_GET['type']) and isset($_GET['query'])){
  $type = $_GET['type'];
  $musicList = search($type,$_GET['query']);
  $searched=true;
}





?>

<!DOCTYPE html>
<html>

<head>
  <meta title="search">
  <link rel="stylesheet" href="/styles/98.css">
  <link rel="stylesheet" href="/styles/main.css">
  <script src="/js/draggable.js"></script>
</head>
<style>
.window{
display: flex;
flex-direction: column;
}

</style>


<body>
  <div class="wrapper">


  <div class="window" style="margin: 32px;position:relative;width:100%;max-height:100vh;">
    <div class="title-bar">
      <div class="title-bar-text">Search</div>
      <div class="title-bar-controls">
        <button aria-label="Minimize"></button>
        <button aria-label="Maximize"></button>
        <button aria-label="Close"></button>
      </div>
    </div>
    <form action="" method="get">
      <div class="field-row" >
        <img src="/resources/clippy.gif">
        <div class="field-row-stacked" style="width: 400px">
          <select name="type">
            <option>query</option>
            <option>artist</option>
            <option>album</option>
            <option>genre</option>
          </select>
          <input name="query" type="text">
          <input type="submit">
        </div>
        </div>
      </form>
    <div class="sunken-panel" style="position:relative;width:100%;height:100%;" >

        <?php
    if ($searched) {
      $cols = ['id','filesize','title','duration','album','genre','artist','year'];
      renderMusicTable($musicList,$musicPath,$cols,"style=\"width:100%\"");
    }
    ?>
      </table>
    </div>
  </div>

  </div></body>
<script>
  dragElement(document.getElementById("searcher"));
</script>
</html>