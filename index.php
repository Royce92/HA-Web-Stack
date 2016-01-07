<?php
//ini_set('display_errors',1);
//error_reporting(E_ALL);

require 'controllers/index.php'; //router
require 'controllers/employees.php'; //router
require 'controllers/departments.php'; //router
require 'controllers/add.php'; //router

     $page_request = 'index';
     if(isset($_REQUEST['page'])) {
        $page_request = $_REQUEST['page'];
      }
      $page = new $page_request;
      $method = $_SERVER['REQUEST_METHOD'];
     /* if($method != "GET"){
         echo $page->$method($_REQUEST['Fname'],$_REQUEST['Lname'],$_REQUEST['Email']);
      }
      else
         */echo $page->$method();
?>
