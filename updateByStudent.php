<?php

$assignmentName = $_REQUEST['name'];
$update = $_REQUEST['update'];

include "student.php";

$student = new Student();
$student->getUserParameters();
$student->setTablename();
$student->updateCurrentData($assignmentName, $update);

?>