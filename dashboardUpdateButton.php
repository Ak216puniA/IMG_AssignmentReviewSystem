<?php

if($_REQUEST['userpart']=="Reviewer"){
    include "reviewer.php";
    $reviewer=new Reviewer();
    $reviewer->setUserParameters();
    // $reviewer->setTablename();
    // $reviewer->tablename="review".$reviewer->tablename;
    
    if(isset($_REQUEST['buttonId'])){
            if($_REQUEST['buttonId']=='done'){
            $assignmentName=$_REQUEST['assignmentName'];
            $studentemail=$_REQUEST['studentEmail'];
        
            $reviewer->updateStudentStatus($studentemail,$assignmentName);
        }
    }

    if(isset($_POST['comment'])){
        if(!empty($_POST['comment'])){
            $reviewer->updateComment($_POST['studentemail'],$_POST['assignment'],$_POST['comment']);
        header("Location: dashboardReviewer.php");
        }  
    }
}

if($_REQUEST['userpart']=="Student"){
    include "student.php";

    $student = new Student();
    $student->setUserParameters();
    // $student->setTablename();

    if(isset($_REQUEST['buttonId'])){
        if($_REQUEST['buttonId']=='addremovecurrent'){
            $assignmentName = $_REQUEST['name'];
            $update = $_REQUEST['update'];
        
            $student->updateStudentCurrentColumn($assignmentName,$update);
        }
        
        if($_REQUEST['buttonId']=='iteration'){
            $assignmentName=$_REQUEST['name'];
            $studentlink=$_REQUEST['studentLink'];
        
            $student->insertIteration($assignmentName,$studentlink);
        }
    }

    if(isset($_POST['assignmentLink'])){
        if(!empty($_POST['assignmentLink'])){
            updateStudentlink($_POST['assignmentLink'],$_POST['assignment']);
        }else{
            alert("Please add the Assignment Link!");
        }
        header("Location: dashboard.php");
    }  
}

?>