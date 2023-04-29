<?php
function fout($val){
  echo "<pre>";print_r($val);echo"</pre>";
}
function clamp($current,$min,$max){
  return max($min, min($max, $current));
}