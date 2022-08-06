<?php

class User{

    public $username;
    public $useremail;
    public $tablename;
    public $connect;

    function mysqlConnect(){
        $servername = "localhost";
        $user = "root";
        $password = "@SequentialHeart198";
        $database="IMG_ARS";

        $this->connect = new mysqli($servername, $user, $password, $database);

        if ($this->connect->connect_error) {
        die("Connection failed: " . $connect->connect_error);
        }
    }

    function getUserParameters(){
        $this->username=$_COOKIE["username"];
        $this->useremail=$_COOKIE["useremail"];
    }

    function setTablename(){
        $array=explode("@",$this->useremail,-1);
        $this->tablename=$array[0];
    }

    function getAssignmentNameArray(){
        $this->mysqlConnect();

        $select_assignment_names="SELECT name FROM assignments";

        $assignmentNameArray=$this->connect->query($select_assignment_names);

        $this->connect->close();
        $this->connect=NULL;

        return $assignmentNameArray;
    }

    function getTotalAssignmentCount(){
        $this->mysqlConnect();

        $select_count_assignments="SELECT COUNT(*) AS `count` FROM assignments";

        $countArray=$this->connect->query($select_count_assignments);
        $totalAssignmentCount=$countArray->fetch_assoc();

        $this->connect->close();
        $this->connect=NULL;

        return $totalAssignmentCount['count'];
    }

    function showHyphenIfNull($value){
        if(isset($value)){
            if($value==NULL){
                return "-";
            }else{
                return $value;
            }
        }else{
            return "-";
        }
        
    }

    function getAssignmentDeadline($assignmentName){
        if($this->connect==NULL){
            $this->mysqlConnect();
        }

        $get_deadline="SELECT deadline FROM assignments WHERE `name`='".$assignmentName."'";
        $rows=$this->connect->query($get_deadline);
        if($rows->num_rows > 0){
            $row=$rows->fetch_assoc();
            $deadline=$row['deadline'];
        }else{
            $deadline=NULL;
        }

        $this->connect->close();
        $this->connect=NULL;

        return $deadline;
    }

    function getAllAssignmentData(){
        $this->mysqlConnect();

        $select_complete_table_assignments="SELECT * FROM assignments";
        $assignmentsTable=$this->connect->query($select_complete_table_assignments);

        $this->connect->close();
        $this->connect=NULL;

        return $assignmentsTable;
    }

    function getAllReviewers(){
        $this->mysqlConnect();

        $select_reviewer_emails="SELECT useremail FROM reviewers";
        $reviewerEmailRows=$this->connect->query($select_reviewer_emails);

        $this->connect->close();
        $this->connect=NULL;

        return $reviewerEmailRows;
    }

    function getUsernameByUseremail($userEmail){
        $this->mysqlConnect();

        $get_username_from_users="SELECT username FROM users WHERE useremail='".$userEmail."'";
        $usernameRows=$this->connect->query($get_username_from_users);
        if($usernameRows->num_rows > 0){
            $username=$usernameRows->fetch_assoc();
            $username=$username['username'];
        }else{
            $username="-";
        }
        

        $this->connect->close();
        $this->connect=NULL;

        return $username;
    }
}
?>