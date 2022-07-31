<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGNIN</title>
    <link rel="stylesheet" href="../styles/authentication_style.css">
    <style><?php include "../styles/authentication_style.css" ?></style>
</head>
<body>
    <?php
    $useremail="";
    $userpass="";
    $hintTextUseremail="";
    $hintTextUserpass="";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $validation_complete=false;

        function ready_data($val){
            $val=trim($val);
            $val=stripslashes($val);
            $val=htmlspecialchars($val);
            return $val;
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
            if(preg_match("/[A-Z]+/",$userpass)&preg_match("/[a-z]+/",$userpass)&preg_match("/[0-9]+/",$userpass)&preg_match("/[^\w+]+/",$userpass)&strlen($userpass)>7){
                $hintTextUserpass="";
            }else{
                $hintTextUserpass="Invalid password format";
            }
        }else{
            $hintTextUserpass="Password is Required";
        }

        $validation_complete=empty($hintTextUseremail)&empty($hintTextUserpass);

        if($validation_complete){
            
            $hashedUserpass=hash("sha256",$userpass);

            //include "../databaseConnect.php";
            $servername = "localhost";
            $user = "root";
            $password = "@SequentialHeart198";
            $database="test";

            $connect = new mysqli($servername, $user, $password, $database);

            if ($connect->connect_error) {
            die("Connection failed: " . $connect->connect_error);
            }

            $search_user="SELECT * FROM users WHERE useremail='".$useremail."'";

            $matched_rows=$connect->query($search_user);

            if($matched_rows->num_rows!=0){
                $row=$matched_rows->fetch_assoc();
                if(strcmp($row["userpass"],$hashedUserpass)==0){
                    //TO-DO: SET COOKIE AND DIRECT TO PROFILE PAGE OF CORRESPONDING USER
                    // echo "<script>alert('Logged in successfully!')</script>";

                    $_SESSION["username_session"]=$row["username"];
                    $_SESSION["useremail_session"]=$useremail;
                    $_SESSION["userpass_session"]=$hashedUserpass;
                    $_SESSION["userpart_session"]=$row["userpart"];
                    $_SESSION["loggedIn_session"]=true;

                    header("Location: ../cookie.php");
                }else{
                    echo "<script>alert('Incorrect password!')</script>";
                }
            }else{
                echo "<script>alert('User not registered! Create a new account.')</script>";
            }

            $connect->close();
        }
    }

    ?>

    <div id="screenDiv">
        <div id="signupFormDiv">
            <div id="heading">
                <span>SIGN IN</span>
                <span id="headingDiscription">
                    Login to your account
                </span>
            </div>
            <hr id="line">
            <div id="formDiv">
                <form id="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="formText">
                        <label for="email">Email:</label>
                        <input class="formTextInput" type="email" name="useremail" id="email" value=<?php echo $useremail?>>
                        <span class="hintText" id="hintTextUseremail" style="color:#C41C1C; font-size:12px"><?php echo $hintTextUseremail?></span>
                    </div>
                    <br>
                    <div class="formText">
                        <label for="pass">Password:</label>
                        <input class="formTextInput" type="password" name="userpass" id="pass" value=<?php echo $userpass?>>
                        <span class="hintText" id="hintTextUserpass" style="color:#C41C1C; font-size:12px"><?php echo $hintTextUserpass?></span>
                    </div>
                    <br>
                    <div class="formButton">
                        <div id="redirectDescription">
                            Want to create new Account?
                        </div>
                        <a href="signup.php" >
                            <input id="formButtonInput" type="button" value="SIGN UP">
                        </a>
                    </div>
                    <br>
                    <div class="formSubmit">
                        <input id="formSubmitInput" type="submit" value="Login">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>