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
    <script>
        function addToCurrentlyReviewing(){

            console.log("hello");

            let clickedButtonId=document.activeElement.id;
            let clickedButton=document.getElementById(clickedButtonId);

            let divCount=clickedButtonId.charAt(clickedButtonId.length - 1);
            let assignmentName=document.getElementById("assignment"+divCount).innerHTML;
            let studentName=document.getElementById("studentname"+divCount).innerHTML;
            let link=document.getElementById("link"+divCount).innerHTML;

            if(clickedButton.innerHTML=="Accept!"){
                xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function(){
                    if(this.readyState==4 && this.status==200){
                        console.log(this.response);
                        clickedButton.innerHTML="Accepted";
                        clickedButton.style.backgroundColor="#CA4F4F";
                    }
                }

                xmlhttp.open("POST","./iterationUpdateButton.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("studentname="+studentName+"&assignment="+assignmentName+"&link="+link);
            }
        }
    </script>
    <?php
    $_SESSION['onPage_session']="ITERATION";

    include "reviewer.php";
    $reviewer=new Reviewer();
    $reviewer->setUserParameters();
    // $reviewer->setTablename();

    include "header.php";

    echo "
        <div class='pageLinksDiv'>
            <button class='pageLink' onClick='document.location.href=`./dashboardReviewer.php`'>Dashboard</button>
            <button class='pageLink' onClick='document.location.href=`./profile.php`'>Profile</button>
            <button class='pageLink' onClick='document.location.href=`./allReviewers.php`'>Reviewers</button>
            <button class='pageLink' onClick='document.location.href=`./allStudents.php`'>Students</button>
            <button class='pageLink' onClick='document.location.href=`./assignmentsReviewer.php`'>Assignments</button>
            <button class='pageLink' onClick='document.location.href=`./iterationReviewer.php`'>Iteration</button>
        </div>
    </div>

    <div class='section'>
    <div class='sectionHeading'>ITERATION REQUESTS</div>
    <div class='sectionContent'>
    ";
    $iterationTable=$reviewer->getIterationRequests();
    if($iterationTable->num_rows > 0){
        $divCount=0;
        while($iterationRow=$iterationTable->fetch_assoc()){
            echo "
            <div class='iterationBar'>
                <div class='iterationBarUpperDiv'>
                    <div class='iterationData'>
                        <div class='iterationDataHeading'>Student</div>
                        <div class='iterationDataValue' id='studentname".strval($divCount)."'>".$iterationRow['username']."</div>
                    </div>
                    <div class='iterationData'>
                        <div class='iterationDataHeading'>Assignment</div>
                        <div class='iterationDataValue' id='assignment".strval($divCount)."'>".$iterationRow['assignment']."</div>
                    </div>
                    <div class='iterationData'>
                        <div class='iterationDataHeading'>Previous Reviewers</div>
                        <div class='iterationDataValue'>
                        ";
                        // $reviewersArray=explode(",",$iterationRow['previousreviewers']);
                        // for($i=0 ; $i<count($reviewersArray) ; $i++){
                        //     $reviewersArray[$i]=trim($reviewersArray[$i]);
                        //     echo "<div>- ".$reviewersArray[$i]."</div>";
                        // }
                        $reviewerArray=$reviewer->getStudentReviewers($iterationRow['studentemail'],$iterationRow['assignment']);
                        if($reviewerArray->num_rows > 0){
                            while($reviewer_username=$reviewerArray->fetch_assoc()){
                                echo "<div>- ".$reviewer_username['reviewer']."</div>";
                            }
                        }else{
                            echo "<div>-</div>";
                        }
                        echo "
                        </div>
                    </div>
                    <div class='iterationData'>
                        <div class='iterationDataHeading'>Asked On</div>
                        <div class='iterationDataValue'>".$iterationRow['askedon']."</div>
                    </div>
                    <div class='iterationData'>
                        <button class='iterationButton' id='iterationButton".strval($divCount)."' onClick='addToCurrentlyReviewing()'>Accept!</button>
                    </div>
                </div>
                ";
                $assignmentLink=$reviewer->showHyphenIfNull($iterationRow['studentlink']);
                echo "
                <div class='iterationBarLowerDiv'>
                    <div class='iterationData iterationDataLink'>
                        <div class='iterationDataHeading'>Assignment Link</div>
                        <div class='iterationDataValue' id='link".strval($divCount)."'><a class='aLink' href='".$assignmentLink."'>".$assignmentLink."</a></div>
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