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

}
?>