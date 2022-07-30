<?php

class Student{

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

}
?>