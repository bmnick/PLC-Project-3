<?php 
  function array_transpose($array) { 
    if (!is_array($array)) return false; 
    $return = array(); 
    foreach($array as $key => $value) { 
      if (!is_array($value)) return $array; 
      foreach ($value as $key2 => $value2) { 
        $return[$key2][$key] = $value2; 
      } 
    } 
    return $return; 
  } 

  $handle = fopen("php://stdin", "r");
  $lines = array();
  
  do {
    echo "Enter a row: ";
    
    $line = trim(fgets($handle));
    
    if ($line != "") {
      $lines[] = explode(",", $line);
    }
    
  } while($line != "");
  
  echo "\nInput: \n";
  print_r($lines);
  
  echo "Transposed Matrix: \n";
  print_r(array_transpose($lines));
?>