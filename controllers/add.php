<?php
class add {
    function __construct(){}
    
    public function POST(){
        
echo "<link rel='stylesheet' href='/views/main.css' type='text/css'><div class='menu'>
    <ul>
        <li>
            <a href='index.php'>Home</a>
        </li>
    </ul>
</div><br>";
                print_r($_REQUEST);

    
    
    }
    

    
        public function GET(){

$tbl = ($_REQUEST["departments"]);
echo "<link rel='stylesheet' href='/views/main.css' type='text/css'><div class='menu'>
    <ul>
        <li>
            <a href='index.php'>Home</a>
        </li>
    </ul>
</div><br>";

include 'views/add.html';
}}
?>