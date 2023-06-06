<?php
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/lib/fileWrapper.php";

class LocalString{
  public $locale;
  public $localizedStrings;

  function __construct($locale){
    $this->locale = $locale;
    global $root;
    $this->localizedStrings = File::openJson("$root/locale/$locale.json");
  }

  function get($token){
    if (isset($this->localizedStrings[$token])){
      return $this->localizedStrings[$token];
    }else{
      return $token;
    }
  }
  static function getAvailableLocales(){
    $out = array();
    global $root;
    foreach (new DirectoryIterator("$root/locale/") as $file) {
      if ($file->isFile()) {
        array_push($out,
        pathinfo($file->getFilename(), PATHINFO_FILENAME)
      );
      }
    }
    return $out;
  }

}
