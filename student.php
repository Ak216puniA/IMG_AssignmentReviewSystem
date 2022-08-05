<?php

include "user.php";

class Student extends User{

    // public $username;
    // public $useremail;
    // public $tablename;
    // public $connect;

    // function mysqlConnect(){
    //     $servername = "localhost";
    //     $user = "root";
    //     $password = "@SequentialHeart198";
    //     $database="IMG_ARS";

    //     $this->connect = new mysqli($servername, $user, $password, $database);

    //     if ($this->connect->connect_error) {
    //     die("Connection failed: " . $connect->connect_error);
    //     }
    // }

    // function getUserParameters(){
    //     $this->username=$_COOKIE["username"];
    //     $this->useremail=$_COOKIE["useremail"];
    // }

    // function setTablename(){
    //     $array=explode("@",$this->useremail,-1);
    //     $this->tablename=$array[0];
    // }

    function completedAssignmentCount(){
        $this->mysqlConnect();

        $count_completed_assignment="SELECT COUNT(assignmentName) AS `count` FROM ".$this->tablename." WHERE status='Done'";

        $result=$this->connect->query($count_completed_assignment);
        $count=$result->fetch_assoc();
        $count_string=strval($count['count']);

        $this->connect->close();
        $this->connect=NULL;
        
        return $count_string;
    }

    function getAssignmentArray(){
        $this->mysqlConnect();

        $select_all_assignments="SELECT * FROM ".$this->tablename;

        $allAssignments=$this->connect->query($select_all_assignments);

        $this->connect->close();
        $this->connect=NULL;
        return $allAssignments;
    }

    function getCurrentAssignmentArray(){
        $this->mysqlConnect();

        $select_pending_assignments="SELECT * FROM ".$this->tablename." WHERE current = true";

        $pendingAssignments=$this->connect->query($select_pending_assignments);

        $this->connect->close();
        $this->connect=NULL;
        return $pendingAssignments;
    }

    function updateCurrentData($assignmentName, $update){
        $this->mysqlConnect();

        $update_current_data="UPDATE ".$this->tablename." SET current=".$update." WHERE assignmentName='".$assignmentName."'";

        $this->connect->query($update_current_data);

        $this->connect->close();
        $this->connect=NULL;
    }

    function checkIfRegisteredByReviewers(){
        $this->mysqlConnect();

        $found=false;

        $check_for_useremail="SELECT useremail FROM students WHERE useremail='".$this->useremail."'";

        if($this->connect->query($check_for_useremail)->num_rows > 0){
            $found=true;
        }else{
            $found=false;
        }

        $this->connect->close();
        $this->connect=NULL;

        return $found;
    }

    function getSubmissionsData(){
        $this->mysqlConnect();

        $noOnTimeSubmissions=0;
        $noLateSubmissions=0;

        $select_submittedOn_not_null="SELECT deadline,submittedOn FROM ".$this->tablename." WHERE submittedOn IS NOT NULL";

        $assignmentRows=$this->connect->query($select_submittedOn_not_null);

        if($assignmentRows->num_rows > 0){
            while($assignment=$assignmentRows->fetch_assoc()){
                $deadlineTimeStamp=strtotime($assignment['deadline']);
                $submittedOnTimeStamp=strtotime($assignment['submittedOn']);
                if(($deadlineTimeStamp-$submittedOnTimeStamp) >= 0){
                    $noOnTimeSubmissions++;
                }else{
                    $noLateSubmissions++;
                }
            }
        }
        $this->connect->close();
        $this->connect=NULL;

        $submissionNumberArray=array($noOnTimeSubmissions,$noLateSubmissions);

        return $submissionNumberArray;
    }

    function addInIterationTable($assignmentName){
        $this->mysqlConnect();

        $check_if_already_present="SELECT * FROM iteration WHERE studentname='".$this->username."' AND assignment='".$assignmentName."'";
        $rowsFound=$this->connect->query($check_if_already_present);

        if($rowsFound->num_rows == 0){
            $select_reviewers_from_studentTable="SELECT reviewers,assignmentlink from ".$this->tablename." WHERE assignmentName='".$assignmentName."'";
            $studentAssignmentRow=$this->connect->query($select_reviewers_from_studentTable);
            $studentAssignmentRow=$studentAssignmentRow->fetch_assoc();
            $reviewers=$studentAssignmentRow['reviewers'];
            $link=$studentAssignmentRow['assignmentlink'];
    
            $presentDate=date("Y-m-d");
    
            $insert_into_iteration="INSERT INTO iteration (studentname,assignment,previousreviewers,askedon,assignmentlink) VALUES ('".$this->username."','".$assignmentName."','".$reviewers."','".$presentDate."','".$link."')";
            $this->connect->query($insert_into_iteration);
        }else{
            $select_reviewers_from_studentTable="SELECT reviewers,assignmentlink from ".$this->tablename." WHERE assignmentName='".$assignmentName."'";
            $studentAssignmentRow=$this->connect->query($select_reviewers_from_studentTable);
            $studentAssignmentRow=$studentAssignmentRow->fetch_assoc();
            $presentDate=date("Y-m-d");
            $update_iteration_data="UPDATE iteration SET askedon='".$presentDate."',assignmentlink='".$studentAssignmentRow['assignmentlink']."',previousreviewers='".$studentAssignmentRow['reviewers']."' WHERE studentname='".$this->username."' AND assignment='".$assignmentName."'";
            $this->connect->query($update_iteration_data);
        }

        $this->connect->close();
        $this->connect=NULL;
    }

    function getMyIterationData(){
        $this->mysqlConnect();

        $get_my_iteration_data="SELECT * FROM iteration WHERE studentname='".$this->username."'";
        $iterationRows=$this->connect->query($get_my_iteration_data);

        $this->connect->close();
        $this->connect=NULL;

        return $iterationRows;
    }

    function getIterationAssignmentLink($assignment){
        $this->mysqlConnect();

        $get_assignmentlink="SELECT assignmentlink FROM `".$this->tablename."` WHERE assignmentName='".$assignment."'";
        $row=$this->connect->query($get_assignmentlink);
        if($row->num_rows > 0){
            $row=$row->fetch_assoc();
            $link=$row['assignmentlink'];
        }else{
            $link="-";
        }

        $this->connect->close();
        $this->connect=NULL;

        return $link;
    }

    function updateAssignmentLink($link,$assignmentName){
        $this->mysqlConnect();

        $update_assignmentlink="UPDATE `".$this->tablename."` SET assignmentlink='".$link."' WHERE assignmentName='".$assignmentName."'";
        $this->connect->query($update_assignmentlink);

        $this->connect->close();
        $this->connect=NULL;
    }

}
?>