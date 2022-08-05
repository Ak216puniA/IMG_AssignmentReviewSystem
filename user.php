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
        if($value==NULL){
            return "-";
        }else{
            return $value;
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
}
?>