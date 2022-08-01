<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles/dashboard_style.css">
    <style><?php include "./dashboard_style.css" ?></style>
    <script src="https://kit.fontawesome.com/765f34396c.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php
    $_SESSION["onPage_session"]="DASHBOARD";
    include "header.php";
    ?>
        <div class="pageLinksDiv">
            <button class="pageLink">Profile</button>
            <button class="pageLink">Reviewers</button>
            <button class="pageLink" id="studentsPageLink" onClick="document.location.href='./allStudents.php'">Students</button>
        </div>
    </div>
</body>
</html>