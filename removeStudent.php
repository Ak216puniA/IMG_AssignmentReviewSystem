<?php

$tablename=$_REQUEST['tablename'];
$studentEmail=$_REQUEST['studentEmail'];

include "reviewer.php";

$reviewer= new Reviewer();
$reviewer->getUserParameters();
$reviewer->setTablename();

$reviewer->removeStudentFromDatabase($tablename,$studentEmail);

?>