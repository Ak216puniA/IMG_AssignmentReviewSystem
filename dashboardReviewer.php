<?php
session_start();
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles/dashboard_style.css">
    <style><?php include "styles/dashboard_style.css"; ?></style>
    <script src="https://kit.fontawesome.com/765f34396c.js" crossorigin="anonymous"></script>
</head>
<body>
    <script>
        function showStudentStatusDesc(){
            
            let clickedButtonId=document.activeElement.id;
            let clickedButton=document.getElementById(clickedButtonId);
    
            let studentStatusDescId="assignmentDesc"+clickedButtonId.charAt(clickedButtonId.length - 1);
            let studentStatusDesc=document.getElementById(studentStatusDescId);

            if(clickedButton.innerHTML == "View"){
                clickedButton.innerHTML="Close";
                clickedButton.style.backgroundColor="#CA4F4F";
                studentStatusDesc.style.display="block";
            }else if(clickedButton.innerHTML=="Close"){
                clickedButton.innerHTML="View";
                clickedButton.style.backgroundColor="#2786A7";
                studentStatusDesc.style.display="none";
            }
        }

        function showCompleteStudentStatus(studentemail){

            let clickedButtonId=document.activeElement.id;
            let clickedButton=document.getElementById(clickedButtonId);

            let completeStudentStatusId="completeStudentStatus"+clickedButtonId.charAt(clickedButtonId.length - 1);
            let completeStudentStatus=document.getElementById(completeStudentStatusId);
            
            xmlhttp=new XMLHttpRequest();
            xmlhttp.onreadystatechange=function(){
                if(this.readyState==4 && this.status==200){

                    if(clickedButton.innerHTML=="View Complete Status"){
                        completeStudentStatus.innerHTML=this.responseText;
                        completeStudentStatus.style.display="block";
                        clickedButton.innerHTML="Close";
                        clickedButton.style.backgroundColor="#CA4F4F";
                    }else if(clickedButton.innerHTML=="Close"){
                        completeStudentStatus.innerHTML="";
                        completeStudentStatus.style.display="none";
                        clickedButton.innerHTML="View Complete Status";
                        clickedButton.style.backgroundColor="#2786A7";
                    }

                }
            }

            xmlhttp.open("POST","getCompleteStudentStatus.php",true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("studentemail="+studentemail);
        }

        function markStatusDone(studentEmail){

            let clickedButtonId=document.activeElement.id;
            let clickedButton=document.getElementById(clickedButtonId);

            if(clickedButton.innerHTML=="Mark 'Done'"){
                let divCount=clickedButtonId.charAt(clickedButtonId.length - 1);
                let assignmentName=document.getElementById("assignmentName"+divCount).innerHTML;

                xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function(){
                    if(this.readyState==4 && this.status==200){
                        clickedButton.innerHTML="Marked!";
                        clickedButton.style.backGroundColor="#2FAAD5";
                    }
                }

                xmlhttp.open("GET","./dashboardUpdateButton.php?buttonId=done&studentEmail="+studentEmail+"&assignmentName="+assignmentName+"&userpart=Reviewer", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send();
            }
        }

        function askComment(){
            console.log("1");
            let clickedButtonId=document.activeElement.id;
            console.log(clickedButtonId);
            let clickedButton=document.getElementById(clickedButtonId);
            console.log(clickedButton.innerHTML);

            if(clickedButton.innerHTML == 'Comment'){
                console.log("2");
                let divCount=clickedButtonId.charAt(clickedButtonId.length - 1);
                console.log(divCount);
                let hiddenComment=document.getElementById('commentDiv'+divCount);
                hiddenComment.style.display='block';
                clickedButton.innerHTML='Enter comment!';
                clickedButton.style.backgroundColor='#2FAAD5';
            }    
        }
    </script>
    <?php
    $_SESSION["onPage_session"]="DASHBOARD";

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

    <div class='allAssignments'>
        <div class='assignmentOverviewDiv' id='assignmentNamesDiv'>
            <div class='assignmentOverviewHeading'>ASSIGNMENTS</div>    
            <div class='assignmentNames'>
    ";

                $totalAssignmentNumber=0;
                $doneAssignmentNumber=0;
                $pendingAssignmentNumber=0;
                $assignments_rows=$reviewer->getAssignmentsRequiredInfo();
                if($assignments_rows->num_rows > 0){
                    while($assignment=$assignments_rows->fetch_assoc()){
                        echo "<div class='assignmentOverviewValue' id='oneAssignmentName'>".$assignment['assignment']."</div>";
                        $totalAssignmentNumber++;
                    }
                }else{
                    echo "<div>-</div>";
                }

    echo "
            </div>
        </div>
        <div class='assignmentOverviewDiv' id='assignmentNamesDiv'>
            <div class='assignmentOverviewHeading'>STUDENTS</div>    
            <div class='assignmentNames'>
    ";

                // $allStudentNamesRows=$reviewer->getAllStudentNames();
                // if($allStudentNamesRows->num_rows > 0){
                //     while($row=$allStudentNamesRows->fetch_assoc()){
                //         echo "<div class='assignmentOverviewValue' id='oneAssignmentName'>".$row['username']."</div>";
                //     }
                // }

                $student_username_rows=$reviewer->getStudentNames();
                if($student_username_rows->num_rows > 0){
                    while($student_username=$student_username_rows->fetch_assoc()){
                        echo "<div class='assignmentOverviewValue' id='oneAssignmentName'>".$student_username['username']."</div>";
                    }
                }
    
    echo "
            </div>
        </div>
        <div class='assignmentOverviewDiv' id='piechartDiv'>
            <div class='assignmentOverviewHeading'>CURRENT ASSIGNMENT STATUS</div>
    ";

    // $currentAssignmentStatus=$reviewer->getAllStudentsAssignmentPiechart();
    $lastAssignmentStatusNumber=$reviewer->getLastAssignmentStatusNumber();
    
    echo "
            <div id='piechart'></div>
            <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>

            <script type='text/javascript'>

            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
            var data = google.visualization.arrayToDataTable([
            ['Students', 'Number'],
            ['Done', ".$lastAssignmentStatusNumber[0]."],
            ['Pending', ".$lastAssignmentStatusNumber[1]."],
            ]);

            var options = {
                title:'', 
                width:290, 
                height:200, 
                colors:['#8F51B5','#B167DE'], 
                 
                backgroundColor: {fill:'transparent' , stroke:'#30AED8'}, 
                chartArea:{left:16,top:8,width:'90%',height:'90%'}, 
                fontSize:13, 
                color:'white'};

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
            }
            </script>
        </div>
    </div>

    <div class='section'>
    <div class='sectionHeading'>STUDENTS STATUS</div>
    <div class='assignmentStatusDiv'>
";

        $student_username_rows=$reviewer->getStudentNames();

        if($student_username_rows->num_rows > 0){
            $divCount=0;
            // while($studentData=$allStudentDataRows->fetch_assoc()){

            //     $explodedStudentTablename=explode("@",$studentData['useremail']);
            //     $studentTablename=$explodedStudentTablename[0];
            while($student_username=$student_username_rows->fetch_assoc()){

echo "
                <div class='assignmentBar'>
                    <div class='assignmentData' id='name".$divCount."'>".$student_username['username']."</div>
                    <div class='assignmentData'>
                        <div class='assignmentDataHeading'>Current Assignments</div>
                        <div class='assignmentDataValue'>
";
                    // $currentAssignmentRows=$reviewer->getCurrentAssignmentsOfStudent($studentTablename);
                    // if($currentAssignmentRows->num_rows > 0){
                    //     while($currentAssignment=$currentAssignmentRows->fetch_assoc()){
                    //         echo "<div>".$currentAssignment['assignmentName']."</div>";
                    //     }
                    // }else{
                    //     echo "<div>-</div>";
                    // }

                    $current_assignment_rows=$reviewer->getCurrentAssignments($student_username['useremail']);
                    if($current_assignment_rows->nnum_rows > 0){
                        while($current_assignment=$current_assignment_rows->fetch_assoc()){
                            echo "<div>- ".$current_assignment['assignment']."</div>";
                        }
                    }else{
                        echo "<div>-</div>";
                    }

echo "
                        </div>
                    </div>    
                    <div class='assignmentData'>
                        <button class='viewButton' id='viewButton".strval($divCount)."' onClick='showStudentStatusDesc()'>View</button>
                    </div>
                </div>
                <div class='assignmentDesc' id='assignmentDesc".strval($divCount)."'>
                    <div class='assignmentDescData'>
                        <div class='assignmentData assignmentDataHidden'>
                            <div class='assignmentDataHeading'>Pending Assignments</div>
                            <div class='assignmentDataValue'>
";
                        // $pendingAssignmentRows=$reviewer->getPendingAssignmentsOfStudent($studentTablename);
                        // if($pendingAssignmentRows->num_rows > 0){
                        //     while($pendingAssignment=$pendingAssignmentRows->fetch_assoc()){
                        //         echo "<div>".$pendingAssignment['assignmentName']."</div>";
                        //     }
                        // }else{
                        //     echo "<div>-</div>";
                        // }

                        $pending_assignment_rows=$reviewer->getStatusAssignments($student_username['useremail'],"Pending");
                        if($pending_assignment_rows->num_rows > 0){
                            while($pending_assignment=$pending_assignment_rows->fetch_assoc()){
                                echo "<div>- ".$pending_assignment['assignment']."</div>";
                                $pendingAssignmentNumber++;
                            }
                        }else{
                            echo "<div>-</div>";
                        }

                        // $studentDataRows=$reviewer->getAllStudentData($studentTablename);
                        // $pending=0;
                        // $onTimeSubmission=0;
                        // $lateSubmission=0;
                        // if($studentDataRows->num_rows > 0){
                        //     while($studentData=$studentDataRows->fetch_assoc()){
                        //         if($studentData['status']=='Done'){
                        //             $done++;
                        //             $deadlineTimeStamp=strtotime($studentData['deadline']);
                        //             if(!empty($studentData['submittedOn'])){
                        //                 $submittedOnTimeStamp=strtotime($studentData['submittedOn']);
                        //             }else{
                        //                 $submittedOnTimeStamp=$deadlineTimeStamp+1;
                        //             }
                        //             if(($deadlineTimeStamp-$submittedOnTimeStamp)>0){
                        //                 $onTimeSubmission++;
                        //             }else{
                        //                 $lateSubmission++;
                        //             }
                        //         }else{
                        //             $pending++;
                        //         }    
                        //     }
                        // } 

                        $doneAssignmentNumber=$totalAssignmentNumber-$pendingAssignmentNumber;
                        $doneAssignmentSubmissionStatus=$reviewer->getDoneSubmissionNumber($student_username['useremail']);
                        $onTimeSubmission=$doneAssignmentSubmissionStatus[0];
                        $lateSubmission=$doneAssignmentSubmissionStatus[1];
echo "
                            </div>
                        </div>
                        <div class='assignmentData assignmentDataHidden'>
                            <div class='assignmentDataHeading'>Assignment Status</div>
                            <div class='assignmentDataValue piechartStudentStatus' id='piechartAssignmentStatus".strval($divCount)."'>

                            <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>

                            <script type='text/javascript'>
                
                            google.charts.load('current', {'packages':['corechart']});
                            google.charts.setOnLoadCallback(drawChart);
                
                            function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                            ['Assignments', 'Number'],
                            ['Done', ".$doneAssignmentNumber."],
                            ['Pending', ".$pendingAssignmentNumber."],
                            ]);
                
                            var options = {
                                title:'', 
                                width:280, 
                                height:130, 
                                colors:['#8F51B5','#B167DE'], 
                                 
                                backgroundColor: {fill:'transparent' , stroke:'#30AED8'}, 
                                chartArea:{left:16,top:8,width:'90%',height:'90%'}, 
                                fontSize:13, 
                                color:'white'};
                
                            var chart = new google.visualization.PieChart(document.getElementById('piechartAssignmentStatus".strval($divCount)."'));
                            chart.draw(data, options);
                            }
                            </script>
                    
                            </div>
                        </div>    
                        <div class='assignmentData assignmentDataHidden'>
                            <div class='assignmentDataHeading'>Submission Status</div>
                            <div class='assignmentDataValue piechartSudentStatus' id='piechartSubmissionStatus".strval($divCount)."'>

                            <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>

                            <script type='text/javascript'>
                
                            google.charts.load('current', {'packages':['corechart']});
                            google.charts.setOnLoadCallback(drawChart);
                
                            function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                            ['Assignments', 'Number'],
                            ['OnTime', ".$onTimeSubmission."],
                            ['Late', ".$lateSubmission."],
                            ]);
                
                            var options = {
                                title:'', 
                                width:280, 
                                height:130, 
                                colors:['#8F51B5','#B167DE'], 
                                 
                                backgroundColor: {fill:'transparent' , stroke:'#30AED8'}, 
                                chartArea:{left:16,top:8,width:'90%',height:'90%'}, 
                                fontSize:13, 
                                color:'white'};
                
                            var chart = new google.visualization.PieChart(document.getElementById('piechartSubmissionStatus".strval($divCount)."'));
                            chart.draw(data, options);
                            }
                            </script>
                            
                            </div>
                        </div>
                    </div>
                    <div class='completeStudentStatus' id='completeStudentStatus".strval($divCount)."'></div>    
                    <div class='updateAssignmentButtonDiv'>
                        <button class='updateAssignmentButton' id='show".strval($divCount)."' onClick='showCompleteStudentStatus(`".$student_username['useremail']."`)'>View Complete Status</button>
                    </div>
                </div>
