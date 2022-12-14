<?php
session_start();

if(isset($_GET['clickedDashboard'])){
    if($_GET['clickedDashboard']){
        $_GET['clickedDashboard']=false;
        if($_SESSION['userpart_session']=="Reviewer"){
            header("Location: dashboardReviewer.php");
        }else if($_SESSION['userpart_session']=="Student"){
            header("Location: dashboard.php");
        }
    }
}

if(isset($_GET['clickedIteration'])){
    if($_GET['clickedIteration']){
        $_GET['clickedIteration']=false;
        if($_SESSION['userpart_session']=="Reviewer"){
            header("Location: iterationReviewer.php");
        }else if($_SESSION['userpart_session']=="Student"){
            header("Location: iterationStudent.php");
        }
    }
}

if(isset($_GET['clickedAssignments'])){
    if($_GET['clickedAssignments']){
        $_GET['clickedAssignments']=false;
        if($_SESSION['userpart_session']=="Reviewer"){
            header("Location: assignmentsReviewer.php");
        }else if($_SESSION['userpart_session']=="Student"){
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
    <title>Students</title>
    <link rel="stylesheet" href="styles/dashboard_style.css">
    <style><?php include "styles/dashboard_style.css" ?></style>
    <link rel="stylesheet" href="styles/allStudents_style.css">
    <style><?php include "styles/allStudents_style.css" ?></style>
    <script src="https://kit.fontawesome.com/765f34396c.js" crossorigin="anonymous"></script>
    <script>
        function addStudent(){

            let button=document.getElementsByClassName('addNewStudentButton');
            let hiddendiv=document.getElementsByClassName('addStudentDiv');

            if(button[0].innerHTML=="ADD STUDENT"){
                hiddendiv[0].style.display="block";
                button[0].style.backgroundColor="#CA4F4F";
                button[0].innerHTML="CLOSE";
            }else{
                hiddendiv[0].style.display="none";
                button[0].style.backgroundColor="#2786A7";
                button[0].innerHTML="ADD STUDENT";
            }
        }

        function removeStudent(){

            let removeButtonArray=document.getElementsByClassName('removeStudentButton');

            for(let i=0 ; i<removeButtonArray.length ; i++){
                if(document.activeElement == removeButtonArray[i]){

                    let pressedButtonId=removeButtonArray[i].id;
                    let studentEmailId="studentEmail"+pressedButtonId.charAt(pressedButtonId.length-1);
                    let studentEmail=document.getElementById(studentEmailId).innerHTML;
                    // let splittedStudentEmail=studentEmail.split("@");
                    // let tablename=splittedStudentEmail[0];

                    let xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function(){
                        if(this.readyState == 4 && this.status == 200){
                            // console.log(1);
                            document.getElementById(pressedButtonId).innerHTML="Removed";
                        }
                    }

                    xmlhttp.open("GET","removeStudent.php?studentEmail="+studentEmail,true);
                    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xmlhttp.send();
                }
            }
        }
    </script>
</head>
<body>

    <?php

    include "reviewer.php";
    $user=new User();
    $user->setUserParameters();

    
    $reviewer= new Reviewer();
    $reviewer->setUserParameters();

    $studentemail=$studentTablename=$hintTextStudentemail="";
    $assignment=$deadline=$status=$submittedOn=$reviewers=$suggestion=array("");
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        function ready_data($val){
            $val=trim($val);
            $val=stripslashes($val);
            $val=htmlspecialchars($val);
            return $val;
        }

        if(!empty($_POST['studentemail'])){
            $studentemail=ready_data($_POST['studentemail']);
            if(filter_var($studentemail, FILTER_VALIDATE_EMAIL)){
                $hintTextUseremail="";
                // $explodeArray=explode("@",$studentemail);
                // $studentTablename=$explodeArray[0];
            }else{
                $hintTextUseremail="Invalid email format";
            }
        }else{
            $hintTextStudentEmail="Email is Required";
        }

        for($i=0 ; $i<$_SESSION['assignmentCount'] ; $i++){

            // if(!empty($_POST['deadline'.strval($i)])){
            //     $deadline[$i]=ready_data($_POST['deadline'.strval($i)]);
            //     if(preg_match("/^\d{4}-\d{2}-\d{2}$/",$deadline[$i])){
            //         $hintTextDeadline="";
            //     }else{
            //         $hintTextDeadline="Invalid date format";
            //     }
            // }else{
            //     $hintTextDeadline="Deadline is Required";
            // }

            $assignment[$i]=ready_data($_POST['assignment'.strval($i)]);

            // $deadline[$i]=ready_data($_POST['deadline'.strval($i)]);

            if(!empty($_POST['status'.strval($i)])){
                $status[$i]=ready_data($_POST['status'.strval($i)]);
            }else{
                $status[$i]="Pending";
            }

            if(!empty($_POST['submittedOn'.strval($i)])){
                $submittedOn[$i]=ready_data($_POST['submittedOn'.strval($i)]);
            }else{
                $submittedOn[$i]=NULL;
            }

            if(!empty($_POST['reviewers'.strval($i)])){
                $reviewers[$i]=ready_data($_POST['reviewers'.strval($i)]);
            }else{
                $reviewers[$i]=NULL;
            }

            if(!empty($_POST['suggestion'.strval($i)])){
                $suggestion[$i]=ready_data($_POST['suggestion'.strval($i)]);
            }else{
                $suggestion[$i]=NULL;
            }
        }

        $allValid=empty($hintTextDeadline)&empty($hintTextStudentemail);

        if($allValid){
            $reviewer->addNewStudent($studentemail,$assignment,$status,$submittedOn,$reviewers,$suggestion);
            // $reviewer->addStudentToDatabase($studentemail,$studentTablename,$deadline,$status,$submittedOn,$reviewers,$suggestion);
        }

        unset($_POST);
    }
    ?>
    
    <?php
    $_SESSION["onPage_session"]="STUDENTS";

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
    
    // $studentsInfoArray=$reviewer->getAllStudentsUserInfo();
    $studentsInfoArray=$user->getStudentsBasicInfo();
    // $studentUsername="";

    if($studentsInfoArray->num_rows > 0){
        $i=0;
        while($studentInfo=$studentsInfoArray->fetch_assoc()){
            // $studentUsername=$reviewer->getUsername($studentInfo['useremail']);
            echo "
            <div class='studentInfoDiv'> 
                <div class='studentInfoImage'><i class='fa-solid fa-square-user'></i></div>
                <div class='studentInfoUsername'>".$user->showHyphenIfNull($studentInfo['username'])."</div>
                <div class='studentInfoUseremail' id='studentEmail".strval($i)."'>".$studentInfo['useremail']."</div>
            ";
            if($_SESSION['userpart_session']=="Reviewer"){
                echo "
                    <div class='removeStudentButtonDiv'><button class='removeStudentButton' id='removeStudentButton".strval($i)."' onClick='removeStudent()'>Remove</button></div>
                ";
            }
            echo "    
            </div>
            ";
            $i++;
        }
    }

    if($_SESSION['userpart_session']=="Reviewer"){

        echo "
        </div>
    
        <div class='addNewStudentButtonDiv'>
            <button class='addNewStudentButton' onClick='addStudent()'>ADD STUDENT</button>
        </div>
        <div class='addStudentDiv'>
        <div class='addStudentFormDiv'>
            <form class=addStudentForm action='' method='post'>
                <div class='addStudentFormSectionHeading'>Student Info</div>
                <div class='addStudentFormStudentInfoDiv'>
                    <label for='studentemail'>Student's Email:</label>
                    <input class= 'addStudentFormInput' id='addStudentFormEmailInput' type='email' name='studentemail' id='studentemail'>
                    <span class='hintText'>".$hintTextStudentemail."</span>
                </div>
                <div class='addStudentFormSectionHeading'>
                <div>Assignment Status</div>
                <div class='addStudentFormSectionHeadingDesc'>(Leave blank wherever no data is available)</div>
                </div>
                <div class='addStudentFormAssignmentDiv'>
        ";
    
        $assignmentNameRows=$reviewer->getAssignmentsRequiredInfo();
    
        if($assignmentNameRows->num_rows > 0){
            $i=0;
            while($name=$assignmentNameRows->fetch_assoc()){
    
                echo "
                    <div class='addStudentFormOneAssignment'>
                        <div class='addStudentFormAssignmentName'>".$name['assignment']."</div>
                        <div class='addStudentFormAssignmentDataDiv'>
                            <div class='addStudentFormAssignmentData'>
                                <div class='addStudentFormAssignmentDataPair'>
                                    <label for='deadline'>Deadline:</label>
                                    <input class='addStudentFormInput' type='text' name='deadline".strval($i)."' id='deadline' value='".$name['deadline']."' readonly>
                                </div>
                                <div class='addStudentFormAssignmentDataPair'>
                                    <label for='status'>Status:</label>
                                    <input class='addStudentFormInput' type='text' name='status".strval($i)."' id='status' placeholder='Done/Pending' pattern='(Done|Pending)'>
                                </div>
                                <div class='addStudentFormAssignmentDataPair'>
                                    <label for='submittedOn'>Submitted On:</label>
                                    <input class='addStudentFormInput' type='text' name='submittedOn".strval($i)."' id='submittedOn' placeholder='yyyy-mm-dd'>
                                </div>
                            </div>
                            <div class='addStudentFormAssignmentData'>
                                <div class='addStudentFormAssignmentDataPair'>
                                    <label for='reviewers'>Reviewers: (separated by comma)</label>
                                    <input class='addStudentFormInput' type='text' name='reviewers".strval($i)."' id='reviewers'>
                                </div>
                                <div class='addStudentFormAssignmentDataPair'>
                                    <label for='suggestion'>Suggestions: (separated by comma)</label>
                                    <input class='addStudentFormInput' type='text' name='suggestion".strval($i)."' id='suggestion'>
                                    <input type='hidden' name='assignment".strval($i)."' value='".$name['assignment']."'>
                                </div>
                            </div>
                        </div>     
                    </div>
                ";
    
                $i++;
            }
            $_SESSION['assignmentCount']=$i;
        }        
        
        echo "
                </div>
                <div class='formSubmit'>
                    <input id='formSubmitInput' type='submit' value='ADD'>
                </div>
            </form>
        </div>
        </div>
        ";    
    }

    ?>
    
</body>
</html>