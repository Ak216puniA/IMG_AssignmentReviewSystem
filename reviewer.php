<?php

include "user.php";

class Reviewer extends User{

    function addStudentToDatabase($studentEmail, $studentTableName, $deadline, $status, $submittedOn, $reviewers, $suggestion){
        $this->mysqlConnect();

        $check_if_student_exists="SELECT useremail FROM students WHERE useremail='".$studentEmail."'";
        if($this->connect->query($check_if_student_exists)->num_rows == 0){

            $this->connect->close();
            $tablerows=$this->getAssignmentNameArray();
            $studentName=$this->getusername($studentEmail);
            $this->mysqlConnect();

            $insert_into_students="INSERT INTO students (useremail) VALUES ('".$studentEmail."')";

            $this->connect->query($insert_into_students);

            // $explodedReviewerEmail=explode("@",$this->useremail);
            // $reviewerTablename="review".$explodedReviewerEmail[0];
            // $insert_into_reviewer_table="INSERT INTO `".$reviewerTablename."` (studentname,studentemail,currentlyreviewed,assignment) VALUES ('".$studentName."','".$studentEmail."','0','-')";
            // $this->connect->query($insert_into_reviewer_table);

            $get_reviewer_emails="SELECT useremail FROM reviewers";
            $reviewerEmailRows=$this->connect->query($get_reviewer_emails);
            if($reviewerEmailRows->num_rows > 0){
                while($reviewerEmail=$reviewerEmailRows->fetch_assoc()){
                    $tablename="review".$reviewerEmail['useremail'];
                    $insert_in_reviewer_table="INSERT INTO `".$tablename."` (studentname,studentemail,currentlyreviewed,assignment) VALUES ('".$studentName."','".$studentEmail."',false,'-')";
                    $this->connect->query($insert_in_reviewer_table);
                }
            }

            $create_student_table="CREATE TABLE ".$studentTableName." (assignmentName VARCHAR(255), deadline DATE NOT NULL, submittedOn DATE, status VARCHAR(8), reviewers VARCHAR(255), suggestion VARCHAR(2048), current VARCHAR(5), assignmentlink VARCHAR(1024), PRIMARY KEY(assignmentName))";

            if($this->connect->query($create_student_table)){

                // $this->connect->close();
                // $tablerows=$this->getAssignmentNameArray();
                // $this->mysqlConnect();

                $prepare_insert_studentTable=$this->connect->prepare("INSERT INTO ".$studentTableName." (assignmentName, deadline, submittedOn, status, reviewers, suggestion) VALUES (?,?,?,?,?,?)");
                $prepare_insert_studentTable->bind_param("ssssss",$bind_assignmentName,$bind_deadline,$bind_submittedOn,$bind_status,$bind_reviewers,$bind_suggestion);

                // $prepare_insert_assignmentTable=$this->connect->prepare("INSERT INTO ".$bind_assignmentName." (useremail,usertable,status) VALUES (?,?,?)");
                // $prepare_insert_assignmentTable->bind_param("sss",$bind_useremail,$bind_usertable,$bind_status);

                $bind_useremail=$studentEmail;
                $explodedEmail=explode("@",$bind_useremail);
                $bind_usertable=$explodedEmail[0];

                if($tablerows->num_rows > 0){
                    $i=0;
                    while($row=$tablerows->fetch_assoc()){
                        $bind_assignmentName=$row['name'];
                        $bind_deadline=$deadline[$i];
                        $bind_submittedOn=$submittedOn[$i];
                        $bind_status=$status[$i];
                        $bind_reviewers=$reviewers[$i];
                        $bind_suggestion=$suggestion[$i];
                        $prepare_insert_studentTable->execute();

                        $bind_assignmentName="assign".$row['name'];
                        $insert_assignmentTable="INSERT INTO `".$bind_assignmentName."` (useremail,usertable,status) VALUES ('".$bind_useremail."','".$bind_usertable."','".$bind_status."')";
                        $this->connect->query($insert_assignmentTable);
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

    function getCurrentAssignment(){
        $this->mysqlConnect();

        $select_current_assignment="SELECT name FROM assignments WHERE deadline IN (SELECT MAX(deadline) FROM assignments)";

        $currentAssignmentArray=$this->connect->query($select_current_assignment);
        $currentAssignmentRow=$currentAssignmentArray->fetch_assoc();


        $this->connect->close();
        $this->connect=NULL;

        return $currentAssignmentRow['name'];
    }

    function getAllStudentsAssignmentPiechart(){
        $currentAssignment=$this->getCurrentAssignment();
        $this->mysqlConnect();

        $done=0;
        $pending=0;

        $select_count="SELECT COUNT(useremail) AS `count` FROM `assign".$currentAssignment."` WHERE status='Done'";
        $done=$this->connect->query($select_count)->fetch_assoc();
        $done=$done['count'];

        $select_count="SELECT COUNT(useremail) AS `count` FROM `assign".$currentAssignment."` WHERE status='Pending'";
        $pending=$this->connect->query($select_count)->fetch_assoc();
        $pending=$pending['count'];

        $piechartArray=array($done,$pending);

        $this->connect->close();
        $this->connect=NULL;

        return $piechartArray;
    }

    function getAllStudentNames(){
        if($this->connect==NULL){
            $this->mysqlConnect();
        }

        $select_username="SELECT username,useremail FROM users WHERE useremail IN (SELECT useremail FROM students)";
        $studentRows=$this->connect->query($select_username);

        $this->connect->close();
        $this->connect=NULL;

        return $studentRows;
    }

    function getAllStudentData($studentTablename){
        $this->mysqlConnect();

        $select_student_data="SELECT * FROM ".$studentTablename;

        $studentDataRows=$this->connect->query($select_student_data);

        $this->connect->close();
        $this->connect=NULL;

        return $studentDataRows;
    }

    function getCurrentAssignmentsOfStudent($studentTablename){
        $this->mysqlConnect();

        $get_where_current_true="SELECT assignmentName FROM ".$studentTablename." WHERE current=true";

        $currentAssignmentArray=$this->connect->query($get_where_current_true);

        $this->connect->close();
        $this->connect=NULL;

        return $currentAssignmentArray;
    }

    function getPendingAssignmentsOfStudent($studentTablename){
        $this->mysqlConnect();

        $select_pending_assignments="SELECT assignmentName FROM ".$studentTablename." WHERE status='Pending'";

        $pendingAssignments=$this->connect->query($select_pending_assignments);

        $this->connect->close();
        $this->connect=NULL;

        return $pendingAssignments;
    }

    function createReviewerTable($tablename){
        $this->mysqlConnect();

        $insert_in_reviewers="INSERT INTO reviewers (useremail) VALUES ('".$this->useremail."')";
        $this->connect->query($insert_in_reviewers);

        $create_table="CREATE TABLE `".$tablename."` (studentname VARCHAR(255), studentemail VARCHAR(255), currentlyreviewed VARCHAR(5), assignment VARCHAR(255), deadline DATE, iterationdate DATE, suggestion VARCHAR(2048), assignmentlink VARCHAR(1024), FOREIGN KEY (studentemail) REFERENCES students(useremail), PRIMARY KEY (studentemail,assignment))";
        $this->connect->query($create_student_table);

        $prepare_insert_data=$this->connect->prepare("INSERT INTO `".$tablename."` (studentname,studentemail,currentlyreviewed) VALUES (?,?,'0')");
        $prepare_insert_data->bind_param("ss",$bind_studentname,$bind_studentemail);

        $studentUserData=$this->getAllStudentNames();
        $this->mysqlConnect();

        if($studentUserData->num_rows > 0){
            while($student=$studentUserData->fetch_assoc()){
                $bind_studentname=$student['username'];
                $bind_studentemail=$student['useremail'];
                $prepare_insert_data->execute();
            }
        }

        $this->connect->close();
        $this->connect=NULL;
    }

    function getMyReviewerData(){
        $this->mysqlConnect();

        $tablename="review".$this->tablename;
        $select_my_data="SELECT * FROM `".$tablename."` WHERE studentemail IN (SELECT useremail FROM users WHERE userpart='Student')";
        $reviewerTable=$this->connect->query($select_my_data);

        $this->connect->close();
        $this->connect=NULL;

        return $reviewerTable;
    }

    function getAssignmentDataFromStudentTable($studentTablename,$assignmentName){
        $this->mysqlConnect();

        $select_other_reviewers="SELECT reviewers FROM `".$studentTablename."` WHERE assignmentName='".$assignmentName."'";

        $allRewviewers=$this->connect->query($select_other_reviewers);

        $this->connect->close();
        $this->connect=NULL;

        return $allRewviewers;
    }

    function markStatusDone($studentEmail,$assignmentName){
        $this->mysqlConnect();

        $update_reviewer_table="UPDATE `".$this->tablename."` SET currentlyreviewed=false WHERE studentemail='".$studentEmail."' AND assignment='".$assignmentName."'";
        $this->connect->query($update_reviewer_table);

        $update_student_table="UPDATE `".explode('@',$studentEmail)[0]."` SET status='Done' WHERE assignmentName='".$assignmentName."'";
        $this->connect->query($update_student_table);

        $this->connect->close();
        $this->connect=NULL;
    }

    function getCompleteIterationTable(){
        $this->mysqlConnect();

        $select_complete_iteration_table="SELECT * FROM iteration";

        $iterationTableRows=$this->connect->query($select_complete_iteration_table);

        $this->connect->close();
        $this->connect=NULL;

        return $iterationTableRows;
    }

    function getIterationAssignmentLink($studentName,$assignmentName){
        $this->mysqlConnect();

        $tablename="review".$this->tablename;

        $get_iteration_link="SELECT assignmentlink FROM `".$tablename."` WHERE studentname='".$studentName."' AND assignment='".$assignmentName."'";

        $linkRows=$this->connect->query($get_iteration_link);

        if($linkRows->num_rows > 0){
            $linkRow=$linkRows->fetch_assoc();

            if($linkRow['assignmentlink']==NULL){
                $link="-";
            }else{
                $link=$linkRow['assignmentlink'];
            }
        }else{
            $link="-";
        }
        
        $this->connect->close();
        $this->connect=NULL;

        return $link;
    }

    function acceptIterationRequest($studentname,$assignment,$link){
        $this->mysqlConnect();

        $tablename="review".$this->tablename;

        $check_if_already_exists="SELECT * FROM `".$tablename."` WHERE studentname='".$studentname."' AND assignment='".$assignment."'";
        $rowsFound=$this->connect->query($check_if_already_exists);

        $deadline=$this->getAssignmentDeadline($assignment);
        $this->mysqlConnect();
        $date=date("Y-m-d");
        $get_student_email="SELECT useremail FROM users WHERE userpart='Student' AND username='".$studentname."'";
        $studentEmail=$this->connect->query($get_student_email);
        $studentEmail=$studentEmail->fetch_assoc();
        $studentEmail=$studentEmail['useremail'];

        if($rowsFound->num_rows == 0){
            $insert_new_row="INSERT INTO `".$tablename."` (studentname,studentemail,currentlyreviewed,assignment,deadline,iterationdate,assignmentlink) VALUES ('".$studentname."','".$studentEmail."',true,'".$assignment."','".$deadline."','".$date."','".$link."')";
            $this->connect->query($insert_new_row);
        }else{
            $update_reviewer_table="UPDATE `".$tablename."` SET currentlyreviewed=true,assignment='".$assignment."',deadline='".$deadline."',iterationdate='".$date."',assignmentlink='".$link."' WHERE studentname='".$studentname."' AND assignment='".$assignment."'";
            $this->connect->query($update_reviewer_table);
        }

        $delete_from_iteration="DELETE FROM iteration WHERE studentname='".$studentname."' AND assignment='".$assignment."'";
        $this->connect->query($delete_from_iteration);

        $this->connect->close();
        $this->connect=NULL;
    }

    function updateComment($studentEmail,$assignment,$comment){
        $this->mysqlConnect();

        $studentTablename=explode("@",$studentEmail)[0];
        $update_comment_for_student="UPDATE `".$studentTablename."` SET suggestion='".$comment."' WHERE assignmentName='".$assignment."'";
        $this->connect->query($update_comment_for_student);

        $update_comment_for_reviewer="UPDATE `".$this->tablename."` SET suggestion='".$comment."' WHERE studentemail='".$studentEmail."' AND assignment='".$assignment."'";
        $this->connect->query($update_comment_for_reviewer);

        $this->connect->close();
        $this->connect=NULL;
    }

    function addAssignmentToDatabase($name,$topics,$description,$deadline,$resources,$link){
        $this->mysqlConnect();

        $check_if_already_exists="SELECT * FROM assignments WHERE `name`='".$name."'";
        $rows=$this->connect->query($check_if_already_exists);
        if($rows->num_rows == 0){
            $insert_into_assignments="INSERT INTO assignments (`name`,topics,`description`,deadline,resources,links) VALUES ('".$name."','".$topics."','".$description."','".$deadline."','".$resources."','".$link."')";
            $this->connect->query($insert_into_assignments);

            $assignmentTablename="assign".$name;
            $create_assignment_table="CREATE TABLE `".$assignmentTablename."` (useremail VARCHAR(255),usertable VARCHAR(255),status VARCHAR(8),PRIMARY KEY (useremail))";
            $this->connect->query($create_assignment_table);
    
            $get_all_student_emails="SELECT useremail FROM students";
            $studentEmailRows=$this->connect->query($get_all_student_emails);

            if($studentEmailRows->num_rows > 0){
                while($semail=$studentEmailRows->fetch_assoc()){
                    $tablename=explode("@",$semail['useremail'])[0];
                    $insert_into_student_table="INSERT INTO `".$tablename."` (assignmentName,deadline,status,current,assignmentlink) VALUES ('".$name."','".$deadline."','Pending',false,'".$link."')";
                    $this->connect->query($insert_into_student_table);

                    $insert_into_assign_table="INSERT INTO `".$assignmentTablename."` (useremail,usertable,status) VALUES ('".$semail['useremail']."','".$tablename."','Pending')";
                    $this->connect->query($insert_into_assign_table);
                }
            }

        }else{
            echo "<script>alert('Assignment already exists!')</script>";
        }

        $this->connect->close();
        $this->connec=NULL;
    }

}

?>