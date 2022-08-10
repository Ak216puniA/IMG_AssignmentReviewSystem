<?php

include "user.php";

class Reviewer extends User{

    // function addStudentToDatabase($studentEmail, $studentTableName, $deadline, $status, $submittedOn, $reviewers, $suggestion){
    //     $this->mysqlConnect();

    //     $check_if_student_exists="SELECT useremail FROM students WHERE useremail='".$studentEmail."'";
    //     if($this->connect->query($check_if_student_exists)->num_rows == 0){

    //         $this->connect->close();
    //         $tablerows=$this->getAssignmentNameArray();
    //         $studentName=$this->getusername($studentEmail);
    //         $this->mysqlConnect();

    //         $insert_into_students="INSERT INTO students (useremail) VALUES ('".$studentEmail."')";

    //         $this->connect->query($insert_into_students);

    //         $get_reviewer_emails="SELECT useremail FROM reviewers";
    //         $reviewerEmailRows=$this->connect->query($get_reviewer_emails);
    //         if($reviewerEmailRows->num_rows > 0){
    //             while($reviewerEmail=$reviewerEmailRows->fetch_assoc()){
    //                 $tablename="review".explode("@",$reviewerEmail['useremail'])[0];
    //                 $insert_in_reviewer_table="INSERT INTO `".$tablename."` (studentname,studentemail,currentlyreviewed,assignment) VALUES ('".$studentName."','".$studentEmail."',false,'-')";
    //                 $this->connect->query($insert_in_reviewer_table);
    //             }
    //         }

    //         $create_student_table="CREATE TABLE ".$studentTableName." (assignmentName VARCHAR(255), deadline DATE NOT NULL, submittedOn DATE, status VARCHAR(8), reviewers VARCHAR(255), suggestion VARCHAR(2048), current VARCHAR(5), assignmentlink VARCHAR(1024), PRIMARY KEY(assignmentName))";

    //         if($this->connect->query($create_student_table)){

    //             $prepare_insert_studentTable=$this->connect->prepare("INSERT INTO ".$studentTableName." (assignmentName, deadline, submittedOn, status, reviewers, suggestion) VALUES (?,?,?,?,?,?)");
    //             $prepare_insert_studentTable->bind_param("ssssss",$bind_assignmentName,$bind_deadline,$bind_submittedOn,$bind_status,$bind_reviewers,$bind_suggestion);

    //             $bind_useremail=$studentEmail;
    //             $explodedEmail=explode("@",$bind_useremail);
    //             $bind_usertable=$explodedEmail[0];

    //             if($tablerows->num_rows > 0){
    //                 $i=0;
    //                 while($row=$tablerows->fetch_assoc()){
    //                     $bind_assignmentName=$row['name'];
    //                     $bind_deadline=$deadline[$i];
    //                     $bind_submittedOn=$submittedOn[$i];
    //                     $bind_status=$status[$i];
    //                     $bind_reviewers=$reviewers[$i];
    //                     $bind_suggestion=$suggestion[$i];
    //                     $prepare_insert_studentTable->execute();

    //                     $bind_assignmentName="assign".$row['name'];
    //                     $insert_assignmentTable="INSERT INTO `".$bind_assignmentName."` (useremail,usertable,status) VALUES ('".$bind_useremail."','".$bind_usertable."','".$bind_status."')";
    //                     $this->connect->query($insert_assignmentTable);
    //                     $i++;
    //                 }
    //                 echo "<script>document.getElementById('formSubmitInput').value='Added Successfully'</script>";
    //             }
    //         }else{
    //             echo "<script>alert('Unable to add student!')</script>";
    //         }
    //     }else{
    //         echo "<script>alert('Student already present!')</script>";
    //     }

    //     $this->connect->close();
    //     $this->connect=NULL;
    // }

