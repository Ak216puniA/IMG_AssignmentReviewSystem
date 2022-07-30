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
    <style><?php include "./dashboard_style.css" ?></style>
    <script src="https://kit.fontawesome.com/765f34396c.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php
    $_SESSION["onPage_session"]="DASHBOARD";
    ?>
    <div class="header">
        <div id="headerLeftDiv">
            <div class="IMGlogoDiv">
                <img class="IMGlogo" src="assets/imglogo.png" alt="IMG">
            </div>
            <div class="headerText">
                <div id="headerTextIMG">INFORMATION MANAGEMENT GROUP</div>
                <div id="headerTextIITR">Indian Institute of Technology, Roorkee</div>
            </div>
        </div>
        <div>
            <div class="userInfo">
                <div class="userInfoText">
                    <div id="userInfoTextUsername"><?php echo $_COOKIE["username"]?></div>
                    <div id="userInfoTextUserpart"><?php echo $_COOKIE["userpart"]?></div>
                </div>
                <div>
                    <i class="fa-solid fa-circle-user" style="color:#0D3340; width:32; height:32px; font-size:36px"></i>
                </div>
            </div>
            <div class="headerButtons">
                <button class="headerButtonLogout" onclick="location.href='authentication/signout.php'">Logout</button>    
            </div>
        </div>
    </div>
    <div class="underHeaderDiv">
        <div class="pageTitleDiv">
            <i class="fa-solid fa-angle-right" style="color:#103F4F ; font-size:18px"></i>
            <div class="pageTitle"><?php echo $_SESSION["onPage_session"] ?></div>
        </div>
        <div class="pageLinksDiv">
            <button class="pageLink">Profile</button>
            <button class="pageLink">Reviewers</button>
            <button class="pageLink">Students</button>
        </div>
    </div>
    <div class="allAssignments">
        <div class="assignmentOverviewDiv" id="assignmentNamesDiv">
            <div class="assignmentOverviewHeading">ASSIGNMENTS</div>    
            <div class="assignmentNames">
                <?php
                include 'databaseConnect.php';

                $fetch_assignment_names="SELECT name FROM assignments";

                $matched_rows=$connect->query($fetch_assignment_names);
                if($matched_rows->num_rows > 0){
                    while($row=$matched_rows->fetch_assoc()){
                        echo "<div class='assignmentOverviewValue' id='oneAssignmentName'>".$row['name']."</div>";
                    }
                }

                $connect->close();
                ?>
            </div>
        </div>
        <div class="assignmentOverviewDiv" id="completedAssignmentsDiv">
            <div class="assignmentOverviewHeading">COMPLETED ASSIGNMENTS</div>
            <div class="assignmentOverviewValue" id="completedAssignmentsScore">
                <?php
                include "student.php";
                $student = new student();
                $student->getUserParameters();
                $student->setTablename();
                $completedAssignmentCount=$student->completedAssignmentCount();

                include "databaseConnect.php";
                $count_total_assignments="SELECT COUNT(*) AS `count` FROM assignments";
                $result=$connect->query($count_total_assignments);
                $count=$result->fetch_assoc();
                $totalAssignmentsCount=$count['count'];

                echo $completedAssignmentCount." / ".$totalAssignmentsCount;

                $connect->close();
                ?>
            </div>
            <div class="assignmentOverviewValue" id="completedAssignmentsDesc">
                <div class="innerDivCompletedAssignmentDesc">
                    <div>Completed</div>
                    <div>assignments</div>
                </div>
                <div id="slashCompletedAssignmentDesc">
                    /
                </div>
                <div class="innerDivCompletedAssignmentDesc">
                    <div>Total</div>
                    <div>assignments</div>
                </div>
            </div>
        </div>
        <div class="assignmentOverviewDiv" id="piechartDiv">
            <div class="assignmentOverviewHeading">ON TIME SUBMISSIONS</div>
            <div id="piechart"></div>
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

            <script type="text/javascript">

            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
            var data = google.visualization.arrayToDataTable([
            ['Assignments', 'Number'],
            ['OnTime Submissions', 1],
            ['Late Submissions', 2],
            ]);

            var options = {'title':'', 'width':400, 'height':200};

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
            }
            </script>
        </div>
    </div>

    <div class="section">
        <div class="sectionHeading">ASSIGNMENT STATUS</div>
        <div class="assignmentStatusDiv">
            <?php
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
            ?>
        </div>
    </div>

    <div id="testdiv"></div>

    <div class="section">
        <div class="sectionHeading">CURRENT ASSIGNMENTS</div>
        <div class="assignmentStatusDiv">
            <?php
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
                        $suggestion=explode($assignment['suggestion']);
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
            ?>
        </div>
    </div>
    
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