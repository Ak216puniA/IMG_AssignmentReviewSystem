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

$studentTablename=$_REQUEST['studentTable'];

include "reviewer.php";
$reviewer=new Reviewer();
$reviewer->getUserParameters();
$reviewer->setTablename();

$allStudentRows=$reviewer->getAllStudentData($studentTablename);

if($allStudentRows->num_rows > 0){
    while($assignmentRow=$allStudentRows->fetch_assoc()){
        echo "
        <div class='studentAssignmentDiv'>
            <div class='studentAssignmentDiv1'>
                <div class='studentAssignmentData'>
                    <div class='studentAssignmentDataHeading'>Assignment</div>
                    <div class='studentAssignmentDataValue'>".$assignmentRow['assignmentName']."</div>
                </div>
                <div class='studentAssignmentData'>
                    <div class='studentAssignmentDataHeading'>Deadline</div>
                    <div class='studentAssignmentDataValue'>".$assignmentRow['deadline']."</div>
                </div>
                <div class='studentAssignmentData'>
                    <div class='studentAssignmentDataHeading'>Status</div>
                    <div class='studentAssignmentDataValue'>".$assignmentRow['status']."</div>
                </div>
                <div class='studentAssignmentData'>
                    <div class='studentAssignmentDataHeading'>Submitted On</div>
                    <div class='studentAssignmentDataValue'>".$assignmentRow['submittedOn']."</div>
                </div>
                <div class='studentAssignmentData'>
                    <div class='studentAssignmentDataHeading'>Reviewers</div>
                    <div class='studentAssignmentDataValue'>
        ";
        $explodeReviewersArray=explode(",",$assignmentRow['reviewers']);
        for($i=0 ; $i<count($explodeReviewersArray) ; $i++){
            $explodeReviewersArray[$i]=trim($explodeReviewersArray[$i]);
            echo "<div>- ".$explodeReviewersArray[$i]."</div>";
        }
        echo "
                    </div>
                </div>
            </div>
            <div class='studentAssignmentData' id='studentAssignmentDataComment'>
                <div class='studentAssignmentDataHeading'>Comments</div>
                <div class='studentAssignmentDataValue'>
        ";
        $explodeCommentsArray=explode(",",$assignmentRow['suggestion']);
        for($i=0 ; $i<count($explodeCommentsArray) ; $i++){
            $explodeCommentsArray[$i]=trim($explodeCommentsArray[$i]);
            echo "<div>- ".$explodeCommentsArray[$i]."</div>";
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