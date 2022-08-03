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
    <style><?php include "dashboard_style.css" ?></style>
    <script src="https://kit.fontawesome.com/765f34396c.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php
    $_SESSION["onPage_session"]="DASHBOARD";

    include "student.php";
    $student = new student();
    $student->getUserParameters();
    $student->setTablename();

    include "header.php";

    echo "
        <div class='pageLinksDiv'>
            <button class='pageLink'>Profile</button>
            <button class='pageLink'>Reviewers</button>
            <button class='pageLink' onClick='document.location.href=`allStudents.php`'>Students</button>
        </div>
    </div>
    ";

    if($student->checkIfRegisteredByReviewers()){

        echo "
        <div class='allAssignments'>
            <div class='assignmentOverviewDiv' id='assignmentNamesDiv'>
                <div class='assignmentOverviewHeading'>ASSIGNMENTS</div>    
                <div class='assignmentNames'>
        ";
    
                    $matched_rows=$student->getAssignmentNameArray();
                    if($matched_rows->num_rows > 0){
                        while($row=$matched_rows->fetch_assoc()){
                            echo "<div class='assignmentOverviewValue' id='oneAssignmentName'>".$row['name']."</div>";
                        }
                    }
    
        echo "
                </div>
            </div>
            <div class='assignmentOverviewDiv' id='completedAssignmentsDiv'>
                <div class='assignmentOverviewHeading'>COMPLETED ASSIGNMENTS</div>
                <div class='assignmentOverviewValue' id='completedAssignmentsScore'>
        ";
                    
                    $completedAssignmentCount=$student->completedAssignmentCount();
                    $totalAssignmentsCount=$student->getTotalAssignmentCount();
    
                    echo $completedAssignmentCount." / ".$totalAssignmentsCount;
        
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

        $noSubmissonsArray=$student->getSubmissionsData();
        
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
    
                $assignmnetsArray=$student->getAssignmentArray();
    
                if($assignmnetsArray->num_rows > 0){
                    $divCount=0;
                    while($assignment=$assignmnetsArray->fetch_assoc()){
    
                        if(empty($assignment['submittedOn'])){
                            $submittedOn="-";
                        }else{
                            $submittedOn=$assignment['submittedOn'];
                        }
    
                        if(empty($assignment['reviewers'])){
                            $reviewers=array("-");
                        }else{
                            $reviewers=explode(",",$assignment['reviewers']);
                            for($i=0 ; $i<count($reviewers) ; $i++){
                                $reviewers[$i]=trim($reviewers[$i]);
                            }
                        }
        echo "
                        <div class='assignmentBar'>
                            <div class='assignmentData' id='name".$divCount."'>".$assignment['assignmentName']."</div>
                            <div class='assignmentData'>".$assignment['status']."</div>
                            <div class='assignmentData'>
                                <button class='viewButton' id='viewButton".strval($divCount)."' onClick='showAssignmentDesc()'>View</button>
                            </div>
                        </div>
                        <div class='assignmentDesc' id='assignmentDesc".strval($divCount)."'>
                            <div class='assignmentDescData'>
                                <div class='assignmentData assignmentDataHidden'>
                                    <div class='assignmentDataHeading'>Deadline</div>
                                    <div class='assignmentDataValue'>".$assignment['deadline']."</div>
                                </div>
                                <div class='assignmentData assignmentDataHidden'>
                                    <div class='assignmentDataHeading'>Submitted On</div>
                                    <div class='assignmentDataValue'>".$submittedOn."</div>
                                </div>
                                <div class='assignmentData assignmentDataHidden'>
                                    <div class='assignmentDataHeading'>Reviewers</div>
                                    <div class='assignmentDataValue'>
        ";
                        
                        for($i=0 ; $i<count($reviewers) ; $i++){
                            echo "<div>".$reviewers[$i]."</div>";
                        }
    
        echo "
                                    </div>
                                </div>
                            </div>
                            <div class='updateAssignmentButtonDiv'>
                                <button class='updateAssignmentButton' id='add".$divCount."' onClick='setCurrentInDatabase(`true`)'>Add to Current Assignments</button>
                            </div>
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
    
                $currentAssignmentArray=$student->getCurrentAssignmentArray();
    
                if($currentAssignmentArray->num_rows > 0){
                    while($assignment=$currentAssignmentArray->fetch_assoc()){
    
                        $todayDate=date("y-m-d");
                        $todayTimeStamp=strtotime($todayDate);
                        if(empty($assignment['submittedOn'])){
                            $deadlineTimeStamp=strtotime($assignment['deadline']);
                            $diff=$deadlineTimeStamp-$todayTimeStamp;
                            $daysLeft=$diff/(3600*24);
                        }else{
                            $submittedOn=$assignment['submittedOn'];
                            $submitTimeStamp=strtotime($submittedOn);
                            $diff=$todayTimeStam-$submitTimeStamp;
                            $daysLeft=$diff/(3600*24);
                            $daysLeft="Submitted ".$daysLeft." days ago";
                        }
    
                        if(empty($assignment['reviewers'])){
                            $reviewers=array("-");
                        }else{
                            $reviewers=explode(",",$assignment['reviewers']);
                            for($i=0 ; $i<count($reviewers) ; $i++){
                                $reviewers[$i]=trim($reviewers[$i]);
                            }
                        }
    
                        if(empty($assignment['suggestion'])){
                            $suggestion=array("");
                        }else{
                            $suggestion=explode(",",$assignment['suggestion']);
                            for($i=0 ; $i<count($suggestion) ; $i++){
                                $suggestion[$i]=trim($suggestion[$i]);
                            }
                        }
    
        echo "
                        <div class='assignmentBar'>
                            <div class='assignmentData' id='name".$divCount."'>".$assignment['assignmentName']."</div>
                            <div class='assignmentData assignmentDataInvisible'>".$assignment['status']."</div>
                            <div class='assignmentData'>
                                <button class='viewButton' id='viewButton".strval($divCount)."' onClick='showAssignmentDesc()'>View</button>
                            </div>
                        </div>
                        <div class='assignmentDesc' id='assignmentDesc".strval($divCount)."'>
                            <div class='assignmentDescData'>
                                <div class='assignmentData assignmentDataHidden assignmentDataCurrentAssignment'>
                                    <div class='assignmentDataHeading'>Deadline</div>
                                    <div class='assignmentDataValue'>".$assignment['deadline']."</div>
                                </div>
                                <div class='assignmentData assignmentDataHidden assignmentDataCurrentAssignment'>
                                    <div class='assignmentDataHeading'>Days Left</div>
                                    <div class='assignmentDataValue'>".$daysLeft."</div>
                                </div>
                                <div class='assignmentData assignmentDataHidden assignmentDataCurrentAssignment'>
                                    <div class='assignmentDataHeading'>Reviewed By</div>
                                    <div class='assignmentDataValue'>
        ";
                        
                        for($i=0 ; $i<count($reviewers) ; $i++){
                            echo "<div>".$reviewers[$i]."</div>";
                        }
    
        echo "
                                    </div>
                                </div>
                                <div class='assignmentData assignmentDataHidden assignmentDataCurrentAssignment'>
                                    <div class='assignmentDataHeading'>Suggestions</div>
                                    <div class='assignmentDataValue'>
        ";
    
                        for($i=0 ; $i<count($suggestion) ; $i++){
                            echo "<div>- ".$suggestion[$i]."</div>";
                        }
                        
        echo "
                                    </div>
                                </div>
                            </div>
                            <div class='updateAssignmentButtonDiv'>
                                <button class='updateAssignmentButton updateAssignmentButtonIteration'>Ask for Iteration</button>
                                <button class='updateAssignmentButton' id='remove".$divCount."' onClick='setCurrentInDatabase(`false`)'>Remove from Current Assignments</button>
                            </div>
                        </div>
        ";
                        $divCount=$divCount+1;
                    }
                }
        
        echo "
            </div>
        </div>
        ";

    }else{
        echo "
            <div class='notRegisteredDiv'>
                <div class='notRegisteredWarning' id='warningLine1'>User not registered!</div>
                <div class='notRegisteredWarning' id='warningLine2'>Ask a reviewer to add you in the student list</div>
            </div>
        ";
    }
 
    ?>
    
    <script>
        function showAssignmentDesc(){
            var viewButtonArray=document.getElementsByClassName('viewButton');
            for(var i=0 ; i<viewButtonArray.length ; i++){

                if(document.activeElement == viewButtonArray[i]){
                    var id = viewButtonArray[i].id;
                    var assignmentDesc=document.getElementById('assignmentDesc'+id.charAt(id.length-1));
                    if(viewButtonArray[i].innerHTML == "View"){
                        assignmentDesc.style.display="block";
                        viewButtonArray[i].innerHTML="Close";
                        viewButtonArray[i].style.backgroundColor="#CA4F4F";
                    }else{
                        assignmentDesc.style.display="none";
                        viewButtonArray[i].innerHTML="View";
                        viewButtonArray[i].style.backgroundColor="#2786A7";
                    }
                }
            }

        }

        function setCurrentInDatabase(update){

            var updateAssignmentButtonArray=document.getElementsByClassName('updateAssignmentButton');
            var clickedOnButtonId="";
            var assignmentName="";
            for(var i=0 ; i<updateAssignmentButtonArray.length ; i++){
                if(document.activeElement == updateAssignmentButtonArray[i]){
                    assignmentName="name"+updateAssignmentButtonArray[i].id.charAt(updateAssignmentButtonArray[i].id.length-1);
                    assignmentName=document.getElementById(assignmentName).innerHTML;
                    clickedOnButtonId=updateAssignmentButtonArray[i].id;
                }
            }

            xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange=function(){
                if(this.readyState == 4 && this.status == 200){
                    if(clickedOnButtonId.charAt(0) == 'a'){
                        document.getElementById(clickedOnButtonId).innerHTML="Added to Current Assignments";
                    }else if(clickedOnButtonId.charAt(0) == 'r'){
                        document.getElementById(clickedOnButtonId).innerHTML="Removed from Current Assignments";
                    }else{
                        document.backgroundColor="yellow";
                    }
                }
            }

            xmlhttp.open("GET", "./updateByStudent.php?name="+assignmentName+"&update="+update, true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send();
        }
    </script>
</body>
</html>