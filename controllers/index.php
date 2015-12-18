<?php


class index{

	function __construct(){}
	public function GET() {
 
try {
echo "<link rel='stylesheet' href='/views/main.css' type='text/css'>
<div class='idx'>
<h2>Department Name</h2>
</div>
";

	require 'config/sql.php'; //router

    $data = $conn->query('SELECT * FROM departments');

echo "<form action='demo_form.asp' method='get'>
<select size='20' name='selectionField' style = 'width: 200px'";

    foreach($data as $row) {

 echo" <option value='$row[dept_no]' >$row[dept_name]</option>

";}
echo "</select>   <input type='image' src='views/images/button.png' border='0' alt='Submit' style='
    margin-left: 11px;
    margin-bottom: 115px;
    width: 147px;
'/>
";		
		
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

}

}
?>
