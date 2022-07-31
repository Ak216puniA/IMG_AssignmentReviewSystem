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
// header("Location: signup.php");

// if(isset($_SESSION["loggedIn_session"])){
//     header("Location: dashboard.php");
// }

if(isset($_SESSION["loggedIn_session"])){
    if($_SESSION["loggedIn_session"]){
        $_SESSION["loggedIn_session"]=false;
        if($_COOKIE['userpart'] == "Student"){
            header("Location: dashboard.php");
        }else{
            header("Location: dashboardReviewer.php");
        }
    }else{
        header("Location: authentication/signin.php");
    }
}else{
    header("Location: authentication/signin.php");
}
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


?>
</body>
</html>