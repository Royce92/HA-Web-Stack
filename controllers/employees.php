<?php


class employees{

	function __construct(){}
	public function GET() {
 
try {
echo "<link rel='stylesheet' href='/views/main.css' type='text/css'>
<div class='tbl'>
<table>
<tr>
<th>First Name</th>
<th>Last Name</th>
<th>Hire Date</th>
<th>End Date</th>


</tr>
</table>
</div>
";

	require 'config/sql.php'; //router

    $data = $conn->query('SELECT * FROM employees LIMIT 5');



    foreach($data as $row) {
echo"
<div class='tbl' >
<table>
  <tr>
    <td>$row[first_name]</td>
	<td>$row[last_name]</td>
	<td>$row[hire_date]</td>
	<td>Current</td>

  </tr>
</table>
</div>"
;
	    }
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

}



}
?>
