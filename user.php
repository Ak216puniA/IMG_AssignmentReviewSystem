<?php

include "connect.php";

class User extends Connection{

    public $username;
    public $useremail;
    public $userpart;
    // public $tablename;
    // public $connect;
    // $connection=new Connection();

    function insertInUsers($username,$useremail,$userpass,$userpart,$sessionid){
        $this->buildConnection();
        $insert_in_users="INSERT INTO users (username,useremail,userpass,userpart,sessionid) VALUES ('".$username."','".$useremail."','".$userpass."','".$userpart."','".$sessionid."')";
        $bool=$this->connection->query($insert_in_users);
        $this->closeConnection();
        return $bool;
    }

    function searchUser($sessionid){
        $this->buildConnection();
        $select_user="SELECT * FROM users WHERE sessionid='".$sessionid."'";
        $user_row=$this->connection->query($select_user);
        if($user_row->num_rows > 0){
            $user=$user_row->fetch_assoc();
        }else{
            $user=NULL;
        }
        $this->closeConnection();
        return $user;
    }

    function validateLoginCredentials($useremail,$userpass){
        $this->buildConnection();
        $search_useremail_in_user="SELECT username,userpass,userpart FROM users WHERE useremail='".$useremail."'";
        $user_row=$this->connection->query($search_useremail_in_user);
        if($user_row->num_rows > 0){
            $user=$user_row->fetch_assoc();
            if(strcmp($userpass,$user['userpass']) == 0){
                $_SESSION['username_session']=$user['username'];
                $_SESSION['useremail_session']=$useremail;
                $_SESSION['userpart_session']=$user['userpart'];
                setcookie("usersessionid",$_COOKIE["PHPSESSID"],time()+(86400*15),"/");
                $this->updateSessionid($useremail,$_COOKIE['PHPSESSID']);
                return true;
            }else{
                echo "<script>alert('Incorrect password! = ".$user['userpass']." , ".$userpass."')</script>";
                $this->closeConnection();
            }
        }else{
            echo "<script>alert('User not Registered! Create new account.')</script>";
            $this->closeConnection();
        }
        return false;
    }

    function updateSessionid($useremail,$sessionid){
        // $this->mysqlConnect();

        // $update_session_id="UPDATE users SET sessionid='".$sessionid."' WHERE useremail='".$this->useremail."'";
        // $this->connect->query($update_session_id);

        // $this->connect->close();
        // $this->connect=NULL;
        if(!empty($this->connection)){
            $this->buildConnection();
        }
        $update_user_sessionid="UPDATE users SET sessionid='".$sessionid."' WHERE useremail='".$useremail."'";
        $this->connection->query($update_user_sessionid);
        $this->closeConnection();
    }

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

    function setUserParametersFromDatabase($sessionid){
        // $this->username=$_COOKIE["username"];
        // $this->useremail=$_COOKIE["useremail"];
        $this->buildConnection();
        $select_user="SELECT * FROM users WHERE sessionid='".$sessionid."'";
        $userRow=$this->connect->query($select_user);
        if($user_row->num_rows > 0){
            $user=$user_row->fetch_assoc();
            $this->username=$user['username'];
            $this->useremail=$user['useremail'];
            $this->userpart=$user['userpart'];
        }
        $this->closeConnection();
    }

    function setUserParameters(){
        $this->username=$_SESSION['username_session'];
        $this->useremail=$_SESSION['useremail_session'];
        $this->userpart=$_SESSION['userpart_session'];
    }

    // function setTablename(){
    //     $array=explode("@",$this->useremail,-1);
    //     $this->tablename=$array[0];
    // }

    function getAssignmentsRequiredInfo(){
        // $this->mysqlConnect();

        // $select_assignment_names="SELECT `name`,deadline FROM assignments";

        // $assignmentNameArray=$this->connect->query($select_assignment_names);

        // $this->connect->close();
        // $this->connect=NULL;

        // return $assignmentNameArray;

        $this->buildConnection();
        $select_all_assignments="SELECT assignment,deadline FROM assignments";
        $assignments=$this->connection->query($select_all_assignments);
        $this->closeConnection();
        return $assignments;
    }

