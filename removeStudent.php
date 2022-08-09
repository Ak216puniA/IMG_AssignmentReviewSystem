<?php

// $tablename=$_REQUEST['tablename'];
$studentemail=$_REQUEST['studentEmail'];

include "reviewer.php";

$reviewer= new Reviewer();
$reviewer->setUserParameters();
// $reviewer->setTablename();

$reviewer->removeStudent($studentemail);

?>