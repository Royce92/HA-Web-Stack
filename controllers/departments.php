<?php

class departments{
    function __construct(){}
        public function GET(){
            
            
            
$tbl = employees;
try {
	require 'config/sql.php'; //router
     
    $stmt = $conn->prepare("SELECT * FROM  $tbl");
    $stmt->execute();

 
    while($row = $stmt->fetch()) {
        print_r($row);
    }
} catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
}
               
        }      
    }
    
?>