<?php 

  $file = 'data/subscriptions/'.time().'.txt';
  $current = implode("\n",$_REQUEST);
  file_put_contents($file, $current);
  
?>