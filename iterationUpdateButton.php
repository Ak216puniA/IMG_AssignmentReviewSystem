<?php
session_start();

include "reviewer.php";
$reviewer=new Reviewer();
$reviewer->setUserParameters();
// $reviewer->setTablename();

$assignment=$_REQUEST['assignment'];
$studentlink=$_REQUEST['link'];
$studentname=$_REQUEST['studentname'];
$studentemail=$reviewer->getStudentemail($studentname);
if(!empty($studentemail)){
    $reviewer->acceptIterationRequest($studentemail,$assignment,$studentlink);
}

?>