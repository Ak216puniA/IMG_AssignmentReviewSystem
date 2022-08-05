<?php

include "reviewer.php";

$reviewer= new Reviewer();
$reviewer->getUserParameters();
$reviewer->setTablename();

if($_SERVER["REQUEST_METHOD"] == "POST"){

    function ready_data($val){
        $val=trim($val);
        $val=stripslashes($val);
        $val=htmlspecialchars($val);
        return $val;
    }

    if(!empty($_POST['name'])){
        
    }
}
?>