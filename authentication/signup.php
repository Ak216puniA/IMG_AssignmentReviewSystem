<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGNUP</title>
    <link rel="stylesheet" href="../styles/authentication_style.css">
    <script src="https://kit.fontawesome.com/765f34396c.js" crossorigin="anonymous"></script>
    <style><?php include "../styles/authentication_style.css" ?></style>
</head>
<body>
<?php

$username="";
$useremail="";
$userpass="";
$userconfirmpass="";
$userpart="";
$hintTextUsername="";
$hintTextUseremail="";
$hintTextUserpass="";
$hintTextUserconfirmpass="";
$hintTextUserpart="";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $validation_complete=false;

    function ready_data($val){
        $val=trim($val);
        $val=stripslashes($val);
        $val=htmlspecialchars($val);
        return $val;
    }

    if(!empty($_POST['username'])){
        $username=ready_data($_POST['username']);
        if(preg_match("/^[A-Za-z][A-Za-z\s\d]*$/" , $username)){
            $hintTextUsername="";
        }else{
            $hintTextUsername="Invalid username format";
        }
    }else{
        $hintTextUsername="Username is Required";
    }

    if(!empty($_POST['useremail'])){
        $useremail=ready_data($_POST['useremail']);
        if(filter_var($useremail, FILTER_VALIDATE_EMAIL)){
            $hintTextUseremail="";
        }else{
            $hintTextUseremail="Invalid email format";
        }
    }else{
        $hintTextUseremail="Email is Required";
    }

    if(!empty($_POST['userpass'])){
        $userpass=ready_data($_POST['userpass']);
        if(preg_match("/[A-Z]+/",$userpass)&preg_match("/[a-z]+/",$userpass)&preg_match("/[0-9]+/",$userpass)&preg_match("/[^\w]+/",$userpass)&strlen($userpass)>7){
            $hintTextUserpass="";
        }else{
            $hintTextUserpass="Invalid password format";
        }
    }else{
        $hintTextUserpass="Password is Required";
    }

    if(!empty($_POST['userconfirmpass'])){
        $userconfirmpass=ready_data($_POST['userconfirmpass']);
        if(preg_match("/[A-Z]+/",$userpass)&preg_match("/[a-z]+/",$userpass)&preg_match("/[0-9]+/",$userpass)&preg_match("/[^\w]+/",$userpass)&strlen($userpass)>7){
            if($userpass!=$userconfirmpass){
                $hintTextUserconfirmpass="Password doesn't match";
            }else{
                $hintTextUserconfirmpass="";
            }
        }else{
            $hintTextUserconfirmpass="Invalid password format";
        }
    }else{
        $hintTextUserconfirmpass="Confirming the password is Required";
    }

    if(!empty($_POST['userpart'])){
        $userpart=$_POST['userpart'];
    }else{
        $hintTextUserpart="Selecting between Student/Reviewer is Required";
    }

    $validation_complete=empty($hintTextUsername)&empty($hintTextUseremail)&empty($hintTextUserpass)&empty($hintTextUserconfirmpass)&empty($hintTextUserpart);

    if($validation_complete){
        $hashedUserpass=hash("sha256",$userpass);

        include "../user.php";
        $user=new User();
        $check_if_already_exists="SELECT useremail,username FROM users WHERE useremail='".$useremail."'";
        $user->buildConnection();
        $user_row=$user->connection->query($check_if_already_exists);
        if($user_row->num_rows == 0){
            if($userpart=="Reviewer"){
                $insert_successfull=$user->insertInUsers($username,$useremail,$hashedUserpass,$userpart,$_COOKIE['PHPSESSID']);
                if($insert_successfull){
                    setcookie("usersessionid", $_COOKIE['PHPSESSID'], time()+(86400*15), "/");
                    $_SESSION["username_session"]=$username;
                    $_SESSION["useremail_session"]=$useremail;
                    $_SESSION["userpart_session"]=$userpart;
                    if($userpart=="Student"){
                        header("Location: ../dashboard.php");
                    }else if($userpart=="Reviewer"){
                        header("Location: ../dashboardReviewer.php");
                    }
                }else{
                    echo "<script>alert('Unable to register new user!')</script>";
                    $username=$useremail=$userpass=$userconfirmpass=$userpart="";
                }
            }else if($userpart=="Student"){
                echo "<script>alert('Student not Registered! Ask a reviewer to add you to student list.')</script>";
                $username=$useremail=$userpass=$userconfirmpass=$userpart="";
            }
        }else{
            $user_info=$user_row->fetch_assoc();
            if(empty($user_info['username'])){
                $insert_successfull=$user->insertInUsers($username,$useremail,$hashedUserpass,$userpart,$_COOKIE['PHPSESSID']);
                if($insert_successfull){
                    setcookie("usersessionid", $_COOKIE['PHPSESSID'], time()+(86400*15), "/");
                    $_SESSION["username_session"]=$username;
                    $_SESSION["useremail_session"]=$useremail;
                    $_SESSION["userpart_session"]=$userpart;
                    if($userpart=="Student"){
                        header("Location: ../dashboard.php");
                    }else if($userpart=="Reviewer"){
                        header("Location: ../dashboardReviewer.php");
                    }            
                }
            }else{
                echo "<script>alert('User already Registered! Login using your user credentials.')</script>";
                $username=$useremail=$userpass=$userconfirmpass=$userpart="";
            }
        }
        $user->closeConnection();
    }
}
?>

    <div id="screenDiv">
        <div id="signupFormDiv">
            <div id="heading">
                <span>SIGN UP</span>
                <span id="headingDiscription">
                    Register as new user
                </span>
            </div>
            <hr id="line">
            <div id="formDiv">
                <form id="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="formText">
                        <div class="labelDiv">
                            <label for="name">Username:</label>
                            <div class="tooltip">
                                <i class="fa-solid fa-circle-question"></i>
                                <span class="tooltiptext">
                                    - Username should start with a letter.
                                    <br>
                                    - Username can contain letters, numbers and spaces only. 
                                </span>
                            </div>
                        </div>                   
                        <input class="formTextInput" type="text" name="username" id="name" value=<?php echo $username?>>
                        <span class="hintText"><?php echo $hintTextUsername?></span>
                    </div>
                    <br>
                    <div class="formText">
                        <label for="email">Email:</label>
                        <input class="formTextInput" type="email" name="useremail" id="email" value=<?php echo $useremail?>>
                        <span class="hintText" id="hintTextUseremail"><?php echo $hintTextUseremail?></span>
                    </div>
                    <br>
                    <div class="formText">
                        <div class="labelDiv">
                            <label for="pass">Password:</label>
                            <div class="tooltip">
                                <i class="fa-solid fa-circle-question"></i>
                                <span class="tooltiptext">
                                    - Password should have atleast 8 characters.
                                    <br>
                                    - Password should conatin atleast one:
                                    <br>
                                        > Uppercase letter
                                    <br>
                                        > Lowercase letter
                                    <br>
                                        > Number
                                    <br>
                                        > Symbol
                                </span>
                            </div>
                        </div>
                        <input class="formTextInput" type="password" name="userpass" id="pass" value=<?php echo $userpass?>>
                        <span class="hintText" id="hintTextUserpass"><?php echo $hintTextUserpass?></span>
                    </div>
                    <br>
                    <div class="formText">
                        <label for="confirmpass">Confirm Password:</label>
                        <input class="formTextInput" type="password" name="userconfirmpass" id="confirmpass" value=<?php echo $userconfirmpass?>>
                        <span class="hintText" id="hintTextUserconfirmpass"><?php echo $hintTextUserconfirmpass?></span>
                    </div>
                    <br>
                    <div class="formRadio">
                        <div>
                            <input type="radio" name="userpart" id="partstudent" value="Student">
                            <label for="partstudent">Student</label>
                        </div>
                        <div>
                            <input type="radio" name="userpart" id="partreviewer" value="Reviewer">
                            <label for="partreviewer">Reviewer</label>
                        </div>
                    </div>
                    <span class="hintText" id="hintTextUserpart"><?php echo $hintTextUserpart?></span>
                    <br>
                    <div class="formButton">
                        <div id="redirectDescription">
                            Already have an Account?
                        </div>
                        <a href="signin.php" >
                            <input id="formButtonInput" type="button" value="SIGN IN">
                        </a>
                    </div>
                    <br>
                    <div class="formSubmit">
                        <input id="formSubmitInput" type="submit" value="Register">
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>