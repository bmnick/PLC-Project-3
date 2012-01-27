<?php 
  // Ben Nicholas, project 3 problem 1
function logfile($msg) {
  $path = "/home/stu15/s10/bmn1826/public_html/www/plc.log";
  
  if ($msg != "")
    $msg = date(DATE_ATOM) . ": " . $msg;
    
  file_put_contents($path, $msg . "\n", FILE_APPEND);
}

if ($_FILES["file"]["error"] > 0) {
  echo "Error: " . $_FILES["file"]["error"] . "<br />";
} else {
  $file = file_get_contents($_FILES["file"]["tmp_name"]);
  
  $sections = explode("\n\n", $file);
  
  $name_string = $sections[0];
  $expense_lines = explode("\n", $sections[1]);
  
  $name = "";
  $expenses = array();
  
  $regex_match = 0;
  
  if (!preg_match("/^Name: (.*)/", trim($name_string), $regex_match)) {
    echo "Error: First line of uploaded file should be of the form \"Name: [some name]\"";
    die();
  } else {
    $name = $regex_match[1];
  }
  
  foreach($expense_lines as $expense_line) {
    if (!preg_match('/^(.*), (.*): \$(.*)\s*|^\s+/', $expense_line, $regex_match)) {
      echo "Error: expense line doesn't match given form<br />";
      echo "Offending Line: \"" . $expense_line . "\"";
      die();
    } else {
      $expense = array();
      
      $expense["category"] = $regex_match[1];
      $expense["date"] = $regex_match[2];
      $expense["amount"] = $regex_match[3];
      
      $expenses[] = $expense;
    }
  }
  
  logfile("Connecting to database...");
  
  $conn = mysql_connect("huckleberryroad.com", "hucklebe_plc", "0WtMN@M}QABk");
  
  if (!$conn) {
    echo "Error: can't connect to database";
    die(); 
  }
  
  logfile("Selecting database...");
  
  if (!mysql_select_db("hucklebe_plc")) {
    echo "Unable to select database";
  }
  
  $person_sql_string = "SELECT person_id, name 
                        FROM People 
                        WHERE People.name = \"" . $name . "\""; // Maybe they won't abuse this
  
  logfile("Querying: " . $person_sql_string);

  $result = mysql_query($person_sql_string);
  
  if (!$result) {
    echo "Could not successfully run query on DB";
    die();
  }
  
  $person_id = 0;
  
  if (mysql_num_rows($result) == 0) {
    $person_insert_sql_string = "INSERT INTO People (name) 
                                 VALUES (" . $name . ")";
                                 
    logfile("Querying: " . $person_insert_sql_string);                             
    
    mysql_query($person_insert_sql_string);
    
    $result = mysql_query($person_sql_string);
  }
  
  $row = mysql_fetch_assoc($result);
  $person_id = $row["person_id"];
  
  foreach ($expenses as $expense) {
    $expense_insert_sql_string = "INSERT INTO Expenses (person_id, item, cost, expense_date)
                                  VALUES (" . $person_id . ", " . $expense["category"] . ", " . $expense["amount"] . ", " . date("Y-m-d", strtotime($expense['date'])) . ")";
                                  
    logfile("Querying: " . $expense_insert_sql_string);
    
    $result = mysql_query($expense_insert_sql_string);
  }
  
  print_r($row);
  
}
?>