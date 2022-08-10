<?php

include "user.php";

class Student extends User{

    // function completedAssignmentCount(){
    //     $this->mysqlConnect();

    //     $count_completed_assignment="SELECT COUNT(assignmentName) AS `count` FROM ".$this->tablename." WHERE status='Done'";

    //     $result=$this->connect->query($count_completed_assignment);
    //     $count=$result->fetch_assoc();
    //     $count_string=strval($count['count']);

    //     $this->connect->close();
    //     $this->connect=NULL;
        
    //     return $count_string;
    // }

    // function getAssignmentArray(){
    //     $this->mysqlConnect();

    //     $select_all_assignments="SELECT * FROM ".$this->tablename;

    //     $allAssignments=$this->connect->query($select_all_assignments);

    //     $this->connect->close();
    //     $this->connect=NULL;
    //     return $allAssignments;
    // }

    function getAssignmentsStatusNumberArray(){
        $this->buildConnection();
        $done=0;
        $total=0;
        $select_student_done_number="SELECT doneCount FROM (SELECT status,COUNT(DISTINCT assignment) AS doneCount FROM (SELECT * FROM students WHERE useremail='".$this->useremail."') AS studenttable GROUP BY status) AS statusCountTable WHERE status='Done'";
        $countTable=$this->connection->query($select_student_done_number);
        if($countTable->num_rows > 0){
            $count=$countTable->fetch_assoc();
            $done=$count['doneCount'];
        }else{
            $done=0;
        }
        $select_student_assignments_number="SELECT COUNT(assignment) AS `count` FROM assignments";
        $countTable=$this->connection->query($select_student_assignments_number);
        if($countTable->num_rows > 0){
            $count=$countTable->fetch_assoc();
            $total=$count['count'];
        }else{
            $total=0;
        }
        $status_number_array=array($done,$total);
        $this->closeConnection();
        return $status_number_array;
    }

