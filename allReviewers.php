<?php
session_start();

include "user.php";
$user=new User();
$user->setUserParameters();

if(isset($_GET['clickedDashboard'])){
    if($_GET['clickedDashboard']){
        $_GET['clickedDashboard']=false;
        if($_SESSION['userpart']=="Reviewer"){
            header("Location: dashboardReviewer.php");
        }else if($_SESSION['userpart']=="Student"){
            header("Location: dashboard.php");
        }
    }
}

if(isset($_GET['clickedIteration'])){
    if($_GET['clickedIteration']){
        $_GET['clickedIteration']=false;
        if($_SESSION['userpart']=="Reviewer"){
            header("Location: iterationReviewer.php");
        }else if($_SESSION['userpart']=="Student"){
            header("Location: iterationStudent.php");
        }
    }
}

if(isset($_GET['clickedAssignments'])){
    if($_GET['clickedAssignments']){
        $_GET['clickedAssignments']=false;
        if($_SESSION['userpart']=="Reviewer"){
            header("Location: assignmentsReviewer.php");
        }else if($_SESSION['userpart']=="Student"){
            header("Location: assignmentsStudent.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviewers</title>
    <link rel="stylesheet" href="styles/dashboard_style.css">
    <style><?php include "styles/dashboard_style.css" ?></style>
    <link rel="stylesheet" href="styles/allStudents_style.css">
    <style><?php include "styles/allStudents_style.css" ?></style>
    <script src="https://kit.fontawesome.com/765f34396c.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php
    $_SESSION["onPage_session"]="REVIEWERS";

    include "header.php";
    echo "
        <div class='pageLinksDiv'>
            <button class='pageLink'><a href='allStudents.php?clickedDashboard=true'>Dashboard</a></button>
            <button class='pageLink' onClick='document.location.href=`./profile.php`'>Profile</button>
            <button class='pageLink' onClick='document.location.href=`./allReviewers.php`'>Reviewers</button>
            <button class='pageLink' onClick='document.location.href=`./allStudents.php`'>Students</button>
            <button class='pageLink'><a href='allStudents.php?clickedAssignments=true'>Assignments</a></button>
            <button class='pageLink'><a href='allStudents.php?clickedIteration=true'>Iteration</a></button>
        </div>
    </div>

    <div class='allStudentsDiv'>
    ";

    $reviewerArray=$user->getReviewersBasicInfo();

    if($reviewerArray->num_rows > 0){
        while($reviewer_info=$reviewerArray->fetch_assoc()){
            // $reviewerName=$user->getUsernameByUseremail($reviewerEmail['useremail']);
            echo "
            <div class='studentInfoDiv'> 
                <div class='studentInfoImage'><i class='fa-solid fa-square-user'></i></div>
                <div class='studentInfoUsername'>".$reviewer_info['username']."</div>
                <div class='studentInfoUseremail'>".$reviewer_info['useremail']."</div>
            </div>
            ";
        }
    }
    ?>
</body>
</html>