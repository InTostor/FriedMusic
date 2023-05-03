<?php
function fout($val){
  echo "<pre>";print_r($val);echo"</pre>";
}
function clamp($current,$min,$max){
  return max($min, min($max, $current));
}

function conlog($data) {
  $output = $data;
  if (is_array($output))
      $output = implode(',', $output);

  echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}
function html($path){
include $path;
}