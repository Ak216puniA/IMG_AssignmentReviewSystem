<?php

if($_REQUEST['userpart']=="Reviewer"){
    include "reviewer.php";
    $reviewer=new Reviewer();
    $reviewer->getUserParameters();
    $reviewer->setTablename();
    $reviewer->tablename="review".$reviewer->tablename;
    
    if(isset($_REQUEST['buttonId'])){
            if($_REQUEST['buttonId']=='done'){
            $assignmentName=$_REQUEST['assignmentName'];
            $studentEmail=$_REQUEST['studentEmail'];
        
            $reviewer->markStatusDone($studentEmail,$assignmentName);
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
    $student->getUserParameters();
    $student->setTablename();

    if(isset($_REQUEST['buttonId'])){
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

    if(isset($_POST['assignmentLink'])){
        if(!empty($_POST['assignmentLink'])){
            $student->updateAssignmentLink($_POST['assignmentLink'],$_POST['assignment']);
        }else{
            alert("Please add the Assignment Link!");
        }
        header("Location: dashboard.php");
    }  
}

    
    

    // if($_POST['buttonId']=='updateLink'){
    //     echo "
    //     <form action='./dashboardUpdateButton.php' method='POST'>
    //         <div class='linkForm'>
    //         <label for='assignmentLink' class='assignmentLinkLabel'>Enter your Assignment Link</label>
    //         <input type='text' name='assignmentLink' id='assignmentLink' class>
    //         <input type='submit' name='submitLink' value='Update!'>
    //         </div>
    //     </form>
    //     ";
    // }


// if($_POST['userpart']=="Student"){
//     include "student.php";

//     $student = new Student();
//     $student->getUserParameters();
//     $student->setTablename();

//     if(!empty($_POST['assignmentLink'])){
//         $student->updateAssignmentLink($_POST['assignmentLink'],$_POST['assignment']);
//     }
// }


?>