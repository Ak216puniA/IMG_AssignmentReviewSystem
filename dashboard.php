<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles/dashboard_style.css">
    <style><?php include "styles/dashboard_style.css" ?></style>
    <script src="https://kit.fontawesome.com/765f34396c.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php
    $_SESSION["onPage_session"]="DASHBOARD";

    include "student.php";
    $student = new student();
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
    ";

    // if($student->checkIfRegisteredByReviewers()){

        echo "
        <div class='allAssignments'>
            <div class='assignmentOverviewDiv' id='assignmentNamesDiv'>
                <div class='assignmentOverviewHeading'>ASSIGNMENTS</div>    
                <div class='assignmentNames'>
        ";
    
                    $assignments_rows=$student->getAssignmentsRequiredInfo();
                    if($assignments_rows->num_rows > 0){
                        while($assignment=$assignments_rows->fetch_assoc()){
                            echo "<div class='assignmentOverviewValue' id='oneAssignmentName'>".$assignment['assignment']."</div>";
                        }
                    }else{
                        echo "<div>-</div>";
                    }
    
        echo "
                </div>
            </div>
            <div class='assignmentOverviewDiv' id='completedAssignmentsDiv'>
                <div class='assignmentOverviewHeading'>COMPLETED ASSIGNMENTS</div>
                <div class='assignmentOverviewValue' id='completedAssignmentsScore'>
        ";
                    
                    // $completedAssignmentCount=$student->completedAssignmentCount();
                    // $totalAssignmentsCount=$student->getTotalAssignmentCount();
                    $status_count_array=$student->getAssignmentsStatusNumberArray();
                    echo $status_count_array[0]." / ".$status_count_array[1];
        
        echo "
                </div>
                <div class='assignmentOverviewValue' id='completedAssignmentsDesc'>
                    <div class='innerDivCompletedAssignmentDesc'>
                        <div>Completed</div>
                        <div>assignments</div>
                    </div>
                    <div id='slashCompletedAssignmentDesc'>
                        /
                    </div>
                    <div class='innerDivCompletedAssignmentDesc'>
                        <div>Total</div>
                        <div>assignments</div>
                    </div>
                </div>
            </div>
            <div class='assignmentOverviewDiv' id='piechartDiv'>
                <div class='assignmentOverviewHeading'>ON TIME SUBMISSIONS</div>
        ";

        $noSubmissonsArray=$student->getDoneSubmissionNumber();
        
        echo "
                <div id='piechart'></div>
                <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
    
                <script type='text/javascript'>
    
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(drawChart);
    
                function drawChart() {
                var data = google.visualization.arrayToDataTable([
                ['Assignments', 'Number'],
                ['OnTime Submissions', ".$noSubmissonsArray[0]."],
                ['Late Submissions', ".$noSubmissonsArray[1]."],
                ]);
    
                var options = {
                    title:'', 
                    width:305, 
                    height:160, 
                    colors:['#8F51B5','#B167DE'], 
                     
                    backgroundColor: {fill:'transparent' , stroke:'#30AED8'}, 
                    chartArea:{left:16,top:8,width:'90%',height:'90%'}, 
                    fontSize:12, 
                    color:'white'};
    
                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                chart.draw(data, options);
                }
                </script>
            </div>
        </div>
    
        <div class='section'>
            <div class='sectionHeading'>ASSIGNMENT STATUS</div>
            <div class='assignmentStatusDiv'>
        ";
    
                // $assignmnetsArray=$student->getAssignmentArray();
    
                // if($assignmnetsArray->num_rows > 0){
                //     $divCount=0;
                //     while($assignment=$assignmnetsArray->fetch_assoc()){
    
                //         if(empty($assignment['submittedOn'])){
                //             $submittedOn="-";
                //         }else{
                //             $submittedOn=$assignment['submittedOn'];
                //         }
    
                //         if(empty($assignment['reviewers'])){
                //             $reviewers=array("-");
                //         }else{
                //             $reviewers=explode(",",$assignment['reviewers']);
                //             for($i=0 ; $i<count($reviewers) ; $i++){
                //                 $reviewers[$i]=trim($reviewers[$i]);
                //             }
                //         }

                $studentTable=$student->getStudentAssignmentTable();
                // $assignments_rows=$student->getAssignmentsRequiredInfo();
                if($studentTable->num_rows > 0){
                    $divCount=0;
                    while($assignment=$studentTable->fetch_assoc()){
                        $student_reviewer_rows=$student->getStudentReviewers($assignment['assignment']);
                        $reviewer_array=array("");
                        // $comment_array=array("");
                        if($student_reviewer_rows->num_rows > 0){
                            $i=0;
                            while($reviewer_name=$student_reviewer_rows->fetch_assoc()){
                                // echo "<div>- ".$reviewer_name['reviewer']."</div>";
                                $reviewer_array[$i]=$reviewer_name['reviewer'];
                                // $comment_array[$i]=$reviewer_name['comment'];
                                $i++;
                            }
                        }

        echo "
                        <div class='assignmentBar'>
                            <div class='assignmentData' id='name".$divCount."'>".$assignment['assignment']."</div>
                            <div class='assignmentData'>".$assignment['finalstatus']."</div>
                            <div class='assignmentData'>
                                <button class='viewButton' id='viewButton".strval($divCount)."' onClick='showAssignmentDesc()'>View</button>
                            </div>
                            <script>
                            function showAssignmentDesc(){
                                var viewButtonArray=document.getElementsByClassName('viewButton');
                                for(var i=0 ; i<viewButtonArray.length ; i++){
                    
                                    if(document.activeElement == viewButtonArray[i]){
                                        var id = viewButtonArray[i].id;
                                        var assignmentDesc=document.getElementById('assignmentDesc'+id.charAt(id.length-1));
                                        if(viewButtonArray[i].innerHTML == 'View'){
                                            assignmentDesc.style.display='block';
                                            viewButtonArray[i].innerHTML='Close';
                                            viewButtonArray[i].style.backgroundColor='#CA4F4F';
                                        }else{
                                            assignmentDesc.style.display='none';
                                            viewButtonArray[i].innerHTML='View';
                                            viewButtonArray[i].style.backgroundColor='#2786A7';
                                        }
                                    }
                                }
                            }
                            </script>
                        </div>
                        <div class='assignmentDesc' id='assignmentDesc".strval($divCount)."'>
                            <div class='assignmentDescData'>
                                <div class='assignmentData assignmentDataHidden'>
                                    <div class='assignmentDataHeading'>Deadline</div>
                                    <div class='assignmentDataValue'>".$assignment['deadline']."</div>
                                </div>
                                <div class='assignmentData assignmentDataHidden'>
                                    <div class='assignmentDataHeading'>Last Review Date</div>
                                    <div class='assignmentDataValue'>".$student->showHyphenIfNull($assignment['submittedOn'])."</div>
                                </div>
                                <div class='assignmentData assignmentDataHidden'>
                                    <div class='assignmentDataHeading'>Reviewers</div>
                                    <div class='assignmentDataValue'>
        ";
                        
                        // for($i=0 ; $i<count($reviewers) ; $i++){
                        //     echo "<div>".$reviewers[$i]."</div>";
                        // }

                        // $student_reviewer_rows=$student->getStudentReviewers($assignment['assignment']);
                        // if($student_reviewer_rows->num_rows > 0){
                        //     while($reviewer_name=$student_reviewer_rows->fetch_assoc()){
                        //         echo "<div>".$reviewer_name['reviewer']."</div>";
                        //     }
                        // }

                        // $student_reviewer_rows=$student->getStudentReviewers($assignment['assignment']);
                        // $reviewer_array=array("");
                        // $comment_array=array("");
                        // if($student_reviewer_rows->num_rows > 0){
                        //     $i=0;
                        //     while($reviewer_name=$student_reviewer_rows->fetch_assoc()){
                        //         // echo "<div>- ".$reviewer_name['reviewer']."</div>";
                        //         $reviewer_array[$i]=$reviewer_name['reviewer'];
                        //         $comment_array[$i]=$reviewer_name['comment'];
                        //         $i++;
                        //     }
                        // }

                        for($i=0;$i<count($reviewer_array);$i++){
                            echo "<div>- ".$reviewer_array[$i]."</div>";
                        }
    
        echo "
                                    </div>
                                </div>
                            </div>
                            <div class='updateAssignmentButtonDiv'>
                                <button class='updateAssignmentButton' id='add".$divCount."' onClick='setCurrentInDatabase(`true`)'>Add to Current Assignments</button>
                            </div>
                            <script>
                            function setCurrentInDatabase(update){

                                var updateAssignmentButtonArray=document.getElementsByClassName('updateAssignmentButton');
                                var clickedOnButtonId='';
                                var assignmentName='';
                                for(var i=0 ; i<updateAssignmentButtonArray.length ; i++){
                                    if(document.activeElement == updateAssignmentButtonArray[i]){
                                        assignmentName='name'+updateAssignmentButtonArray[i].id.charAt(updateAssignmentButtonArray[i].id.length-1);
                                        assignmentName=document.getElementById(assignmentName).innerHTML;
                                        clickedOnButtonId=updateAssignmentButtonArray[i].id;
                                    }
                                }
                    
                                if(document.getElementById(clickedOnButtonId).innerHTML!='Added to Current Assignments'){
                                    if(document.getElementById(clickedOnButtonId).innerHTML!='Removed from Current Assignments')
                                    xmlhttp = new XMLHttpRequest();
                                    xmlhttp.onreadystatechange=function(){
                                        if(this.readyState == 4 && this.status == 200){
                                            if(clickedOnButtonId.charAt(0) == 'a'){
                                                document.getElementById(clickedOnButtonId).innerHTML='Added to Current Assignments';
                                            }else if(clickedOnButtonId.charAt(0) == 'r'){
                                                document.getElementById(clickedOnButtonId).innerHTML='Removed from Current Assignments';
                                            }else{
                                                document.backgroundColor='yellow';
                                            }
                                            console.log(this.response);
                                        }
                                    }
                    
                                    console.log(clickedOnButtonId+' , '+assignmentName+' , '+update);
                    
                                    xmlhttp.open('GET', './dashboardUpdateButton.php?buttonId=addremovecurrent&name='+assignmentName+'&update='+update+'&userpart=Student', true);
                                    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                                    xmlhttp.send();
                                } 
                            }
                    
                            </script>
                        </div>
        ";
                        $divCount=$divCount+1;
                    }
                }
            
        echo "
            </div>
        </div>
    
        <div id='testdiv'></div>
    
        <div class='section'>
            <div class='sectionHeading'>CURRENT ASSIGNMENTS</div>
            <div class='assignmentStatusDiv'>
        ";
    
                // $currentAssignmentArray=$student->getCurrentAssignmentArray();
    
                // if($currentAssignmentArray->num_rows > 0){
                //     while($assignment=$currentAssignmentArray->fetch_assoc()){
    
                //         $todayDate=date("y-m-d");
                //         $todayTimeStamp=strtotime($todayDate);
                //         if(empty($assignment['submittedOn'])){
                //             $deadlineTimeStamp=strtotime($assignment['deadline']);
                //             $diff=$deadlineTimeStamp-$todayTimeStamp;
                //             $daysLeft=$diff/(3600*24);
                //         }else{
                //             $submittedOn=$assignment['submittedOn'];
                //             $submitTimeStamp=strtotime($submittedOn);
                //             $diff=$todayTimeStamp-$submitTimeStamp;
                //             $daysLeft=$diff/(3600*24);
                //             $daysLeft="Submitted ".$daysLeft." days ago";
                //         }
    
                //         if(empty($assignment['reviewers'])){
                //             $reviewers=array("-");
                //         }else{
                //             $reviewers=explode(",",$assignment['reviewers']);
                //             for($i=0 ; $i<count($reviewers) ; $i++){
                //                 $reviewers[$i]=trim($reviewers[$i]);
                //             }
                //         }
    
                //         if(empty($assignment['suggestion'])){
                //             $suggestion=array("");
                //         }else{
                //             $suggestion=explode(",",$assignment['suggestion']);
                //             for($i=0 ; $i<count($suggestion) ; $i++){
                //                 $suggestion[$i]=trim($suggestion[$i]);
                //             }
                //         }

                $current_assignments_rows=$student->getCurrentAssignments();
                if($current_assignments_rows->num_rows > 0){
                    while($current_assignment=$current_assignments_rows->fetch_assoc()){
                        $student_reviewer_rows=$student->getStudentReviewers($assignment['assignment']);
                        $reviewer_array=array("");
                        $comment_array=array("");
                        if($student_reviewer_rows->num_rows > 0){
                            $i=0;
                            while($reviewer_name=$student_reviewer_rows->fetch_assoc()){
                                // echo "<div>- ".$reviewer_name['reviewer']."</div>";
                                $reviewer_array[$i]=$reviewer_name['reviewer'];
                                $comment_array[$i]=$reviewer_name['comment'];
                                $i++;
                            }
                        }
                        $todayDate=date("y-m-d");
                        $todayDate=strtotime($todayDate);
                        $daysLeft="";
                        if($current_assignment['finalstatus']=="Done"){
                            $submittedOn=strtotime($current_assignment['finalsubmittedOn']);
                            $diff=$todayDate-$submittedOn;
                            $daysLeft="Submitted ".($diff/(3600*24))." days ago";
                        }else{
                            $deadline=strtotime($current_assignment['deadline']);
                            $diff=$deadline-$todayDate;
                            $daysLeft=$diff/(3600*24);
                        }
                        
        echo "
                        <div class='assignmentBar'>
                            <div class='assignmentData' id='name".$divCount."'>".$current_assignment['assignment']."</div>
                            <div class='assignmentData assignmentDataInvisible'>".$assignment['status']."</div>
                            <div class='assignmentData'>
                                <button class='viewButton' id='viewButton".strval($divCount)."' onClick='showAssignmentDesc()'>View</button>
                            </div>
                        </div>
                        <div class='assignmentDesc' id='assignmentDesc".strval($divCount)."'>
                            <div class='assignmentDescData'>
                                <div class='assignmentData assignmentDataHidden assignmentDataCurrentAssignment'>
                                    <div class='assignmentDataHeading'>Deadline</div>
                                    <div class='assignmentDataValue'>".$current_assignment['deadline']."</div>
                                </div>
                                <div class='assignmentData assignmentDataHidden assignmentDataCurrentAssignment'>
                                    <div class='assignmentDataHeading'>Days Left</div>
                                    <div class='assignmentDataValue'>".$daysLeft."</div>
                                </div>
                                <div class='assignmentData assignmentDataHidden assignmentDataCurrentAssignment'>
                                    <div class='assignmentDataHeading'>Reviewed By</div>
                                    <div class='assignmentDataValue'>
        ";
                        
                                    // $student_reviewer_rows=$student->getStudentReviewers($assignment['assignment']);
                                    // $comment_array=array("");
                                    // if($student_reviewer_rows->num_rows > 0){
                                    //     $i=0;
                                    //     while($reviewer_name=$student_reviewer_rows->fetch_assoc()){
                                    //         echo "<div>- ".$reviewer_name['reviewer']."</div>";
                                    //         $comment_array[$i]=$reviewer_name['comment'];
                                    //         $i++;
                                    //     }
                                    // }
                                    for($i=0 ; $i<count($reviewer_array) ; $i++){
                                        echo "<div>- ".$reviewer_array[$i]."</div>";
                                    }
    
        echo "
                                    </div>
                                </div>
                                <div class='assignmentData assignmentDataHidden assignmentDataCurrentAssignment'>
                                    <div class='assignmentDataHeading'>Suggestions</div>
                                    <div class='assignmentDataValue'>
        ";
    
                                    for($i=0 ; $i<count($comment_array) ; $i++){
                                        if(!empty($comment_array[$i])){
                                            echo "<div>- ".$comment_array[$i]."</div>";
                                        }
                                    }
                        
        echo "
                                    </div>
                                </div>
                            </div>
        ";
                            // $assignmentLink=$student->showHyphenIfNull($student->getIterationAssignmentLink($assignment['assignmentName']));
                            $studentlink=$student->getStudentLink($current_assignment['assignment']);
        echo "
                            <div class='iterationLinkDiv'>
                                <div class='iterationLinkHeading'>Assignment Link</div>
                                <div class='iterationLinkValue' id='link".strval($divCount)."'><a class='aLink' href='".$studentLink."'>".$studentLink."</a></div>
                                <div class='addLinkFormDiv' id='addLink".strval($divCount)."'>
                                    <form action='./dashboardUpdateButton.php' method='POST'>
                                        <div class='linkForm'>
                                            <label for='assignmentLink' class='assignmentLinkLabel'>Enter your Assignment Link</label>
                                            <input type='text' name='assignmentLink' id='assignmentLink' class='linkInput'>
                                            <input type='submit' name='submitLink' value='Update!'>
                                            <input type='hidden' name='userpart' value='Student'>
                                            <input type='hidden' name='assignment' value='".$current_assignment['assignment']."'>
                                        </div>
                                    </form>
                                </div> 
                            </div> 
                            <div class='updateAssignmentButtonDiv'>
        ";
                            if(strcmp($current_assignment['finalstatus'],'Done')!=0){
                                echo "<button class='updateAssignmentButton updateAssignmentButtonIteration' id='file".strval($divCount)."' onClick='askAssignmentLink()'>Update Assignment Link</button>";
                                echo "
                                    <button class='updateAssignmentButton updateAssignmentButtonIteration' id='iteration".strval($divCount)."' onClick='addInIterationTable()'>Ask for Iteration</button>    
                                ";
                            }
        echo "                    
                                <button class='updateAssignmentButton' id='remove".strval($divCount)."' onClick='setCurrentInDatabase(`false`)'>Remove from Current Assignments</button>
                            </div>
                            <script>
                            function addInIterationTable(){

                                let clickedButtonId=document.activeElement.id;
                                let clickedButton=document.getElementById(clickedButtonId);
                    
                                if(clickedButton.innerHTML=='Ask for Iteration'){
                                    let divCount=clickedButtonId.charAt(clickedButtonId.length - 1);
                                    if(document.getElementById('link'+divCount).innerHTML!='-' && document.getElementById('link'+divCount).innerHTML!=null){
                                        let assignmentName=document.getElementById('name'+divCount).innerHTML;
                    
                                        xmlhttp=new XMLHttpRequest();
                                        xmlhttp.onreadystatechange=function(){
                                            if(this.readyState==4 && this.status==200){
                                                console.log(this.response);
                                                clickedButton.innerHTML='Asked!';
                                                clickedButton.style.bacgroundColor='#2FAAD5';
                                            }
                                        }
                    
                                        xmlhttp.open('GET', './dashboardUpdateButton.php?buttonId=iteration&name='+assignmentName+'&userpart=Student', true);
                                        xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                                        xmlhttp.send();
                                    }else{
                                        alert('Please attach file before asking for iteration!');
                                    }
                                }
                            }

                            function setCurrentInDatabase(update){

                                var updateAssignmentButtonArray=document.getElementsByClassName('updateAssignmentButton');
                                var clickedOnButtonId='';
                                var assignmentName='';
                                for(var i=0 ; i<updateAssignmentButtonArray.length ; i++){
                                    if(document.activeElement == updateAssignmentButtonArray[i]){
                                        assignmentName='name'+updateAssignmentButtonArray[i].id.charAt(updateAssignmentButtonArray[i].id.length-1);
                                        assignmentName=document.getElementById(assignmentName).innerHTML;
                                        clickedOnButtonId=updateAssignmentButtonArray[i].id;
                                    }
                                }
                    
                                if(document.getElementById(clickedOnButtonId).innerHTML!='Added to Current Assignments'){
                                    if(document.getElementById(clickedOnButtonId).innerHTML!='Removed from Current Assignments')
                                    xmlhttp = new XMLHttpRequest();
                                    xmlhttp.onreadystatechange=function(){
                                        if(this.readyState == 4 && this.status == 200){
                                            if(clickedOnButtonId.charAt(0) == 'a'){
                                                document.getElementById(clickedOnButtonId).innerHTML='Added to Current Assignments';
                                            }else if(clickedOnButtonId.charAt(0) == 'r'){
                                                document.getElementById(clickedOnButtonId).innerHTML='Removed from Current Assignments';
                                            }else{
                                                document.backgroundColor='yellow';
                                            }
                                            console.log(this.response);
                                        }
                                    }
                    
                                    console.log(clickedOnButtonId+' , '+assignmentName+' , '+update);
                    
                                    xmlhttp.open('GET', './dashboardUpdateButton.php?buttonId=addremovecurrent&name='+assignmentName+'&update='+update+'&userpart=Student', true);
                                    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                                    xmlhttp.send();
                                } 
                            }

                            function askAssignmentLink(){
                                let clickedButtonId=document.activeElement.id;
                                let clickedButton=document.getElementById(clickedButtonId);
                    
                                let divCount=clickedButtonId.charAt(clickedButtonId.length - 1);
                                let hiddenLinkForm=document.getElementById('addLink'+divCount);
                    
                                if(clickedButton.innerHTML=='Update Assignment Link'){
                                    hiddenLinkForm.style.display='block';
                                    clickedButton.innerHTML='Add your Assignment Link!';
                                    clickedButton.style.backgroundColor='#2FAAD5';
                                }
                    
                            }
                    
                            </script>
                        </div>
        ";
                        $divCount=$divCount+1;
                    }
                }
        
        echo "
            </div>
        </div>
        ";

    // }else{
    //     echo "
    //         <div class='notRegisteredDiv'>
    //             <div class='notRegisteredWarning' id='warningLine1'>User not registered!</div>
    //             <div class='notRegisteredWarning' id='warningLine2'>Ask a reviewer to add you in the student list</div>
    //         </div>
    //     ";
    // }
 
    ?>
</body>
</html>