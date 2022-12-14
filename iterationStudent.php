<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iteration</title>
    <link rel="stylesheet" href="styles/dashboard_style.css">
    <style><?php include "styles/dashboard_style.css"; ?></style>
    <link rel="stylesheet" href="styles/iteration_style.css">
    <style><?php include "styles/iteration_style.css"; ?></style>
    <script src="https://kit.fontawesome.com/765f34396c.js" crossorigin="anonymous"></script>
</head>
<body>
<?php
    $_SESSION['onPage_session']="ITERATION";

    include "student.php";
    $student=new Student();
    $student->setUserParameters();
    // $student->setTablename();

    include "header.php";

    echo "
        <div class='pageLinksDiv'>
            <button class='pageLink' onClick='document.location.href=`./dashboard.php`'>Dashboard</button>
            <button class='pageLink' onClick='document.location.href=`./profile.php`'>Profile</button>
            <button class='pageLink' onClick='document.location.href=`./allReviewers.php`'>Reviewers</button>
            <button class='pageLink' onClick='document.location.href=`./allStudents.php`'>Students</button>
            <button class='pageLink' onClick='document.location.href=`./assignmentsStudent.php`'>Assignments</button>
            <button class='pageLink' onClick='document.location.href=`./iterationStudent.php`'>Iteration</button>
        </div>
    </div>

    <div class='section'>
    <div class='sectionHeading'>ITERATION REQUESTS</div>
    <div class='sectionContent'>
    ";
    $iterationRows=$student->getStudentIterationRequests();
    if($iterationRows->num_rows > 0){
        $divCount=0;
        while($iterationRow=$iterationRows->fetch_assoc()){
            echo "
            <div class='iterationBar'>
                <div class='iterationBarUpperDiv'>
                    <div class='iterationData iterationDataStudent'>
                        <div class='iterationDataHeading'>Student</div>
                        <div class='iterationDataValue' id='studentname".strval($divCount)."'>".$iterationRow['username']."</div>
                    </div>
                    <div class='iterationData iterationDataStudent'>
                        <div class='iterationDataHeading'>Assignment</div>
                        <div class='iterationDataValue' id='assignment".strval($divCount)."'>".$iterationRow['assignment']."</div>
                    </div>
                    <div class='iterationData iterationDataStudent'>
                        <div class='iterationDataHeading'>Previous Reviewers</div>
                        <div class='iterationDataValue'>
                        ";
                        // $reviewersArray=explode(",",$iterationRow['previousreviewers']);
                        // for($i=0 ; $i<count($reviewersArray) ; $i++){
                        //     $reviewersArray[$i]=trim($reviewersArray[$i]);
                        //     echo "<div>- ".$reviewersArray[$i]."</div>";
                        // }
                        $assignment_reviewer=$student->getStudentReviewers($iterationRow['assignment']);
                        if($assignment_reviewer->num_rows > 0){
                            while($reviewer_username=$assignment_reviewer->fetch_assoc()){
                                echo "<div>- ".$reviewer_username['reviewer']."</div>";
                            }
                        }else{
                            echo "<div>-</div>";
                        }
                        echo "
                        </div>
                    </div>
                    <div class='iterationData iterationDataStudent'>
                        <div class='iterationDataHeading'>Asked On</div>
                        <div class='iterationDataValue'>".$iterationRow['askedon']."</div>
                    </div>
                </div>
                ";
                $assignmentLink=$student->showHyphenIfNull($iterationRow['studentlink']);
                echo "
                <div class='iterationBarLowerDiv'>
                    <div class='iterationData iterationDataLink'>
                        <div class='iterationDataHeading'>Assignment Link</div>
                        <div class='iterationDataValue'><a class='aLink' href='".$assignmentLink."'>".$assignmentLink."</a></div>
                    </div>    
                </div>
            </div>
            ";
            $divCount++;
        }
    }
    echo "
    </div>
    </div>
    ";  
?>    
    
</body>
</html>