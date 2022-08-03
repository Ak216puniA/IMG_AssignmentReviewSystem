<?php
session_start();

if(isset($_GET['clicked'])){
    if($_GET['clicked']){
        $_GET['clicked']=false;
        if($_COOKIE['userpart']=="Reviewer"){
            header("Location: dashboardReviewer.php");
        }else if($_COOKIE['userpart']=="Student"){
            header("Location: dashboard.php");
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
    <script src="https://kit.fontawesome.com/765f34396c.js" crossorigin="anonymous"></script>
    <script>
        function addStudent(){

            // let studentNo = prompt("Enter number of students to be added", "1");
            // $_SESSION['addStudentNo']=studentNo;
            // $_SESSION['addStudentNo']=(int)$_SESSION['addStudentNo'];

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
                // alert(i);
                if(document.activeElement == removeButtonArray[i]){

                    let pressedButtonId=removeButtonArray[i].id;
                    // alert(pressedButtonId);
                    let studentEmailId="studentEmail"+pressedButtonId.charAt(pressedButtonId.length-1);
                    let studentEmail=document.getElementById(studentEmailId).innerHTML;
                    let splittedStudentEmail=studentEmail.split("@");
                    let tablename=splittedStudentEmail[0];

                    // alert(tablename+" , "+studentEmail);

                    let xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function(){
                        if(this.readyState == 4 && this.status == 200){
                            // alert("Done");
                            pressedButtonId.innerHTML="Removed";
                            // pressedButtonId.style.opacity="0.6";
                        }
                    }

                    xmlhttp.open("GET","removeStudent.php?tablename="+tablename+"&studentEmail="+studentEmail,true);
                    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xmlhttp.send();
                }
            }
        }

        // function ifStudent(){
        //     if($_COOKIE['userpart']=="Student"){
        //         alert("Entered");
        //         let removeButtonDivArray=document.getElementsByClassName('removeStudentButtonDiv');
        //         for(let i=0 ; i<removeButtonDivArray.length ; i++){
        //             removeButtonDivArray[i].style.display="none";
        //         }
        //         let addStudentButtonDiv=document.getElementsByClassName('addNewStudentButtonDiv')[0];
        //         addStudentButtonDiv.style.display="none";
        //         let addStudentDiv=document.getElementsByClassName('addStudentDiv')[0];
        //         addStudentDiv.style.display="none";
        //     }
        // }
    </script>
</head>
<body>

    <?php

    // if($_GET['clicked']){
    //     $_GET['clicked']=false;
    //     if($_COOKIE['userpart']=="Reviewer"){
    //         header("Location: dashboardReviewer.php");
    //     }else if($_COOKIE['userpart']=="Student"){
    //         header("Location: dashboard.php");
    //     }
    // }

    include "reviewer.php";

    $reviewer= new Reviewer();
    $reviewer->getUserParameters();
    $reviewer->setTablename();

    $studentemail=$studentTablename=$hintTextStudentemail=$hintTextDeadline="";
    $deadline=$status=$submittedOn=$reviewers=$suggestion=array("");
    
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
                $explodeArray=explode("@",$studentemail);
                $studentTablename=$explodeArray[0];
            }else{
                $hintTextUseremail="Invalid email format";
            }
        }else{
            $hintTextStudentEmail="Email is Required";
        }

        for($i=0 ; $i<$_SESSION['assignmentCount'] ; $i++){

            if(!empty($_POST['deadline'.strval($i)])){
                $deadline[$i]=ready_data($_POST['deadline'.strval($i)]);
                if(preg_match("/^\d{4}-\d{2}-\d{2}$/",$deadline[$i])){
                    $hintTextDeadline="";
                }else{
                    $hintTextDeadline="Invalid date format";
                }
            }else{
                $hintTextDeadline="Deadline is Required";
            }

            if(!empty($_POST['status'.strval($i)])){
                $status[$i]=ready_data($_POST['status'.strval($i)]);
            }else{
                $status[$i]="-";
            }

            if(!empty($_POST['submittedOn'.strval($i)])){
                $submittedOn[$i]=ready_data($_POST['submittedOn'.strval($i)]);
            }else{
                $submittedOn[$i]=NULL;
            }

            if(!empty($_POST['reviewers'.strval($i)])){
                $reviewers[$i]=ready_data($_POST['reviewers'.strval($i)]);
            }else{
                $reviewers[$i]="-";
            }

            if(!empty($_POST['suggestion'.strval($i)])){
                $suggestion[$i]=ready_data($_POST['suggestion'.strval($i)]);
            }else{
                $suggestion[$i]="-";
            }
        }

        $allValid=empty($hintTextDeadline)&empty($hintTextStudentemail);

        if($allValid){
            $reviewer->addStudentToDatabase($studentemail,$studentTablename,$deadline,$status,$submittedOn,$reviewers,$suggestion);
        }

        unset($_POST);
    }
    ?>
    
    <?php
    $_SESSION["onPage_session"]="STUDENTS";

    include "header.php";
    echo "
        <div class='pageLinksDiv'>
            <button class='pageLink'><a href='allStudents.php?clicked=true'>Dashboard</a></button>
            <button class='pageLink'>Profile</button>
            <button class='pageLink'>Reviewers</button>
        </div>
    </div>

    <div class='allStudentsDiv'>
    ";
    
    $studentsInfoArray=$reviewer->getAllStudentsUserInfo();
    $studentUsername="";

    if($studentsInfoArray->num_rows > 0){
        $i=0;
        while($studentInfo=$studentsInfoArray->fetch_assoc()){
            $studentUsername=$reviewer->getUsername($studentInfo['useremail']);
            echo "
            <div class='studentInfoDiv'> 
                <div class='studentInfoImage'><i class='fa-solid fa-square-user'></i></div>
                <div class='studentInfoUsername'>".$studentUsername."</div>
                <div class='studentInfoUseremail' id='studentEmail".strval($i)."'>".$studentInfo['useremail']."</div>
                <div class='removeStudentButtonDiv'><button class='removeStudentButton' id='removeStudentButton".strval($i)."' onClick='removeStudent()'>Remove</button></div>
            </div>
            ";
            $i++;
        }
    }

    if($_COOKIE['userpart']=="Reviewer"){

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
    
        $assignmentNameRows=$reviewer->getAssignmentNameArray();
    
        if($assignmentNameRows->num_rows > 0){
            $i=0;
            while($name=$assignmentNameRows->fetch_assoc()){
    
                echo "
                    <div class='addStudentFormOneAssignment'>
                        <div class='addStudentFormAssignmentName'>".$name['name']."</div>
                        <div class='addStudentFormAssignmentDataDiv'>
                            <div class='addStudentFormAssignmentData'>
                                <div class='addStudentFormAssignmentDataPair'>
                                    <label for='deadline'>Deadline:</label>
                                    <input class='addStudentFormInput' type='text' name='deadline".strval($i)."' id='deadline' placeholder='yyyy-mm-dd' pattern='\d{4}-\d{2}-\d{2}'>
                                    <span class='hintText'>".$hintTextDeadline."</span>
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

    <!-- <script>
        function addStudent(){

            // let studentNo = prompt("Enter number of students to be added", "1");
            // $_SESSION['addStudentNo']=studentNo;
            // $_SESSION['addStudentNo']=(int)$_SESSION['addStudentNo'];

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
                // alert(i);
                if(document.activeElement == removeButtonArray[i]){

                    let pressedButtonId=removeButtonArray[i].id;
                    // alert(pressedButtonId);
                    let studentEmailId="studentEmail"+pressedButtonId.charAt(pressedButtonId.length-1);
                    let studentEmail=document.getElementById(studentEmailId).innerHTML;
                    let splittedStudentEmail=studentEmail.split("@");
                    let tablename=splittedStudentEmail[0];

                    // alert(tablename+" , "+studentEmail);

                    let xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange=function(){
                        if(this.readyState == 4 && this.status == 200){
                            // alert("Done");
                            pressedButtonId.innerHTML="Removed";
                            // pressedButtonId.style.opacity="0.6";
                        }
                    }

                    xmlhttp.open("GET","removeStudent.php?tablename="+tablename+"&studentEmail="+studentEmail,true);
                    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xmlhttp.send();
                }
            }
        }

        // function ifStudent(){
        //     if($_COOKIE['userpart']=="Student"){
        //         alert("Entered");
        //         let removeButtonDivArray=document.getElementsByClassName('removeStudentButtonDiv');
        //         for(let i=0 ; i<removeButtonDivArray.length ; i++){
        //             removeButtonDivArray[i].style.display="none";
        //         }
        //         let addStudentButtonDiv=document.getElementsByClassName('addNewStudentButtonDiv')[0];
        //         addStudentButtonDiv.style.display="none";
        //         let addStudentDiv=document.getElementsByClassName('addStudentDiv')[0];
        //         addStudentDiv.style.display="none";
        //     }
        // }
    </script> -->
    
</body>
</html>