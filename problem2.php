<?php 
  // Ben Nicholas, project 3 problem 2
  $letters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $rotated = "nopqrstuvwxyzabcdefghijklmNOPQRSTUVWXYZABCDEFGHIJKLM";
  
  $handle = fopen("php://stdin", "r");
  $input = "";
  
  while ($tmp = fgets($handle)) {
	  $input = $input . $tmp;
  }
  
  // Lines
  echo "\n# of lines: " . count(explode("\n", $input));
  // Words
  echo "\n# of words: " . count(preg_split("/\s+/", $input));
  // Characters
  echo "\n# of characters: " . strlen($input);
  
  // Rot13
  $rot = strtr($input, $letters, $rotated);
  echo "\nRot13: \n" . $rot;
  
?>