    // function getAssignmentsStatusNumberArray(){
    //     $this->buildConnection();
    //     $done=0;
    //     $total=0;
    //     $select_student_done_number="SELECT doneCount FROM (SELECT status,COUNT(DISTINCT assignment) AS doneCount FROM (SELECT * FROM students WHERE useremail='".$this->useremail."') AS studenttable GROUP BY status) AS statusCountTable WHERE status='Done'";
    //     $countTable=$this->connection->query($select_student_done_number);
    //     if($countTable->num_rows > 0){
    //         $count=$countTable->fetch_assoc();
    //         $done=$count['doneCount'];
    //     }else{
    //         $done=0;
    //     }
    //     $select_student_assignments_number="SELECT COUNT(assignment) AS `count` FROM assignments";
    //     $countTable=$this->connection->query($select_student_assignments_number);
    //     if($countTable->num_rows > 0){
    //         $count=$countTable->fetch_assoc();
    //         $total=$count['count'];
    //     }else{
    //         $total=0;
    //     }
    //     $status_number_array=array($done,$total);
    //     $this->closeConnection();
    //     return $status_number_array;
    // }

    // function getTotalAssignmentCount(){
    //     $this->mysqlConnect();

    //     $select_count_assignments="SELECT COUNT(*) AS `count` FROM assignments";

    //     $countArray=$this->connect->query($select_count_assignments);
    //     $totalAssignmentCount=$countArray->fetch_assoc();

    //     $this->connect->close();
    //     $this->connect=NULL;

    //     return $totalAssignmentCount['count'];
    // }

    // function getDoneSubmissionNumber(){
    //     $this->buildConnection();
    //     $onTime=0;
    //     $late=0;
    //     $select_done_deadline_submittedOn="SELECT deadline,submittedOn FROM assignments JOIN (SELECT assignment,submittedOn FROM students WHERE useremail='".$this->useremail."' AND status='Done') AS newtable ON newtable.assignment=assignments.assignment";
    //     $table_rows=$this->connection->query($select_done_deadline_submittedOn);
    //     if($table_rows->num_rows > 0){
    //         while($row=$table_rows->fetch_assoc()){
    //             $deadlineTimeStamp=strtotime($row['deadline']);
    //             $submittedOnTimeStamp=strtotime($row['submittedOn']);
    //             if(($deadlineTimeStamp-$submittedOnTimeStamp) >= 0){
    //                 $onTime++;
    //             }else{
    //                 $late++;
    //             }
    //         }
    //     }
    //     $submission_number_array=array($onTime,$late);
    //     $this->closeConnection();
    //     return $submission_number_array;
    // }

    // function getStudentAssignmentTable(){
    //     $this->buildConnection();
    //     $select_student_table="SELECT assignments.assignment,deadline,status,submittedOn,reviewer FROM assignments JOIN students ON assignments.assignment=students.assignment WHERE useremail='".$this->useremail."'";
    //     $student_table=$this->connection->query($select_student_table);
    //     $this->closeConnection();
    //     return $student_table;
    // }

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

    // function getAllAssignmentData(){
    //     $this->mysqlConnect();

    //     $select_complete_table_assignments="SELECT * FROM assignments";
    //     $assignmentsTable=$this->connect->query($select_complete_table_assignments);

    //     $this->connect->close();
    //     $this->connect=NULL;

    //     return $assignmentsTable;
    // }

    function getAssignmentsTable(){
        $this->buildConnection();
        $select_assignments="SELECT * FROM assignments";
        $assignment_table=$this->connection->query($select_assignments);
        $this->closeConnection();
        return $assignment_table;
    }

    // function getAllReviewers(){
    //     $this->mysqlConnect();

    //     $select_reviewer_emails="SELECT useremail FROM reviewers";
    //     $reviewerEmailRows=$this->connect->query($select_reviewer_emails);

    //     $this->connect->close();
    //     $this->connect=NULL;

    //     return $reviewerEmailRows;
    // }

    function getReviewersBasicInfo(){
        $this->buildConnection();
        $select_reviewers="SELECT username,useremail FROM users WHERE userpart='Reviewer'";
        $reviewers_info=$this->connection->query($select_reviewers);
        $this->closeConnection();
        return $reviewers_info;
    }

    function getStudentsBasicInfo(){
        $this->buildConnection();
        $select_students="SELECT username,useremail FROM users WHERE userpart='Student'";
        $student_info=$this->connection->query($select_students);
        $this->closeConnection();
        return $student_info;
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