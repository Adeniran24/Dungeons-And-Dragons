<?php

$dbserver = "92.113.22.21";
$dbusername = "u963849950_Adeniran";
$dbpassword = "#vxK0F&BU;s3";
$dbname = "u963849950_User";

$conn = mysqli_connect($dbserver, $dbusername, $dbpassword, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

register_shutdown_function(function() use ($conn) {
    if ($conn) {
        $conn->close();
    }
});





?>