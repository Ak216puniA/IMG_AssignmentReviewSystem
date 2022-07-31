<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
    <link rel="stylesheet" href="styles/dashboard_style.css">
    <style><?php include "styles/dashboard_style.css" ?></style>
    <script src="https://kit.fontawesome.com/765f34396c.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php
    $_SESSION["onPage_session"]="STUDENTS";
    ?>
    <div class="header">
        <div id="headerLeftDiv">
            <div class="IMGlogoDiv">
                <img class="IMGlogo" src="assets/imglogo.png" alt="IMG">
            </div>
            <div class="headerText">
                <div id="headerTextIMG">INFORMATION MANAGEMENT GROUP</div>
                <div id="headerTextIITR">Indian Institute of Technology, Roorkee</div>
            </div>
        </div>
        <div>
            <div class="userInfo">
                <div class="userInfoText">
                    <div id="userInfoTextUsername"><?php echo $_COOKIE["username"]?></div>
                    <div id="userInfoTextUserpart"><?php echo $_COOKIE["userpart"]?></div>
                </div>
                <div>
                    <i class="fa-solid fa-circle-user" style="color:#0D3340; width:32; height:32px; font-size:36px"></i>
                </div>
            </div>
            <div class="headerButtons">
                <button class="headerButtonLogout" onclick="location.href='authentication/signout.php'">Logout</button>    
            </div>
        </div>
    </div>
    <div class="underHeaderDiv">
        <div class="pageTitleDiv">
            <i class="fa-solid fa-angle-right" style="color:#103F4F ; font-size:18px"></i>
            <div class="pageTitle"><?php echo $_SESSION["onPage_session"] ?></div>
        </div>
        <div class="pageLinksDiv">
            <button class="pageLink">Dashboard</button>
            <button class="pageLink">Profile</button>
            <button class="pageLink">Reviewers</button>
        </div>
    </div>

    <div class="addNewStudentButtonDiv">
        <button class="addNewStudentButton">ADD STUDENT</button>
    </div>
    <div class="addStudentFormDiv">
        <form class=addStudentForm action="" method="post">
            <div>
                
            </div>
        </form>
    </div>
    
</body>
</html>