<?php
session_start();

$loggedIn_cookie=$_SESSION["loggedIn_session"];
$username_cookie=$_SESSION["username_session"];
$useremail_cookie=$_SESSION["useremail_session"];
$userpart_cookie=$_SESSION["userpart_session"];

setcookie("loggedIn", $loggedIn_cookie, time()+(86400*15), "/");
setcookie("username", $username_cookie, time()+(86400*15), "/");
setcookie("useremail", $useremail_cookie, time()+(86400*15), "/");
setcookie("userpart", $userpart_cookie, time()+(86400*15), "/");

// include "user.php";
// $user=new User();
// $user->updateSessionId($_COOKIE["PHPSESSID"]);

header("Location: index.php");

?>