    function addNewStudent($studentemail,$assignment,$status,$submittedOn,$reviewers,$comment){
        // $assignments=$this->getAssignmentsRequiredInfo();
        $this->buildConnection();
        $check_if_exists="SELECT useremail FROM users WHERE useremail='".$studentemail."'";
        $check=$this->connection->query($check_if_exists);
        if($check->num_rows == 0){
            $insert_student_users="INSERT INTO users (useremail,userpart) VALUES ('".$studentemail."','Student')"; 
            $this->connection->query($insert_student_users);
            $prepare_insert_student=$this->connection->prepare("INSERT INTO students (useremail,assignment,`status`,current,submittedOn,reviewer,comment) VALUES (?,?,?,false,?,?,?)");
            $prepare_insert_student->bind_param("ssssss",$bind_useremail,$bind_assignment,$bind_status,$bind_submittedOn,$bind_reviewer,$bind_comment);
            if(count($assignment) > 0){
                $bind_useremail=$studentemail;
                $count=0;
                while($count<count($assignment)){
                    $bind_assignment=$assignment[$count];
                    $bind_status=$status[$count];
                    $bind_submittedOn=$submittedOn[$count];
                    $bind_comment=$comment[$count];
                    $reviewers=$reviewers[$count];
                    $reviewer_array=explode(",",$reviewers);
                    for($i=0;$i<count($reviewer_array);$i++){
                        $bind_reviewer=$reviewer_array[$i];
                        $prepare_insert_student->execute();
                    }
                    $count++;
                }
            }
        }
        $this->closeConnection();
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

    // function removeStudentFromDatabase($studentTablename, $studentEmail){
    //     $this->mysqlConnect();

    //     $remove_from_student="DELETE FROM students WHERE useremail='".$studentEmail."'";
    //     $drop_student_table="DROP TABLE ".$studentTablename;

    //     $this->connect->query($remove_from_student);
    //     $this->connect->query($drop_student_table);

    //     $this->connect->close();
    //     $this->connect=NULL;
    // }

    function removeStudent($studentemail){
        $this->buildConnection();
        $delete_student="DELETE FROM iteration WHERE s_useremail='".$studentemail."'";
        $this->connection->query($delete_student);
        $delete_student="DELETE FROM students WHERE useremail='".$studentemail."'";
        $this->connection->query($delete_student);
        $delete_student="DELETE FROM reviewers WHERE s_useremail='".$studentemail."'";
        $this->connection->query($delete_student);
        $delete_student="DELETE FROM users WHERE useremail='".$studentemail."'";
        $this->connection->query($delete_student);
        $this->closeConnection();
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

    // function getAllStudentsAssignmentPiechart(){
    //     $currentAssignment=$this->getCurrentAssignment();
    //     $this->mysqlConnect();

    //     $done=0;
    //     $pending=0;

    //     $select_count="SELECT COUNT(useremail) AS `count` FROM `assign".$currentAssignment."` WHERE status='Done'";
    //     $done=$this->connect->query($select_count)->fetch_assoc();
    //     $done=$done['count'];

    //     $select_count="SELECT COUNT(useremail) AS `count` FROM `assign".$currentAssignment."` WHERE status='Pending'";
    //     $pending=$this->connect->query($select_count)->fetch_assoc();
    //     $pending=$pending['count'];

    //     $piechartArray=array($done,$pending);

    //     $this->connect->close();
    //     $this->connect=NULL;

    //     return $piechartArray;
    // }

    function getLastAssignmentStatusNumber(){
        $this->buildConnection();
        $done=0;
        $pending=0;
        $select_status_number="SELECT MIN(`status`) AS finalstatus FROM (SELECT useremail,`status` FROM students WHERE assignment IN (SELECT assignment FROM assignments WHERE deadline IN (SELECT MAX(deadline) FROM assignments))) AS tablenew GROUP BY useremail";
        $status_rows=$this->connection->query($select_status_number);
        if($status_rows->num_rows > 0){
            while($status=$status_rows->fetch_assoc()){
                if($status['finalstatus']=="Done"){
                    $done++;
                }else{
                    $pending++;
                }
            }
        }
        $status_number_array=array($done,$pending);
        $this->closeConnection();
        return $status_number_array;
    }

    // function getAllStudentNames(){
    //     if($this->connect==NULL){
    //         $this->mysqlConnect();
    //     }

    //     $select_username="SELECT username,useremail FROM users WHERE useremail IN (SELECT useremail FROM students)";
    //     $studentRows=$this->connect->query($select_username);

    //     $this->connect->close();
    //     $this->connect=NULL;

    //     return $studentRows;
    // }

    function getStudentNames(){
        $this->buildConnection();
        $select_student_usernames="SELECT username,useremail FROM users WHERE useremail IN (SELECT DISTINCT(useremail) FROM students)";
        $student_username_rows=$this->connection->query($select_student_usernames);
        $this->closeConnection();
        return $student_username_rows;
    }

    // function getAllStudentData($studentTablename){
    //     $this->mysqlConnect();

    //     $select_student_data="SELECT * FROM ".$studentTablename;

    //     $studentDataRows=$this->connect->query($select_student_data);

    //     $this->connect->close();
    //     $this->connect=NULL;

    //     return $studentDataRows;
    // }

    function getStudentTable($studentuseremail){
        $this->buildConnection();
        $select_student_table="SELECT table1.username,table2.s_useremail AS studentemail,assignment AS r_assignment,deadline FROM (SELECT username,useremail FROM users WHERE useremail='".$studentuseremail."') AS table1 JOIN (SELECT s_useremail,reviewers.assignment,deadline FROM reviewers JOIN assignments ON reviewers.assignment=assignments.assignment WHERE s_useremail='".$studentuseremail."') AS table2 ON table1.useremail=table2.s_useremail";
        $student_table=$this->connection->query($select_student_table);
        $this->closeConnection();
        return $student_table;
    }

    // function getCurrentAssignmentsOfStudent($studentTablename){
    //     $this->mysqlConnect();

    //     $get_where_current_true="SELECT assignmentName FROM ".$studentTablename." WHERE current=true";

    //     $currentAssignmentArray=$this->connect->query($get_where_current_true);

    //     $this->connect->close();
    //     $this->connect=NULL;

    //     return $currentAssignmentArray;
    // }

    function getCurrentAssignments($studentemail){
        $this->buildConnection();
        $select_current_true="SELECT DISTINCT(assignment) FROM students WHERE useremail='".$studentemail."' AND current=true";
        $current_assignment_rows=$this->connection->query($select_current_true);
        $this->closeConnection();
        return $current_assignment_rows;
    }

    // function getPendingAssignmentsOfStudent($studentTablename){
    //     $this->mysqlConnect();

    //     $select_pending_assignments="SELECT assignmentName FROM ".$studentTablename." WHERE status='Pending'";

    //     $pendingAssignments=$this->connect->query($select_pending_assignments);

    //     $this->connect->close();
    //     $this->connect=NULL;

    //     return $pendingAssignments;
    // }

    function getStatusAssignments($studentemail,$status){
        $this->buildConnection();
        $select_status_pending="SELECT assignment FROM (SELECT useremail,assignment,MIN(status) AS finalstatus FROM students GROUP BY useremail,assignment) AS tablenew WHERE useremail='".$studentemail."' AND finalstatus='".$status."'";
        $pending_assignment_rows=$this->connection->query($select_status_pending);
        $this->closeConnection();
        return $pending_assignment_rows;
    }

    function getDoneSubmissionNumber($studentemail){
        $this->buildConnection();
        $onTime=0;
        $late=0;
        $select_done_deadline_submittedOn="SELECT deadline,submittedOn FROM assignments JOIN (SELECT assignment,submittedOn FROM students WHERE useremail='".$studentemail."' AND status='Done') AS newtable ON newtable.assignment=assignments.assignment";
        $table_rows=$this->connection->query($select_done_deadline_submittedOn);
        if($table_rows->num_rows > 0){
            while($row=$table_rows->fetch_assoc()){
                $deadlineTimeStamp=strtotime($row['deadline']);
                $submittedOnTimeStamp=strtotime($row['submittedOn']);
                if(($deadlineTimeStamp-$submittedOnTimeStamp) >= 0){
                    $onTime++;
                }else{
                    $late++;
                }
            }
        }
        $submission_number_array=array($onTime,$late);
        $this->closeConnection();
        return $submission_number_array;
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

    // function getMyReviewerData(){
    //     $this->mysqlConnect();

    //     $tablename="review".$this->tablename;
    //     $select_my_data="SELECT * FROM `".$tablename."` WHERE studentemail IN (SELECT useremail FROM users WHERE userpart='Student')";
    //     $reviewerTable=$this->connect->query($select_my_data);

    //     $this->connect->close();
    //     $this->connect=NULL;

    //     return $reviewerTable;
    // }

    function getReviewerStudentEmails(){
        $this->buildConnection();
        $select_s_useremails="SELECT DISTINCT(s_useremail) FROM reviewers WHERE r_useremail='".$this->useremail."'";
        $student_useremail_rows=$this->connection->query($select_s_useremails);
        $this->closeConnection();
        return $student_useremail_rows;
    }

    function getAssignmentDataFromStudentTable($studentTablename,$assignmentName){
        $this->mysqlConnect();

        $select_other_reviewers="SELECT reviewers FROM `".$studentTablename."` WHERE assignmentName='".$assignmentName."'";

        $allRewviewers=$this->connect->query($select_other_reviewers);

        $this->connect->close();
        $this->connect=NULL;

        return $allRewviewers;
    }

    // function markStatusDone($studentEmail,$assignmentName){
    //     $this->mysqlConnect();

    //     $update_reviewer_table="UPDATE `".$this->tablename."` SET currentlyreviewed=false WHERE studentemail='".$studentEmail."' AND assignment='".$assignmentName."'";
    //     $this->connect->query($update_reviewer_table);

    //     $update_student_table="UPDATE `".explode('@',$studentEmail)[0]."` SET status='Done' WHERE assignmentName='".$assignmentName."'";
    //     $this->connect->query($update_student_table);

    //     $this->connect->close();
    //     $this->connect=NULL;
    // }

    function updateStudentStatus($studentemail,$assignmentName){
        $this->buildConnection();
        $select_max="SELECT MAX(submittedOn) AS maxdate FROM students WHERE useremail='".$studentemail."' AND assignment='".$assignmentName."'";
        $max=$this->connection->query($select_max);
        $max=$max->fetch_assoc();
        $maxSubmittedOn=$max['maxdate'];
        $update_student_status="UPDATE students SET `status`='Done' WHERE useremail='".$studentemail."' AND assignment='".$assignmentName."' AND submittedOn='".$maxSubmittedOn."'";
        $this->connection->query($update_student_status);
        $delete_from_reviewers="DELETE FROM reviewers WHERE r_useremail='".$this->useremail."' AND s_useremail='".$studentemail."' AND assignment='".$assignmentName."'";
        $this->connection->query($delete_from_reviewers);
        $this->closeConnection();
    }

    // function getCompleteIterationTable(){
    //     $this->mysqlConnect();

    //     $select_complete_iteration_table="SELECT * FROM iteration";

    //     $iterationTableRows=$this->connect->query($select_complete_iteration_table);

    //     $this->connect->close();
    //     $this->connect=NULL;

    //     return $iterationTableRows;
    // }

    function getIterationRequests(){
        $this->buildConnection();
        $select_iteration_table="SELECT iteration.s_useremail AS studentemail,username,assignment,askedon,studentlink FROM users JOIN iteration ON users.useremail=iteration.s_useremail";
        $iteration_table=$this->connection->query($select_iteration_table);
        $this->closeConnection();
        return $iteration_table;
    }

    // function getIterationAssignmentLink($studentName,$assignmentName){
    //     $this->mysqlConnect();

    //     $tablename="review".$this->tablename;

    //     $get_iteration_link="SELECT assignmentlink FROM `".$tablename."` WHERE studentname='".$studentName."' AND assignment='".$assignmentName."'";

    //     $linkRows=$this->connect->query($get_iteration_link);

    //     if($linkRows->num_rows > 0){
    //         $linkRow=$linkRows->fetch_assoc();

    //         if($linkRow['assignmentlink']==NULL){
    //             $link="-";
    //         }else{
    //             $link=$linkRow['assignmentlink'];
    //         }
    //     }else{
    //         $link="-";
    //     }
        
    //     $this->connect->close();
    //     $this->connect=NULL;

    //     return $link;
    // }

    function getStudentLink($studentemail,$assignmentName){
        $this->buildConnection();
        $select_studentlink="SELECT DISTINCT(studentlink) FROM students WHERE useremail='".$studentemail."' AND assignment='".$assignmentName."'";
        $studentlink_row=$this->connection->query($select_studentlink);
        $studentlink_row=$studentlink_row->fetch_assoc();
        $studentlink=$studentlink_row['studentlink'];
        $studentlink=$this->showHyphenIfNull($studentlink);
        $this->closeConnection();
        return $studentlink;
    }

    // function acceptIterationRequest($studentname,$assignment,$link){
    //     $this->mysqlConnect();

    //     $tablename="review".$this->tablename;

    //     $check_if_already_exists="SELECT * FROM `".$tablename."` WHERE studentname='".$studentname."' AND assignment='".$assignment."'";
    //     $rowsFound=$this->connect->query($check_if_already_exists);

    //     $deadline=$this->getAssignmentDeadline($assignment);
    //     $this->mysqlConnect();
    //     $date=date("Y-m-d");
    //     $get_student_email="SELECT useremail FROM users WHERE userpart='Student' AND username='".$studentname."'";
    //     $studentEmail=$this->connect->query($get_student_email);
    //     $studentEmail=$studentEmail->fetch_assoc();
    //     $studentEmail=$studentEmail['useremail'];

    //     if($rowsFound->num_rows == 0){
    //         $insert_new_row="INSERT INTO `".$tablename."` (studentname,studentemail,currentlyreviewed,assignment,deadline,iterationdate,assignmentlink) VALUES ('".$studentname."','".$studentEmail."',true,'".$assignment."','".$deadline."','".$date."','".$link."')";
    //         $this->connect->query($insert_new_row);
    //     }else{
    //         $update_reviewer_table="UPDATE `".$tablename."` SET currentlyreviewed=true,assignment='".$assignment."',deadline='".$deadline."',iterationdate='".$date."',assignmentlink='".$link."' WHERE studentname='".$studentname."' AND assignment='".$assignment."'";
    //         $this->connect->query($update_reviewer_table);
    //     }

    //     $delete_from_iteration="DELETE FROM iteration WHERE studentname='".$studentname."' AND assignment='".$assignment."'";
    //     $this->connect->query($delete_from_iteration);

    //     $studentTablename=explode("@",$studentEmail)[0];
    //     $get_reviewers="SELECT reviewers FROM `".$studentTablename."` WHERE assignmentName='".$assignment."'";
    //     $reviewers=$this->connect->query($get_reviewers);
    //     if($reviewers->num_rows > 0){
    //         $reviewers=$reviewers->fetch_assoc();
    //         $reviewers=$reviewers.", ".$this->username;
    //         $add_reviewer_in_reviewers_of_student="UPDATE `".$studentTablename."` SET reviewers='".$reviewers."' WHERE assignmentName='".$assignment."'";
    //         $this->connect->query($add_reviewer_in_reviewers_of_student);
    //     }

    //     $this->connect->close();
    //     $this->connect=NULL;
    // }

    function acceptIterationRequest($studentemail,$assignment,$studentlink){
        $this->buildConnection();
        $presentDate=date("Y-m-d");
        $check_if_exists="SELECT * FROM reviewers WHERE r_useremail='".$this->useremail."' AND s_useremail='".$studentemail."' AND assignment='".$assignment."'";
        $check=$this->connection->query($check_if_exists);
        if($check->num_rows == 0){
            $insert_into_reviewers="INSERT INTO reviewers (r_useremail,s_useremail,assignment,iterationdate,studentlink) VALUES ('".$this->useremail."','".$studentemail."','".$assignment."','".$presentDate."','".$studentlink."')";
            $this->connection->query($insert_into_reviewers);
        }else{
            $update_reviewers="UPDATE reviewers SET iterationdate='".$presentDate."',studentlink='".$studentlink."',comment='-' WHERE r_useremail='".$this->useremail."' AND s_useremail='".$studentemail."' AND assignment='".$assignment."'";
            $this->connection->query($update_reviewers);
        }
        $select_max="SELECT MAX(submittedOn) AS maxdate FROM students WHERE useremail='".$studentemail."' AND assignment='".$assignmentName."'";
        $max=$this->connection->query($select_max);
        $max=$max->fetch_assoc();
        $maxSubmittedOn=$max['maxdate'];
        $update_student_reviewers="UPDATE students SET reviewer='".$this->username."' WHERE useremail='".$studentemail."' AND assignment='".$assignment."' AND submittedOn='".$maxSubmittedOn."'";
        $delete_iteration="DELETE FROM iteration WHERE s_useremail='".$studentemail."' AND assignment='".$assignment."'";
        $this->connection->query($delete_iteration);
        $this->closeConnection();
    }

    // function updateComment($studentEmail,$assignment,$comment){
    //     $this->mysqlConnect();

    //     $studentTablename=explode("@",$studentEmail)[0];
    //     $update_comment_for_student="UPDATE `".$studentTablename."` SET suggestion='".$comment."' WHERE assignmentName='".$assignment."'";
    //     $this->connect->query($update_comment_for_student);

    //     $update_comment_for_reviewer="UPDATE `".$this->tablename."` SET suggestion='".$comment."' WHERE studentemail='".$studentEmail."' AND assignment='".$assignment."'";
    //     $this->connect->query($update_comment_for_reviewer);

    //     $this->connect->close();
    //     $this->connect=NULL;
    // }

    function updateComment($studentemail,$assignmentName,$comment){
        $this->buildConnection();
        $select_max="SELECT MAX(submittedOn) AS maxdate FROM students WHERE useremail='".$studentemail."' AND assignment='".$assignmentName."'";
        $max=$this->connection->query($select_max);
        $max=$max->fetch_assoc();
        $maxSubmittedOn=$max['maxdate'];
        $update_comment="UPDATE students SET comment='".$comment."' WHERE useremail='".$studentemail."' AND assignment='".$assignmentName."' AND submittedOn='".$maxSubmittedOn."'";
        $this->connection->query($update_comment);
        $select_max="SELECT MAX(iterationdate) AS maxdate FROM reviewers WHERE r_useremail='".$this->useremail."' AND s_useremail='".$studentemail."' AND assignment='".$assignmentName."'";
        $max=$this->connection->query($select_max);
        $max=$max->fetch_assoc();
        $maxIterationdate=$max['maxdate'];
        $update_comment="UPDATE reviewers SET comment='".$comment."' WHERE r_useremail='".$this->useremail."' AND s_useremail='".$studentemail."' AND assignment='".$assignmentName."' AND iterationdate='".$maxIterationdate."'";
        $this->connection->query($update_comment);
        $this->closeConnection();
    }

    // function addAssignmentToDatabase($name,$topics,$description,$deadline,$resources,$link){
    //     $this->mysqlConnect();

    //     $check_if_already_exists="SELECT * FROM assignments WHERE `name`='".$name."'";
    //     $rows=$this->connect->query($check_if_already_exists);
    //     if($rows->num_rows == 0){
    //         $insert_into_assignments="INSERT INTO assignments (`name`,topics,`description`,deadline,resources,links) VALUES ('".$name."','".$topics."','".$description."','".$deadline."','".$resources."','".$link."')";
    //         $this->connect->query($insert_into_assignments);

    //         $assignmentTablename="assign".$name;
    //         $create_assignment_table="CREATE TABLE `".$assignmentTablename."` (useremail VARCHAR(255),usertable VARCHAR(255),status VARCHAR(8),PRIMARY KEY (useremail))";
    //         $this->connect->query($create_assignment_table);
    
    //         $get_all_student_emails="SELECT useremail FROM students";
    //         $studentEmailRows=$this->connect->query($get_all_student_emails);

    //         if($studentEmailRows->num_rows > 0){
    //             while($semail=$studentEmailRows->fetch_assoc()){
    //                 $tablename=explode("@",$semail['useremail'])[0];
    //                 $insert_into_student_table="INSERT INTO `".$tablename."` (assignmentName,deadline,status,current,assignmentlink) VALUES ('".$name."','".$deadline."','Pending',false,'".$link."')";
    //                 $this->connect->query($insert_into_student_table);

    //                 $insert_into_assign_table="INSERT INTO `".$assignmentTablename."` (useremail,usertable,status) VALUES ('".$semail['useremail']."','".$tablename."','Pending')";
    //                 $this->connect->query($insert_into_assign_table);
    //             }
    //         }

    //     }else{
    //         echo "<script>alert('Assignment already exists!')</script>";
    //     }

    //     $this->connect->close();
    //     $this->connec=NULL;
    // }

    function insertNewAssignment($assignment,$topics,$description,$deadline,$assignmentlink,$resource){
        $student_userinfo=$this->getStudentNames();
        $this->buildConnection();
        $check_if_exists="SELECT assignment FROM assignments WHERE assignment='".$assignment."'";
        $check=$this->connection->query($check_if_exists);
        if($check->num_rows == 0){
            $insert_assignment_into_assignments="INSERT INTO assignments (assignment,topics,`description`,deadline,assignmentlink,`resource`) VALUES ('".$assignment."','".$topics."','".$description."','".$deadline."','".$assignmentlink."','".$resource."')";
            $this->connection->query($insert_assignment_into_assignments);
            $prepare_insert_into_students=$this->connection->prepare("INSERT INTO students (useremail,assignment,`status`,current) VALUES (?,?,'Pending',false)");
            $prepare_insert_into_students->bind_param("ss",$bind_useremail,$bind_assignment);
            $bind_assignment=$assignment;
            if($student_userinfo->num_rows > 0){
                while($studentinfo=$student_userinfo->fetch_assoc()){
                    $bind_useremail=$studentinfo['useremail'];
                    $prepare_insert_into_students->execute();
                }
            }
        }
        $this->closeConnection();
    }

    function getStudentReviewers($studentemail,$assignmentName){
        $this->buildConnection();
        $select_reviewers="SELECT DISTINCT(reviewer),comment FROM students WHERE useremail='".$studentemail."' AND assignment='".$assignmentName."'";
        $reviewer_rows=$this->connection->query($select_reviewers);
        $this->closeConnection();
        return $reviewer_rows;
    }

    function getStudentemail($username){
        $this->buildConnection();
        $select_useremail="SELECT useremail FROM users WHERE username='".$username."' AND userpart='Student'";
        $useremail=$this->connection->query($select_useremail);
        if($useremail->num_rows > 0){
            $useremail=$useremail->fetch_assoc();
            $useremail=$useremail['useremail'];
        }else{
            $useremail=NULL;
        }
        $this->closeConnection();
        return $useremail;
    }

    function getStudentColumn($column,$studentemail,$assignment){
        $this->buildConnection();
        $select_column="SELECT DISTINCT(".$column."),submittedOn FROM students WHERE useremail='".$studentemail."' AND assignment='".$assignment."' ORDER BY submittedOn";
        $result=$this->connection->query($select_column);
        $this->closeConnection();
        return $result;
    }

}

?>