<?php
echo "

<div class='header'>
    <div id='headerLeftDiv'>
        <div class='IMGlogoDiv'>
            <img class='IMGlogo' src='assets/imglogo.png' alt='IMG'>
        </div>
        <div class='headerText'>
            <div id='headerTextIMG'>INFORMATION MANAGEMENT GROUP</div>
            <div id='headerTextIITR'>Indian Institute of Technology, Roorkee</div>
        </div>
    </div>
    <div>
        <div class='userInfo'>
            <div class='userInfoText'>
                <div id='userInfoTextUsername'>".$_SESSION['username_session']."</div>
                <div id='userInfoTextUserpart'>".$_SESSION['userpart_session']."</div>
            </div>
            <div>
                <i class='fa-solid fa-circle-user' style='color:#0D3340; width:32; height:32px; font-size:36px'></i>
            </div>
        </div>
        <div class='headerButtons'>
            <button class='headerButtonLogout' onclick='location.href=`authentication/signout.php`'>Logout</button>    
        </div>
    </div>
</div>
<div class='underHeaderDiv'>
    <div class='pageTitleDiv'>
        <i class='fa-solid fa-angle-right' style='color:#103F4F ; font-size:18px'></i>
        <div class='pageTitle'>".$_SESSION['onPage_session']."</div>
    </div>

";
?>