<?php

// $server="localhost";
// $user="root";
// $password="@SequentialHeart198";
// $database="IMG_ARSystem";

// $connect=new mysqli($server,$user,$password,$database);

// if ($connect->connect_error) {
//     die("Connection failed: " . $connect->connect_error);
// }

class Connection{
    public $connection;

    function buildConnection(){
        $server="localhost";
        $user="root";
        $password="password";
        $database="IMG_ARSystem";

        $this->connection=new mysqli($server,$user,$password,$database);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    function closeConnection(){
        $this->connection->close();
        $this->connection=NULL;
    }
}
?>