    function getDoneSubmissionNumber(){
        $this->buildConnection();
        $onTime=0;
        $late=0;
        $select_done_deadline_submittedOn="SELECT deadline,submittedOn FROM assignments JOIN (SELECT assignment,submittedOn FROM students WHERE useremail='".$this->useremail."' AND status='Done') AS newtable ON newtable.assignment=assignments.assignment";
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

    function getStudentAssignmentTable(){
        $this->buildConnection();
        // $select_student_table="SELECT assignments.assignment,deadline,status,submittedOn,reviewer FROM assignments JOIN students ON assignments.assignment=students.assignment WHERE useremail='".$this->useremail."'";
        $select_student_table="SELECT assignments.assignment,finalstatus,finalsubmittedOn,deadline FROM assignments JOIN (SELECT assignment,finalstatus,finalsubmittedOn FROM (SELECT useremail,assignment,MIN(status) AS finalstatus,MAX(submittedOn) finalsubmittedOn FROM students GROUP BY useremail,assignment) AS tablenew WHERE useremail='".$this->useremail."') AS studenttable ON assignments.assignment=studenttable.assignment";
        $student_table=$this->connection->query($select_student_table);
        $this->closeConnection();
        return $student_table;
    }

    function getStudentReviewers($assignmentName){
        $this->buildConnection();
        $select_reviewers="SELECT DISTINCT(reviewer),comment FROM students WHERE useremail='".$this->useremail."' AND assignment='".$assignmentName."'";
        $reviewer_rows=$this->connection->query($select_reviewers);
        $this->closeConnection();
        return $reviewer_rows;
    }

    // function getCurrentAssignmentArray(){
    //     $this->mysqlConnect();

    //     $select_pending_assignments="SELECT * FROM ".$this->tablename." WHERE current = true";

    //     $pendingAssignments=$this->connect->query($select_pending_assignments);

    //     $this->connect->close();
    //     $this->connect=NULL;
    //     return $pendingAssignments;
    // }

    function getCurrentAssignments(){
        $this->buildConnection();
        $select_current_assignment_table="SELECT assignments.assignment,deadline,finalstatus,finalsubmittedOn FROM assignments JOIN (SELECT assignment,finalstatus,finalsubmittedOn,finalcurrent FROM (SELECT useremail,assignment,MIN(status) AS finalstatus,MAX(submittedOn) AS finalsubmittedOn,MIN(current) AS finalcurrent FROM students GROUP BY useremail,assignment) AS tablenew WHERE useremail='".$this->useremail."' AND finalcurrent=true) AS studenttable ON assignments.assignment=studenttable.assignment";
        $current_assignments_table=$this->connection->query($select_current_assignment_table);
        $this->closeConnection();
        return $current_assignments_table;
    }

    //-----------------------------------------------------------------------------------------------------------
    // function updateCurrentData($assignmentName, $update){
    //     $this->mysqlConnect();

    //     $update_current_data="UPDATE ".$this->tablename." SET current=".$update." WHERE assignmentName='".$assignmentName."'";

    //     $this->connect->query($update_current_data);

    //     $this->connect->close();
    //     $this->connect=NULL;
    // }

    function updateStudentCurrentColumn($assignmentName,$update){
        $this->buildConnection();
        $update_current="UPDATE students SET current='".$update."' WHERE useremail='".$this->useremail."' AND assignment='".$assignmentName."'";
        $this->connection->query($update_current);
        $this->closeConnection();
    }

    // function checkIfRegisteredByReviewers(){
    //     $this->mysqlConnect();

    //     $found=false;

    //     $check_for_useremail="SELECT useremail FROM students WHERE useremail='".$this->useremail."'";

    //     if($this->connect->query($check_for_useremail)->num_rows > 0){
    //         $found=true;
    //     }else{
    //         $found=false;
    //     }

    //     $this->connect->close();
    //     $this->connect=NULL;

    //     return $found;
    // }

    // function getSubmissionsData(){
    //     $this->mysqlConnect();

    //     $noOnTimeSubmissions=0;
    //     $noLateSubmissions=0;

    //     $select_submittedOn_not_null="SELECT deadline,submittedOn FROM ".$this->tablename." WHERE submittedOn IS NOT NULL";

    //     $assignmentRows=$this->connect->query($select_submittedOn_not_null);

    //     if($assignmentRows->num_rows > 0){
    //         while($assignment=$assignmentRows->fetch_assoc()){
    //             $deadlineTimeStamp=strtotime($assignment['deadline']);
    //             $submittedOnTimeStamp=strtotime($assignment['submittedOn']);
    //             if(($deadlineTimeStamp-$submittedOnTimeStamp) >= 0){
    //                 $noOnTimeSubmissions++;
    //             }else{
    //                 $noLateSubmissions++;
    //             }
    //         }
    //     }
    //     $this->connect->close();
    //     $this->connect=NULL;

    //     $submissionNumberArray=array($noOnTimeSubmissions,$noLateSubmissions);

    //     return $submissionNumberArray;
    // }

    //-----------------------------------------------------------------------------------------------------------
    // function addInIterationTable($assignmentName){
    //     $this->mysqlConnect();

    //     $check_if_already_present="SELECT * FROM iteration WHERE studentname='".$this->username."' AND assignment='".$assignmentName."'";
    //     $rowsFound=$this->connect->query($check_if_already_present);

    //     if($rowsFound->num_rows == 0){
    //         $select_reviewers_from_studentTable="SELECT reviewers,assignmentlink from ".$this->tablename." WHERE assignmentName='".$assignmentName."'";
    //         $studentAssignmentRow=$this->connect->query($select_reviewers_from_studentTable);
    //         $studentAssignmentRow=$studentAssignmentRow->fetch_assoc();
    //         $reviewers=$studentAssignmentRow['reviewers'];
    //         $link=$studentAssignmentRow['assignmentlink'];
    
    //         $presentDate=date("Y-m-d");
    
    //         $insert_into_iteration="INSERT INTO iteration (studentname,assignment,previousreviewers,askedon,assignmentlink) VALUES ('".$this->username."','".$assignmentName."','".$reviewers."','".$presentDate."','".$link."')";
    //         $this->connect->query($insert_into_iteration);
    //     }else{
    //         $select_reviewers_from_studentTable="SELECT reviewers,assignmentlink from ".$this->tablename." WHERE assignmentName='".$assignmentName."'";
    //         $studentAssignmentRow=$this->connect->query($select_reviewers_from_studentTable);
    //         $studentAssignmentRow=$studentAssignmentRow->fetch_assoc();
    //         $presentDate=date("Y-m-d");
    //         $update_iteration_data="UPDATE iteration SET askedon='".$presentDate."',assignmentlink='".$studentAssignmentRow['assignmentlink']."',previousreviewers='".$studentAssignmentRow['reviewers']."' WHERE studentname='".$this->username."' AND assignment='".$assignmentName."'";
    //         $this->connect->query($update_iteration_data);
    //     }

    //     $this->connect->close();
    //     $this->connect=NULL;
    // }

    function insertIteration($assignmentName,$studentlink){
        $this->buildConnection();
        $presentDate=date("Y-m-d");
        $insert_iteration="INSERT INTO iteration (s_useremail,assignment,askedOn,studentlink) VALUES ('".$this->useremail."','".$assignmentName."','".$presentDate."','".$studentlink."')";
        $this->connection->query($insert_iteration);
        $insert_into_students="INSERT INTO students (useremail,assignment,`status`,submittedOn,current,studentlink) VALUES ('".$this->useremail."','".$assignmentName."','Pending','".$presentDate."',true,'".$studentlink."')";
        $this->connection->query($insert_into_students);
        $this->closeConnection();
    }

    //-------------------------------------------------------------------------------------------------------------
    // function getMyIterationData(){
    //     $this->mysqlConnect();

    //     $get_my_iteration_data="SELECT * FROM iteration WHERE studentname='".$this->username."'";
    //     $iterationRows=$this->connect->query($get_my_iteration_data);

    //     $this->connect->close();
    //     $this->connect=NULL;

    //     return $iterationRows;
    // }

    function getStudentIterationRequests(){
        $this->buildConnection();
        $select_iterations="SELECT username,assignment,askedon,studentlink FROM users JOIN iteration ON users.useremail=iteration.s_useremail WHERE users.useremail='".$this->useremail."'";
        $iteration=$this->connection->query($select_iterations);
        $this->closeConnection();
        return $iteration;
    }

    // function getIterationAssignmentLink($assignment){
    //     $this->mysqlConnect();

    //     $get_assignmentlink="SELECT assignmentlink FROM `".$this->tablename."` WHERE assignmentName='".$assignment."'";
    //     $row=$this->connect->query($get_assignmentlink);
    //     if($row->num_rows > 0){
    //         $row=$row->fetch_assoc();
    //         $link=$row['assignmentlink'];
    //     }else{
    //         $link="-";
    //     }

    //     $this->connect->close();
    //     $this->connect=NULL;

    //     return $link;
    // }

    function getStudentLink($assignmentName){
        $this->buildConnection();
        $select_studentlink="SELECT DISTINCT(studentlink) FROM students WHERE useremail='".$this->useremail."' AND assignment='".$assignmentName."'";
        $studentlink_row=$this->connection->query($select_studentlink);
        $studenlink_row=$studentlink_row->fetch_assoc();
        $studentlink=$studenlink_row['studentlink'];
        $studentlink=$this->showHyphenIfNull($studentlink);
        $this->closeConnection();
        return $studentlink;
    }

    //--------------------------------------------------------------------------------------------------------------
    // function updateAssignmentLink($link,$assignmentName){
    //     $this->mysqlConnect();

    //     $update_assignmentlink="UPDATE `".$this->tablename."` SET assignmentlink='".$link."' WHERE assignmentName='".$assignmentName."'";
    //     $this->connect->query($update_assignmentlink);

    //     $this->connect->close();
    //     $this->connect=NULL;
    // }

    function updateStudentlink($studentlink,$assignmentName){
        $this->buildConnection();
        $update_studentlink="UPDATE students SET studentlink='".$studentlink."' WHERE useremail='".$this->useremail."' AND assignment='".$assignmentName."'";
        $this->connection->query($update_studentlink);
        $this->closeConnection();
    }

}
?>