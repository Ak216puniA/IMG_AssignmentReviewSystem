<?php

unset($_SESSION["loggedIn_session"]);
unset($_SESSION["username_session"]);
unset($_SESSION["useremail_session"]);
unset($_SESSION["userpass_session"]);
unset($_SESSION["userpart_session"]);

setcookie("loggedIn", "0", time()+(86400*365), "/");
setcookie("username", "", time()+(86400*365), "/");
setcookie("useremail", "", time()+(86400*365), "/");
setcookie("userpass", "", time()+(86400*365), "/");
setcookie("userpart", "", time()+(86400*365), "/");

header("Location: signin.php");

?>