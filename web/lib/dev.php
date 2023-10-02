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

function runAndFetchCmd($cmd, &$stdout=null, &$stderr=null) {
  $proc = proc_open($cmd,[
      1 => ['pipe','w'],
      2 => ['pipe','w'],
  ],$pipes);
  $stdout = stream_get_contents($pipes[1]);
  fclose($pipes[1]);
  $stderr = stream_get_contents($pipes[2]);
  fclose($pipes[2]);
  return proc_close($proc);
}