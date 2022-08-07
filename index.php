<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MainPage</title>
</head>
<body>
<?php

if(isset($_COOKIE["loggedIn"])){
    if($_COOKIE["loggedIn"]){
        if($_COOKIE['userpart'] == "Student"){
            header("Location: dashboard.php");
        }else{
            header("Location: dashboardReviewer.php");
        }
    }else{
        header("Location: authentication/signin.php");
    }
}else{
    header("Locationn: authentication/signin.php");
}

// include "databaseConnect.php";

// $search_for_session_id="SELECT * FROM users WHERE sessionid='".$_COOKIE["PHPSESSID"]."'";
// $userData=$this->connect->query($search_for_session_id);
// if($userData->num_rows > 0){

// }

?>
</body>
</html>