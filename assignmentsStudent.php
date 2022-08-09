<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments</title>
    <link rel="stylesheet" href="styles/dashboard_style.css">
    <style><?php include "styles/dashboard_style.css" ?></style>
    <link rel="stylesheet" href="styles/assignments_style.css">
    <style><?php include "styles/assignments_style.css" ?></style>
    <script src="https://kit.fontawesome.com/765f34396c.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php
    $_SESSION['onPage_session']="ASSIGNMENTS";
    
    include "student.php";
    $student=new student();
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
    <div class='sectionHeading'>ASSIGNMENTS</div>
    <div class='sectionContentAssignment'>
    ";   
    $assignment_table=$student->getAssignmentsTable();
    if($assignment_table->num_rows > 0){
        $divCount=0;
        while($assignment=$assignment_table->fetch_assoc()){

            echo "
            <div class='sectionContentSubDiv'>
                <div class='sectionContentSubDiv1'>
                    <div class='sectionContentDivData'>
                        <div class='sectionContentDivDataHeading'>Assignment</div>
                        <div class='sectionContentDivDataValue'>".$assignment['assignment']."</div>
                    </div>
                    <div class='sectionContentDivData'>
                        <div class='sectionContentDivDataHeading'>Deadline</div>
                        <div class='sectionContentDivDataValue'>".$assignment['deadline']."</div>
                    </div>
                    <div class='sectionContentDivData'>
                        <div class='sectionContentDivDataHeading'>Topics</div>
                        <div class='sectionContentDivDataValue'>";
                        if(!empty($assignment['topics'])){
                            $topics=$assignment['topics'];
                            $topicArray=explode(",",$topics);
                            for($i=0 ; $i<count($topicArray) ; $i++){
                                $topicArray[$i]=trim($topicArray[$i]);
                                echo "<div>".$topicArray[$i]."</div>";
                            }
                        }else{
                            echo "-";
                        }                        
                        echo"
                        </div>
                    </div>
                </div>
                <div class='sectionContentSubDiv2'>
                        <div class='sectionContentDivDataHeading'>Description</div>
                        <div class='sectionContentDivDataValue'>".$student->showHyphenIfNull($assignment['description'])."</div>
                </div>
                <div class='sectionContentSubDiv2'>
                        <div class='sectionContentDivDataHeading'>Assignment Link</div>
                        <div class='sectionContentDivDataValue'>".$student->showHyphenIfNull($assignment['assignmentlink'])."</div>
                </div>
                <div class='sectionContentSubDiv2'>
                        <div class='sectionContentDivDataHeading'>Resources</div>
                        <div class='sectionContentDivDataValue'>";
                        if(!empty($assignment['resource'])){
                            $resources=$assignment['resource'];
                            $resourceArray=explode(",",$resources);
                            for($i=0 ; $i<count($resourceArray) ; $i++){
                                $resourceArray[$i]=trim($resourceArray[$i]);
                                echo "<div>".$resourceArray[$i]."</div>";
                            }
                        }else{
                            echo "-";
                        }
                        echo"
                        </div>                
                </div>
            </div>
            ";
            $divCount++;
        }
    }
    ?>
    
</body>
</html>