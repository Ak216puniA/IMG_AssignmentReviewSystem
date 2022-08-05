<?php

include "reviewer.php";
$reviewer=new Reviewer();
$reviewer->getUserParameters();
$reviewer->setTablename();

$studentname=$_REQUEST['studentname'];
$assignment=$_REQUEST['assignment'];
$link=$_REQUEST['link'];

$reviewer->acceptIterationRequest($studentname,$assignment,$link);
?>