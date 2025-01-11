<?php

$server = "92.113.22.21";
$username = "u963849950_Adeniran";
$password = "AdeniranAdmin";
$dbname = "u963849950_User";

$conn = mysqli_connect($server, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
else {
    echo "Connected successfully";
}





?>