<?php

$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/fileWrapper.php";
require_once "$root/lib/dev.php";

$methodFiles = glob("$root/docs/apiMethodsDescription/*.{json}", GLOB_BRACE);

foreach ($methodFiles as $key=>$file){
  $methodsArray[$key] =File::openJson($file);
}

usort($methodsArray, function ($item1, $item2) {
  return $item1['category'] <=> $item2['category'];
});

?>

<html lang="en">
  <head>
    <link rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="/styles/98.css">
    <title>API documentation</title>
  </head>
  <style>
  body {
    background-color: #c0c0c0;
    color: #222;
  }

  h1,h2,h3{
    box-shadow: inset 1px 1px #fff, inset -1px -1px grey, inset 4px 4px #dfdfdf, inset -2px -2px #0a0a0a;
    padding:10px;
  }

  h1,h2,h3,h4,h5,h6{
    margin:10px 5px 10px 5px;
  }
  var {
    color: rgb(0, 80, 80);
    font-weight: bold;
  }

  .material-symbols-outlined {
    font-variation-settings:
      'FILL' 0,
      'wght' 400,
      'GRAD' 0,
      'opsz' 24
  }

  .apiFunction {
    padding: 5px;
    margin: 15px 0 0 0;
    box-shadow: inset -1px -1px #fff, inset 1px 1px grey, inset -2px -2px #dfdfdf, inset 2px 2px #0a0a0a;
  }

  .warning {
    color: yellow;
    background-color: #000;
    box-shadow: 0px 0px 0px 2px #000;
  }

  .section {
    padding: 5px;
    margin: 15px 0 0 0;
    border: 2px solid black;
  }
  .apiUrl{
    font-size:1rem;
    color:#000 !important;
    font-family: unset;
  }
</style>
  <body>
    <div class="section">
      <h1>Info</h1>
      This is documentaion for the API. It is usefull to gather data, make custom web or desktop/mobile client. <br>
      Not all web functionality available in API and vice versa.<br>
      No constants are taken from config files. Changing config will cause this document to be outdated
      <p>There are used some icons which describing type and behaviour of an API methods</p>
      <p><span class="material-symbols-outlined">key</span> - authentication required</p>
      <p><span class="material-symbols-outlined">upload</span> - file in POST expected (upload file)</p>
      <p><span class="material-symbols-outlined">delete</span> - deleting information (files or database data)</p>
      <p><span class="material-symbols-outlined">table</span> - get data from database</p>
      <p><span class="material-symbols-outlined">timer</span> - method has cooldown (DoS prevention)</p>
    </div>
    <div class="section">
      <h1>Outline</h1>
      <?php foreach ($methodsArray as $method){
        echo "<div class=\"apiFunction\"><a href=\"#{$method['url']}\">".$method['url']." ". $method['methodHumanName']."</a></div>";
      }?>
    </div>
  </body>
</html>


<?php

$lastCat = "";
foreach ($methodsArray as $method){
  if ($method['category'] != $lastCat){
    $lastCat = $method['category'];
    echo "<h1>$lastCat</h1>";
  }

  $spanTags = [];
  foreach ($method['tags'] as $key=>$tag){
    $spanTags[$key] = "<span class=\"material-symbols-outlined\">$tag</span>";
  }

  echo 
  "
  <div class=\"section\">
    <h3 id=\"{$method['url']}\">{$method['methodHumanName']}</h3>
    ".implode('',$spanTags)."
    <div class=\"apiFunction\">
    <p>{$method['description']}</p>
    <h4>Request</h4>
    {$method['requestType']}<input class=\"apiUrl\" readonly value={$method['url']}></input>
    <h4>Return</h4>
    <p>MIME type <var>{$method['returnMime']}</var></p>
    <pre>{$method['returnExample']}</pre>
  ";
}