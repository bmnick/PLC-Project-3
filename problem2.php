<?php 
  echo "Enter text, line by line. Finish with an empty line.\n";
  
  $handle = fopen("php://stdin", "r");
  $lines = array();
  
  do {
    echo "> ";
    
    $line = trim(fgets($handle));
    
    if ($line != "") {
      $lines[] = $line;
    }
    
  } while($line != "");
  
  $rot = array();
  foreach($lines as $line) {
    
  }
  
  print_r($lines);
?>