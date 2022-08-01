<?php

$tablename=$_REQUEST['tablename'];
$studentEmail=$_REQUEST['studentEmail'];

// echo "<script> console.log('".$tablename." , ".$studentEmail."')</script>";

include "reviewer.php";

$reviewer= new Reviewer();
$reviewer->getUserParameters();
$reviewer->setTablename();

$reviewer->removeStudentFromDatabase($tablename,$studentEmail);

?>