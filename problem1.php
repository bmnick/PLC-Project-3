<?php
  // Ben Nicholas, project 3 problem 1
  do {
    echo "Input list of 5 numbers, seperated by \", \"> ";
    $handle = fopen("php://stdin", r);
    $line = trim(fgets($handle));
    
    $entries = split(", ", $line);
  } while (count($entries) != 5);
  
  
  natsort($entries);
  
  echo "Ascending order: " . implode(" ", $entries) . "\n";
  echo "Descending order: " . implode(" ", array_reverse($entries)) . "\n";
  
?>