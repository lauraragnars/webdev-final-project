<?php

$shops = array('https://docs.google.com/spreadsheets/d/e/2PACX-1vRgvugkuS6G0iBUS5lUjXILRIUgUmMAkzGRtG-EG42XznvuCjZ39FjiEKSRTkLHfipJ-9RWpQnmSA97/pub?output=tsv', 
'https://docs.google.com/spreadsheets/d/e/2PACX-1vQ0FmZc_rJWGvILtcXMbfBfBvTrtR4Vk9olA_psAtLDsbrlNUzAn0B8QD4j4J0e8cMmNNzvgG6secVz/pub?output=tsv');

$out = [];

foreach ($shops as $shop) {
  $data = file_get_contents($shop);
  // Break lines
  $lines = explode("\n", $data);
  $keys = explode("\t", $lines[0]);
  
  for($i = 1; $i < count($lines); $i++){
    $data = explode("\t", $lines[$i]);
    $item = [];
    for($j = 0; $j < count($data); $j++){
      $data[$j] = str_replace("\r", "", $data[$j]);
      $keys[$j] = str_replace("\r", "", $keys[$j]);
      $item[$keys[$j]]=$data[$j];
    }
    array_push($out, $item);
  }
}
file_put_contents("shop.txt", json_encode($out));
?>