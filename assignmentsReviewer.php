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
    <script>
        function showAddAssignmentDiv(){
            let clickedButton=document.getElementById('addAssignButton');
            let hiddenFormDiv=document.getElementById('addAssignForm');
            if(clickedButton.innerHTML=='ADD ASSIGNMENT'){
                clickedButton.innerHTML='CLOSE';
                clickedButton.style.backgroundColor='#CA4F4F';
                hiddenFormDiv.style.display='block';
            }else if(clickedButton.innerHTML=='CLOSE'){
                clickedButton.innerHTML='ADD ASSIGNMENT';
                clickedButton.style.backgroundColor='#2786A7';
                hiddenFormDiv.style.display='none';
            }
        }
    </script>
    <?php
    $_SESSION['onPage_session']="ASSIGNMENTS";

    $formname=$formdeadline=$formtopics=$formlink=$formresources=$formdescription="";
    $hintname=$hintdeadline=$hinttopics=$hintlink=$hintresources=$hintdescription="";
    
    include "reviewer.php";
    $reviewer=new Reviewer();
    $reviewer->setUserParameters();
    // $reviewer->setTablename();

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        function ready_data($val){
            $val=trim($val);
            $val=stripslashes($val);
            $val=htmlspecialchars($val);
            return $val;
        }
    
        if(!empty($_POST['name'])){
            $formname=ready_data($_POST['name']);
            $hintname="";
        }else{
            $hintname="Assignment name is Required";
        }

        if(!empty($_POST['deadline'])){
            $formdeadline=ready_data($_POST['deadline']);
            $hintdeadline="";
        }else{
            $hintdeadline="Deadline is Required";
        }

        if(!empty($_POST['topics'])){
            $formtopics=ready_data($_POST['topics']);
            $hinttopics="";
        }else{
            $formtopics="-";
        }

        if(!empty($_POST['description'])){
            $formdescription=ready_data($_POST['description']);
            $hintdescription="";
        }else{
            $hintdescription="Please add some description";
        }

        if(!empty($_POST['link'])){
            $formlink=ready_data($_POST['link']);
            $hintlink="";
        }else{
            $formlink="-";
        }

        if(!empty($_POST['resources'])){
            $formresources=ready_data($_POST['resources']);
            $hintresources="";
        }else{
            $formresources="-";
        }

        $ready=empty($hintname)&empty($hintdeadline)&empty($hintdescription);

        if($ready){
            $reviewer->insertNewAssignment($formname,$formtopics,$formdescription,$formdeadline,$formlink,$formresources);
            // $reviewer->addAssignmentToDatabase($formname,$formtopics,$formdescription,$formdeadline,$formresources,$formlink);
            $formname=$formdeadline=$formdescription=$formlink=$formresources=$formtopics="";
        }
        
    }

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
    <div class='sectionHeading'>ASSIGNMENTS</div>
    <div class='sectionContentAssignment'>
    ";   
    $assignmentTableRows=$reviewer->getAssignmentsTable();
    if($assignmentTableRows->num_rows > 0){
        $divCount=0;
        while($assignment=$assignmentTableRows->fetch_assoc()){

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
                        <div class='sectionContentDivDataValue'>".$reviewer->showHyphenIfNull($assignment['description'])."</div>
                </div>
                <div class='sectionContentSubDiv2'>
                        <div class='sectionContentDivDataHeading'>Assignment Link</div>
                        <div class='sectionContentDivDataValue'><a class='aLink' href='".$reviewer->showHyphenIfNull($assignment['assignmentlink'])."'>".$reviewer->showHyphenIfNull($assignment['links'])."</a></div>
                </div>
                <div class='sectionContentSubDiv2'>
                        <div class='sectionContentDivDataHeading'>Resources</div>
                        <div class='sectionContentDivDataValue'>";
                        if(!empty($assignment['resource'])){
                            $resources=$assignment['resource'];
                            $resourceArray=explode(",",$resources);
                            for($i=0 ; $i<count($resourceArray) ; $i++){
                                $resourceArray[$i]=trim($resourceArray[$i]);
                                echo "<div><a class='aLink' href='".$resourceArray[$i]."'>".$resourceArray[$i]."</a></div>";
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
    echo "
    </div>
    
    <div class='addNewAssignmentButtonDiv'>
        <button class='addNewAssignmentButton' id='addAssignButton' onClick='showAddAssignmentDiv()'>ADD ASSIGNMENT</button>
    </div>
    <div class='addAssignmentFormDiv' id='addAssignForm'>
        <form action='' method='post' class='form'>
            <div class='addAssignmentForm'>
                <div class='addAssignmentHeading'>Assignment Info</div>
                <div class='addAssignmentDiv1'>
                    <div class='addAssignmentblock'>
                        <label class='addAssignmentLabel' for='name'>Assignment Name:</label>
                        <div class='hintText'>".$hintname."</div>
                        <input type=text' id='name' name='name' class='addAssignmentInput' value='".$formname."'>
                    </div>
                    <div class='addAssignmentblock'>
                        <label class='addAssignmentLabel' for='deadline'>Deadline:</label>
                        <div class='hintText'>".$hintdeadline."</div>
                        <input type=text' id='deadline' name='deadline' class='addAssignmentInput' placeholder='yyyy-mm-dd' pattern='\d{4}-\d{2}-\d{2}' value='".$formdeadline."'>
                    </div>
                    <div class='addAssignmentblock'>
                        <label class='addAssignmentLabel' for='topics'>Topics:</label>
                        <div class='hintText'>".$hinttopics."</div>
                        <input type=text' id='topics' name='topics' class='addAssignmentInput' placeholder='Separated by commas' value='".$formtopics."'>
                    </div>
                </div>
                <div class='addAssignmentDiv2'>
                    <label class='addAssignmentLabel' for='description'>Description:</label>
                    <div class='hintText'>".$hintdescription."</div>
                    <input type='text' id='description' name='description' class='addAssignmentInput' value='".$formdescription."'>
                </div>
                <div class='addAssignmentDiv2'>
                    <label class='addAssignmentLabel' for='link'>Assignment Link:</label>
                    <div class='hintText'>".$hintlink."</div>
                    <input type='text' id='link' name='link' class='addAssignmentInput' value='".$formlink."'>
                </div>
                <div class='addAssignmentDiv2'>
                    <label class='addAssignmentLabel' for='resources'>Resources:</label>
                    <div class='hintText'>".$hintresources."</div>
                    <input type='text' id='resources' name='resources' class='addAssignmentInput' placeholder='Separated by commas' value='".$formresources."'>
                </div>
                <div class='formSubmitDiv'>
                <input class='formSubmitButton' type='submit' value='ADD'>
                </div>
            </div>
        </form>
    </div>
    </div>
    "; 

    ?>
    
</body>
</html>