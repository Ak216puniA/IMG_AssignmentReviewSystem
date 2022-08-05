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

        function showCompleteStudentStatus(tablename){

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
            xmlhttp.send("studentTable="+tablename);
        }

        function markStatusDone(studentEmail){

            // console.log("1");
            let clickedButtonId=document.activeElement.id;
            let clickedButton=document.getElementById(clickedButtonId);

            // console.log("2");
            if(clickedButton.innerHTML=="Mark 'Done'"){
                // console.log("3");
                let divCount=clickedButtonId.charAt(clickedButtonId.length - 1);
                let assignmentName=document.getElementById("assignmentName"+divCount).innerHTML;
                // console.log("4");

                xmlhttp=new XMLHttpRequest();
                // console.log("5");
                xmlhttp.onreadystatechange=function(){
                    // console.log("6");
                    if(this.readyState==4 && this.status==200){
                        // console.log("7");
                        clickedButton.innerHTML="Marked!";
                        clickedButton.style.backGroundColor="#2FAAD5";
                    }
                }

                // console.log("8");
                xmlhttp.open("GET","./dashboardUpdateButton.php?buttonId=done&studentEmail="+studentEmail+"&assignmentName="+assignmentName+"&userpart=Reviewer", true);
                // console.log("9");
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                // console.log("10");
                xmlhttp.send();
            }
        }

        // function askComment(studentEmail){

            // let clickedButtonId=document.activeElement.id;
            // let clickedButton=document.getElementById(clickedButtonId);

            // if(clickedButton.innerHTML == 'Comment'){
            //     let divCount=clickedButtonId.charAt(clickedButtonId.length - 1);
        //         let assignmentName=document.getElementById("assignmentName"+divCount).innerHTML;

        //         xmlhttp=new XMLHttpRequest();
        //         xmlhttp.onreadystatechange=function(){
        //             if(this.readyState==4 && this.status==200){
        //                 clickedButton.innerHTML="Comment Updated!";
        //                 clickedButton.style.backGroundColor="#2FAAD5";
        //             }
        //         }

        //         xmlhttp.open("GET","./dashboardUpdateButton.php?buttonId=done&studentEmail="+studentEmail+"&assignmentName="+assignmentName+"&userpart=Reviewer", true);
        //         xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //         xmlhttp.send();
        //     }

        // }

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
                // let hiddenCommentForm=document.getElementById('commentForm'+divCount);
                hiddenComment.style.display='block';
                // hiddenCommentForm.style.display='block';
                clickedButton.innerHTML='Enter comment!';
                clickedButton.style.backgroundColor='#2FAAD5';
            }    
        }
    </script>
    <?php
    $_SESSION["onPage_session"]="DASHBOARD";

    include "reviewer.php";
    $reviewer=new Reviewer();
    $reviewer->getUserParameters();
    $reviewer->setTablename();

    include "header.php";
    
    echo "
        <div class='pageLinksDiv'>
            <button class='pageLink' onClick='document.location.href=`./dashboardReviewer.php`'>Dashboard</button>        
            <button class='pageLink' onClick='document.location.href=`./profile.php`'>Profile</button>
            <button class='pageLink'>Reviewers</button>
            <button class='pageLink' id='studentsPageLink' onClick='document.location.href=`./allStudents.php`'>Students</button>
            <button class='pageLink' id='iterationPageLink' onClick='document.location.href=`./assignmentsReviewer.php`'>Assignments</button>
            <button class='pageLink' id='iterationPageLink' onClick='document.location.href=`./iterationReviewer.php`'>Iteration</button>
        </div>
    </div>

    <div class='allAssignments'>
        <div class='assignmentOverviewDiv' id='assignmentNamesDiv'>
            <div class='assignmentOverviewHeading'>ASSIGNMENTS</div>    
            <div class='assignmentNames'>
    ";

                $matched_rows=$reviewer->getAssignmentNameArray();
                if($matched_rows->num_rows > 0){
                    while($row=$matched_rows->fetch_assoc()){
                        echo "<div class='assignmentOverviewValue' id='oneAssignmentName'>".$row['name']."</div>";
                    }
                }

    echo "
            </div>
        </div>
        <div class='assignmentOverviewDiv' id='assignmentNamesDiv'>
            <div class='assignmentOverviewHeading'>STUDENTS</div>    
            <div class='assignmentNames'>
    ";

                $allStudentNamesRows=$reviewer->getAllStudentNames();
                if($allStudentNamesRows->num_rows > 0){
                    while($row=$allStudentNamesRows->fetch_assoc()){
                        echo "<div class='assignmentOverviewValue' id='oneAssignmentName'>".$row['username']."</div>";
                    }
                }
    
    echo "
            </div>
        </div>
        <div class='assignmentOverviewDiv' id='piechartDiv'>
            <div class='assignmentOverviewHeading'>CURRENT ASSIGNMENT STATUS</div>
    ";

    $currentAssignmentStatus=$reviewer->getAllStudentsAssignmentPiechart();
    
    echo "
            <div id='piechart'></div>
            <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>

            <script type='text/javascript'>

            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
            var data = google.visualization.arrayToDataTable([
            ['Students', 'Number'],
            ['Done', ".$currentAssignmentStatus[0]."],
            ['Pending', ".$currentAssignmentStatus[1]."],
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

        $allStudentDataRows=$reviewer->getAllStudentNames();

        if($allStudentDataRows->num_rows > 0){
            $divCount=0;
            while($studentData=$allStudentDataRows->fetch_assoc()){

                $explodedStudentTablename=explode("@",$studentData['useremail']);
                $studentTablename=$explodedStudentTablename[0];
                // echo $studentTablename;

echo "
                <div class='assignmentBar'>
                    <div class='assignmentData' id='name".$divCount."'>".$studentData['username']."</div>
                    <div class='assignmentData'>
                        <div class='assignmentDataHeading'>Current Assignments</div>
                        <div class='assignmentDataValue'>
";
                    $currentAssignmentRows=$reviewer->getCurrentAssignmentsOfStudent($studentTablename);
                    if($currentAssignmentRows->num_rows > 0){
                        while($currentAssignment=$currentAssignmentRows->fetch_assoc()){
                            echo "<div>".$currentAssignment['assignmentName']."</div>";
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
                        $pendingAssignmentRows=$reviewer->getPendingAssignmentsOfStudent($studentTablename);
                        // echo "<script>console.log('".$pendingAssignmentRows->num_rows."')</script>";
                        if($pendingAssignmentRows->num_rows > 0){
                            while($pendingAssignment=$pendingAssignmentRows->fetch_assoc()){
                                echo "<div>".$pendingAssignment['assignmentName']."</div>";
                            }
                        }else{
                            echo "<div>-</div>";
                        }

                        $studentDataRows=$reviewer->getAllStudentData($studentTablename);
                        $done=0;
                        $pending=0;
                        $onTimeSubmission=0;
                        $lateSubmission=0;
                        if($studentDataRows->num_rows > 0){
                            while($studentData=$studentDataRows->fetch_assoc()){
                                if($studentData['status']=='Done'){
                                    $done++;
                                    $deadlineTimeStamp=strtotime($studentData['deadline']);
                                    if(!empty($studentData['submittedOn'])){
                                        $submittedOnTimeStamp=strtotime($studentData['submittedOn']);
                                    }else{
                                        $submittedOnTimeStamp=$deadlineTimeStamp+1;
                                    }
                                    if(($deadlineTimeStamp-$submittedOnTimeStamp)>0){
                                        $onTimeSubmission++;
                                    }else{
                                        $lateSubmission++;
                                    }
                                }else{
                                    $pending++;
                                }    
                            }
                        } 
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
                            ['Done', ".$done."],
                            ['Pending', ".$pending."],
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
                        <button class='updateAssignmentButton' id='show".strval($divCount)."' onClick='showCompleteStudentStatus(`".$studentTablename."`)'>View Complete Status</button>
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
        $reviewerCompleteTable=$reviewer->getMyReviewerData();

        if($reviewerCompleteTable->num_rows > 0){
            while($reviewerTableRow=$reviewerCompleteTable->fetch_assoc()){
                if($reviewerTableRow['currentlyreviewed']){
                    echo "
                    <div class='assignmentBar'>
                        <div class='assignmentData' id='studentName".$divCount."'>".$reviewerTableRow['studentname']."</div>
                        <div class='assignmentData'>
                            <div class='assignmentDataHeading'>Reviewing Assignment</div>
                            <div class='assignmentDataValue' id='assignmentName".strval($divCount)."'>".$reviewerTableRow['assignment']."</div>
                        </div>
                        <div class='assignmentData'>
                            <button class='viewButton' id='viewButton".strval($divCount)."' onClick='showStudentStatusDesc()'>View</button>
                        </div>
                    </div>
                    <div class='assignmentDesc' id='assignmentDesc".strval($divCount)."'>
                        <div class='assignmentDescData'>
                            <div class='assignmentData assignmentDataHidden assignmentDataCurrentAssignment'>
                                <div class='assignmentDataHeading'>Deadline</div>
                                <div class='assignmentDataValue'>".$reviewer->showHyphenIfNull($reviewerTableRow['deadline'])."</div>
                            </div>
                            <div class='assignmentData assignmentDataHidden assignmentDataCurrentAssignment'>
                                <div class='assignmentDataHeading'>Last Iteration</div>
                                <div class='assignmentDataValue'>".$reviewer->showHyphenIfNull($reviewerTableRow['iterationdate'])."</div>
                            </div>
                            <div class='assignmentData assignmentDataHidden assignmentDataCurrentAssignment'>
                                <div class='assignmentDataHeading'>Other Reviewers</div>
                                <div class='assignmentDataValue'>
                    ";
    
                    $studentTablename=explode('@',$reviewerTableRow['studentemail'])[0];
                    $studentDataRows=$reviewer->getAssignmentDataFromStudentTable($studentTablename,$reviewerTableRow['assignment']);
                    if($studentDataRows->num_rows > 0){
                        $studentRow=$studentDataRows->fetch_assoc();
                        $reviewersArray=explode(",",$studentRow['reviewers']);
                        for($i=0 ; $i<count($reviewersArray) ; $i++){
                            $reviewersArray[$i]=trim($reviewersArray[$i]);
                            if($reviewersArray[$i]!=$reviewer->username){
                                echo "<div>- ".$reviewersArray[$i]."</div>";
                            }
                        }
                    }

                    echo "
                                </div>
                            </div>
                            <div class='assignmentData assignmentDataHidden assignmentDataCurrentAssignment'>
                                <div class='assignmentDataHeading'>Comment</div>
                                <div class='assignmentDataValue'>
                    ";
                    $explodedCommentArray=explode(",",$reviewer->showHyphenIfNull($reviewerTableRow['suggestion']));
                    for($i=0 ; $i<count($explodedCommentArray) ; $i++){
                        $explodedCommentArray[$i]=trim($explodedCommentArray[$i]);
                        echo "<div>- ".$explodedCommentArray[$i]."</div>";
                    }
                    $studentEmail=$reviewerTableRow['studentemail'];
                    echo "
                                </div>
                            </div>
                        </div>
                        <div class='iterationLinkDiv'>
                            <div class='iterationLinkHeading'>Assignment Link</div>
                            <div class='iterationLinkValue'>".$reviewer->showHyphenIfNull($reviewer->getIterationAssignmentLink($reviewerTableRow['studentname'],$reviewerTableRow['assignment']))."</div>
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
                                        <input type='hidden' name='assignment' value='".$reviewerTableRow['assignment']."'>
                                        <input type='hidden' name='studentemail' value='".$studentEmail."'>
                                    </div>
                                </form>
                            </div> 
                        </div>    
                        <div class='updateAssignmentButtonDiv'>
                        <button class='updateAssignmentButton commentButton' id='comment".$divCount."' onClick='askComment()'>Comment</button>
                            <button class='updateAssignmentButton' id='done".$divCount."' onClick='markStatusDone(`".$studentEmail."`)'>Mark 'Done'</button>
                        </div> 
                    </div>           
                    ";
                    $divCount++;
                }
            }
        }

echo "
        </div>
    </div>
";

?>
</body>
</html>