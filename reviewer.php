<?php

class Reviewer{

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

    function addStudentToDatabase($studentEmail, $studentTableName, $deadline, $status, $submittedOn, $reviewers, $suggestion){
        $this->mysqlConnect();

        $check_if_table_exists="SHOW TABLES LIKE '%".$studentTableName."%'";
        if($this->connect->query($check_if_table_exists)->num_rows == 0){

            $insert_into_students="INSERT INTO students (useremail) VALUES ('".$studentEmail."')";

            $this->connect->query($insert_into_students);

            $create_student_table="CREATE TABLE ".$studentTableName." (assignmentName VARCHAR(255), deadline DATE NOT NULL, submittedOn DATE, status VARCHAR(8), reviewers VARCHAR(255), suggestion VARCHAR(2048), current VARCHAR(5), PRIMARY KEY(assignmentName))";

            if($this->connect->query($create_student_table)){

                $this->connect->close();
                $tablerows=$this->getAssignmentNameArray();
                $this->mysqlConnect();

                $prepare_insert=$this->connect->prepare("INSERT INTO ".$studentTableName." (assignmentName, deadline, submittedOn, status, reviewers, suggestion) VALUES (?,?,?,?,?,?)");
                $prepare_insert->bind_param("ssssss",$bind_assignmentName,$bind_deadline,$bind_submittedOn,$bind_status,$bind_reviewers,$bind_suggestion);

                if($tablerows->num_rows > 0){
                    $i=0;
                    while($row=$tablerows->fetch_assoc()){
                        $bind_assignmentName=$row['name'];
                        $bind_deadline=$deadline[$i];
                        $bind_submittedOn=$submittedOn[$i];
                        $bind_status=$status[$i];
                        $bind_reviewers=$reviewers[$i];
                        // echo $suggestion[$i];
                        $bind_suggestion=$suggestion[$i];
                        $prepare_insert->execute();
                        $i++;
                    }
                    echo "<script>document.getElementById('formSubmitInput').value='Added Successfully'</script>";
                }

            }else{
                echo "<script>alert('Unable to add student!')</script>";
            }
        }else{
            echo "<script>alert('Student already present!')</script>";
        }

        $this->connect->close();
        $this->connect=NULL;
    }

    function getAllStudentsUserInfo(){
        $this->mysqlConnect();

        $select_students_info="SELECT useremail FROM students";

        $allStudentsInfo=$this->connect->query($select_students_info);

        $this->connect->close();
        $this->connect=NULL;

        return $allStudentsInfo;
    }

    function getusername($useremail){
        $this->mysqlConnect();

        $username="-";

        $check_if_user_registered="SELECT username,useremail FROM users WHERE useremail='".$useremail."'";
        $rows=$this->connect->query($check_if_user_registered);
        if($rows->num_rows>0){
            $desiredRow=$rows->fetch_assoc();
            if(empty($desiredRow['username'])){
                $username="-";
            } else{
                $username=$desiredRow['username'];
            }
        }else{
            $username="-";
        }

        $this->connect->close();
        $this->connect=NULL;

        return $username;

    }

    function removeStudentFromDatabase($studentTablename, $studentEmail){
        $this->mysqlConnect();

        $remove_from_student="DELETE FROM students WHERE useremail='".$studentEmail."'";
        $drop_student_table="DROP TABLE ".$studentTablename;

        $this->connect->query($remove_from_student);
        $this->connect->query($drop_student_table);

        $this->connect->close();
        $this->connect=NULL;
    }


}

?>