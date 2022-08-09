<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GetStudentData</title>
    <link rel="stylesheet" href="styles/dashboard_style.css">
    <style><?php include "styles/dashboard_style.css"; ?></style>
    <script src="https://kit.fontawesome.com/765f34396c.js" crossorigin="anonymous"></script>
</head>
<body>

<?php

$studentemail=$_REQUEST['studentemail'];

include "reviewer.php";
$reviewer=new Reviewer();
$reviewer->setUserParameters();
// $reviewer->setTablename();

// $allStudentRows=$reviewer->getAllStudentData($studentTablename);

$assignment_basic_info=$reviewer->getAssignmentsRequiredInfo();
//$prepare_select_student_column=$this->connection->prepare("SELECT DISTINCT(?) FROM (SELECT assignment,`status`,submittedOn,reviewer,comment FROM students WHERE useremail=?) AS table1 JOIN (SELECT assignment,deadline FROM assignments) AS table2 ON table1.assignment=table2.assignment WHERE assignment=?");
$prepare_select_student_column=$this->connection->prepare("SELECT DISTINCT(?) FROM students WHERE useremail=? AND assignment=?");
$prepare_select_student_column->bind_param("sss",$bind_column,$bind_studentemail,$bind_assignment);
$bind_studentemail=$studentemail;
if($assignment_basic_info->num_rows > 0){
    while($assignment=$assignment_basic_info->fetch_assoc()){
        $bind_assignment=$assignment['assignment'];
        echo "
        <div class='studentAssignmentDiv'>
            <div class='studentAssignmentDiv1'>
                <div class='studentAssignmentData'>
                    <div class='studentAssignmentDataHeading'>Assignment</div>
                    <div class='studentAssignmentDataValue'>".$assignment['assignment']."</div>
                </div>
                <div class='studentAssignmentData'>
                    <div class='studentAssignmentDataHeading'>Deadline</div>
                    <div class='studentAssignmentDataValue'>".$assignment['deadline']."</div>
                </div>
                <div class='studentAssignmentData'>
                    <div class='studentAssignmentDataHeading'>Status</div>
                    <div class='studentAssignmentDataValue'>
                    ";
                    $bind_column="status";
                    $status_column=$prepare_select_student_column->execute();
                    // if($status_columns->num_rows >= 2){
                    //     echo "<span>Done</span>";
                    // }else if($status_columns->num_rows == 1){
                    //     $status=$status_columns->fetch_assoc()
                    // }
                    if($status_column->num_rows > 0){
                        $bool=true;
                        while($status=$status_column->fetch_assoc()&$bool){
                            if($status['status']=="Done"){
                                echo "<span>Done</span>";
                                $bool=false;
                            }
                        }
                        if($bool){
                            echo "<span>Pending</span>";
                        }
                    }
                    echo
                    "</div>
                </div>
                <div class='studentAssignmentData'>
                    <div class='studentAssignmentDataHeading'>Submitted On</div>
                    <div class='studentAssignmentDataValue'>
                    ";
                    // $bind_column="reviewer";
                    // $reviewer_cloumn=$prepare_select_student_column->execute();
                    // if($reviewer_cloumn->num_rows > 0){
                    //     while($reviewer_username=$reviewer_cloumn->fetch_assoc()){
                    //         echo "<div>- ".$reviewer_username['reviewer']."</div>";
                    //     }
                    // }else{
                    //     echo "<div>-</div>";
                    // }
                    $bind_column="submittedOn";
                    $submittedOn_cloumn=$prepare_select_student_column->execute();
                    if($submittedOn_cloumn->num_rows > 0){
                        while($submittedOn=$submittedOn_cloumn->fetch_assoc()){
                            echo "<div>".$submittedOn['submittedOn']."</div>";
                        }
                    }else{
                        echo "<div>-</div>";
                    }
                    echo"</div>
                </div>
                <div class='studentAssignmentData'>
                    <div class='studentAssignmentDataHeading'>Reviewers</div>
                    <div class='studentAssignmentDataValue'>
                     ";
                    $bind_column="reviewer";
                    $reviewer_cloumn=$prepare_select_student_column->execute();
                    if($reviewer_cloumn->num_rows > 0){
                        while($reviewer_username=$reviewer_cloumn->fetch_assoc()){
                            echo "<div>- ".$reviewer_username['reviewer']."</div>";
                        }
                    }else{
                        echo "<div>-</div>";
                    }
                    echo "
                    </div>
                </div>
            </div>
            <div class='studentAssignmentData' class='studentAssignmentDataComment'>
                <div class='studentAssignmentDataHeading'>Comments</div>
                <div class='studentAssignmentDataValue'>
                ";
                    $bind_column="comment";
                    $comment_cloumn=$prepare_select_student_column->execute();
                    if($comment_cloumn->num_rows > 0){
                        while($comment=$comment_cloumn->fetch_assoc()){
                            echo "<div>- ".$comment['comment']."</div>";
                        }
                    }else{
                        echo "<div>-</div>";
                    }
                echo "
                </div>
            </div>
        </div>
        ";
    }
}

?>
    
</body>
</html>