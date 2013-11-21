<?php 

  $file = 'data/subscriptions/'.time().'.txt';
  // Open the file to get existing content
  //$current = file_get_contents($file);
  // Append a new person to the file
  $current = implode("\n",$_REQUEST);
  // Write the contents back to the file
  file_put_contents($file, $current);
  
?>