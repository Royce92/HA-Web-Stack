<?php


class index{


	function __construct(){}

	public function GET() {

		
   $name = 'Joe'; # user-supplied data
 
try {
    $conn = new PDO('mysql:host=localhost;dbname=employees', 'root', 'a7505489');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    $data = $conn->query('SELECT * FROM employees LIMIT 5');
 
    foreach($data as $row) {
        print_r($row); 
    }
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}

}



}
?>