";
                $divCount=$divCount+1;
            }
        }
    
echo "
    </div>
    </div>

    <div class='section'>
        <div class='sectionHeading'>CURRENTLY REVIEWING</div>
        <div class='assignmentStatusDiv'>
";
        // $reviewerCompleteTable=$reviewer->getMyReviewerData();

        // if($reviewerCompleteTable->num_rows > 0){
        //     while($reviewerTableRow=$reviewerCompleteTable->fetch_assoc()){
        //         if($reviewerTableRow['currentlyreviewed']){
            $reviewer_s_useremail_rows=$reviewer->getReviewerStudentEmails();
            if($reviewer_s_useremail_rows->num_rows > 0){
                $reviewer->buildConnection();
                $prepare_select_student_table=$reviewer->connection->prepare("SELECT table1.username,table2.s_useremail AS studentemail,assignment AS r_assignment,deadline FROM (SELECT username,useremail FROM users WHERE useremail=?) AS table1 JOIN (SELECT s_useremail,reviewers.assignment,deadline FROM reviewers JOIN assignments ON reviewers.assignment=assignments.assignment WHERE s_useremail=?) AS table2 ON table1.useremail=table2.s_useremail");
                $prepare_select_student_table->bind_param("ss",$bind_studentemail,$bind_studentemail);
                $prepare_select_student_cloumn_values=$reviewer->connection->prepare("SELECT ? FROM students WHERE useremail=? AND assignment=? ORDER BY submittedOn");
                $prepare_select_student_cloumn_values->bind_param("sss",$bind_column,$bind_studentemail,$bind_assignment);
                while($reviewer_s_useremail=$reviewer_s_useremail_rows->fetch_assoc()){
                    $bind_studentemail=$reviewer_s_useremail['s_useremail'];
                    $studentTable=$bind_studentemail->execute();
                    $bind_assignment=$studentTable['assignment'];
                    echo "
                    <div class='assignmentBar'>
                        <div class='assignmentData' id='studentName".$divCount."'>".$studentTable['username']."</div>
                        <div class='assignmentData'>
                            <div class='assignmentDataHeading'>Reviewing Assignment</div>
                            <div class='assignmentDataValue' id='assignmentName".strval($divCount)."'>".$studentTable['assignment']."</div>
                        </div>
                        <div class='assignmentData'>
                            <button class='viewButton' id='viewButton".strval($divCount)."' onClick='showStudentStatusDesc()'>View</button>
                        </div>
                    </div>
                    <div class='assignmentDesc' id='assignmentDesc".strval($divCount)."'>
                        <div class='assignmentDescData'>
                            <div class='assignmentData assignmentDataHidden assignmentDataCurrentAssignment'>
                                <div class='assignmentDataHeading'>Deadline</div>
                                <div class='assignmentDataValue'>".$reviewer->showHyphenIfNull($studentTable['deadline'])."</div>
                            </div>
                            <div class='assignmentData assignmentDataHidden assignmentDataCurrentAssignment'>
                                <div class='assignmentDataHeading'>Last Iteration</div>
                                <div class='assignmentDataValue'>
                                ";
                                $bind_column='submittedOn';
                                $student_iterationdate=$prepare_select_student_cloumn_values->execute();
                                if($student_iterationdate->num_rows > 0){
                                    while($iterationdate=$student_iterationdate->fetch_assoc()){
                                        echo "<div>".$iterationdate['iterationdate']."</div>";
                                    }
                                }else{
                                    echo "<div>-</div>";
                                }
                                echo 
                                "</div>
                            </div>
                            <div class='assignmentData assignmentDataHidden assignmentDataCurrentAssignment'>
                                <div class='assignmentDataHeading'>Reviewers</div>
                                <div class='assignmentDataValue'>
                    ";
    
                    // $studentTablename=explode('@',$reviewerTableRow['studentemail'])[0];
                    // $studentDataRows=$reviewer->getAssignmentDataFromStudentTable($studentTablename,$reviewerTableRow['assignment']);
                    // if($studentDataRows->num_rows > 0){
                    //     $studentRow=$studentDataRows->fetch_assoc();
                    //     $reviewersArray=explode(",",$studentRow['reviewers']);
                    //     for($i=0 ; $i<count($reviewersArray) ; $i++){
                    //         $reviewersArray[$i]=trim($reviewersArray[$i]);
                    //         if($reviewersArray[$i]!=$reviewer->username){
                    //             echo "<div>- ".$reviewersArray[$i]."</div>";
                    //         }
                    //     }
                    // }

                    $bind_column='reviewer';
                    $student_reviewer=$prepare_select_student_cloumn_values->execute();
                    if($student_reviewer->num_rows > 0){
                        while($reviewer_username=$student_reviewer->fetch_assoc()){
                            echo "<div>".$reviewer_username['reviewer']."</div>";
                        }
                    }else{
                        echo "<div>-</div>";
                    }

                    echo "
                                </div>
                            </div>
                            <div class='assignmentData assignmentDataHidden assignmentDataCurrentAssignment'>
                                <div class='assignmentDataHeading'>Comment</div>
                                <div class='assignmentDataValue'>
                    ";
                    // $explodedCommentArray=explode(",",$reviewer->showHyphenIfNull($reviewerTableRow['suggestion']));
                    // for($i=0 ; $i<count($explodedCommentArray) ; $i++){
                    //     $explodedCommentArray[$i]=trim($explodedCommentArray[$i]);
                    //     echo "<div>- ".$explodedCommentArray[$i]."</div>";
                    // }
                    // $studentEmail=$reviewerTableRow['studentemail'];

                    $bind_column='comment';
                    $student_comment=$prepare_select_student_cloumn_values->execute();
                    if($student_comment->num_rows > 0){
                        while($comment=$student_comment->fetch_assoc()){
                            echo "<div>".$comment['comment']."</div>";
                        }
                    }else{
                        echo "<div>-</div>";
                    }
                    
                    echo "
                                </div>
                            </div>
                        </div>
                        <div class='iterationLinkDiv'>
                            <div class='iterationLinkHeading'>Assignment Link</div>
                            <div class='iterationLinkValue'>".$reviewer->showHyphenIfNull($reviewer->getStudentLink($reviewer_s_useremail['s_useremail'],$studentTable['assignment']))."</div>
                        </div>
                        <div class='iterationCommentDiv' id='commentDiv".strval($divCount)."'>
                            <div class='commentHeading'>Comment / Suggestion</div>
                            <div class='commentFormDiv'>
                                <form action='./dashboardUpdateButton.php' method='POST'>
                                    <div class='commentForm'>
                                        <label for='comment' class='assignmentLinkLabel'>Enter your Comment (sepearted by commas)</label>
                                        <input type='text' name='comment' id='comment' class='linkInput'>
                                        <input type='submit' name='submitLink' value='Update!'>
                                        <input type='hidden' name='userpart' value='Reviewer'>
                                        <input type='hidden' name='assignment' value='".$studentTable['assignment']."'>
                                        <input type='hidden' name='studentemail' value='".$reviewer_s_useremail['s_useremail']."'>
                                    </div>
                                </form>
                            </div> 
                        </div>    
                        <div class='updateAssignmentButtonDiv'>
                        <button class='updateAssignmentButton commentButton' id='comment".$divCount."' onClick='askComment()'>Comment</button>
                            <button class='updateAssignmentButton' id='done".$divCount."' onClick='markStatusDone(`".$reviewer_s_useremail['s_useremail']."`)'>Mark 'Done'</button>
                        </div> 
                    </div>           
                    ";
                    $divCount++;
                }
                $reviewer->closeConnection();
            }

echo "
        </div>
    </div>
";

?>
</body>
</html>