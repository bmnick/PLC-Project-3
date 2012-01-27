<?php 
  // Ben Nicholas, project 3 problem 4
function logfile($msg) {
  $path = "/home/stu15/s10/bmn1826/public_html/www/plc.log";
  
  if ($msg != "")
    $msg = date(DATE_ATOM) . ": " . $msg;
    
  file_put_contents($path, $msg . "\n", FILE_APPEND);
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Expense Report Generation!</title>
  </head>
  
  <body>
    <form action="reports.php" method="get" accept-charset="utf-8">
     	<label for="person">Person: </label><input type="text" name="person" value="" id="person" />
     	<label for="date-begin">Begin Date: </label><input type="text" name="date-begin" value="" id="date" />     	
     	<label for="date-end">End Date: </label><input type="text" name="date-end" value="" id="date" />     	

    	<input type="submit" value="Continue &rarr;">
    </form>
    <?php 
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
        
        $filters = "";
        
        if ($_GET["person"]) {
          $filters = $filters . "WHERE name = \"" . $_GET["person"] . "\" ";
        }
        
        if ($_GET["date-begin"] && $_GET["date-end"]) {
          if ($filters == "") {
            $filters = $filters . "WHERE ";
          } else {
            $filters = $filters . "AND ";
          }
          $filters = $filters . "date BETWEEN " . date("Y-m-d", strtotime($_GET["date-begin"])) . " AND " . date("Y-m-d", strtotime($_GET["date-end"])) . " ";
        }
        
        $person_totals_sql = "SELECT name, SUM(cost) FROM Expenses INNER JOIN People ON Expenses.person_id = People.person_id " . $filters . " GROUP BY name";
        
        logfile("Querying: " . $person_totals_sql);
        
        $result = mysql_query($person_totals_sql);
        
        if ($result) {
          while ($row = mysql_fetch_assoc($result)) {
            echo $row["name"] . ": $" . $row["SUM(cost)"] . "<br />";
          }
        } else {
          echo "No Results found matching this filter";
        }
        
        echo "<br /> <br />";
        
        $categories_totals_sql = "SELECT item, SUM(cost) FROM Expenses INNER JOIN People ON Expenses.person_id = People.person_id " . $filters . " GROUP BY item";
        
        logfile("Querying: " . $categories_totals_sql);
        
        $result = mysql_query($categories_totals_sql);
        
        if ($result) {
          while ($row = mysql_fetch_assoc($result)) {
            echo $row["item"] . ": $" . $row["SUM(cost)"] . "<br />";
          }
        } else {
          echo "No Results found matching this filter";
        }

    ?>
  </body>
</html>