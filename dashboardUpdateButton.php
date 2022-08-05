<?php

if($_REQUEST['userpart']=="Reviewer"){
    include "reviewer.php";
    $reviewer=new Reviewer();
    $reviewer->getUserParameters();
    $reviewer->setTablename();
    $reviewer->tablename="review".$reviewer->tablename;
    
    if($_REQUEST['buttonId']=='done'){
        $assignmentName=$_REQUEST['assignmentName'];
        $studentEmail=$_REQUEST['studentEmail'];
    
        $reviewer->markStatusDone($studentEmail,$assignmentName);
    }
}

if($_REQUEST['userpart']=="Student"){
    include "student.php";

    $student = new Student();
    $student->getUserParameters();
    $student->setTablename();
    
    if($_REQUEST['buttonId']=='addremovecurrent'){
        $assignmentName = $_REQUEST['name'];
        $update = $_REQUEST['update'];
    
        $student->updateCurrentData($assignmentName, $update);
    }
    
    if($_REQUEST['buttonId']=='iteration'){
        $assignmentName=$_REQUEST['name'];
    
        $student->addInIterationTable($assignmentName);
    }
}


?>