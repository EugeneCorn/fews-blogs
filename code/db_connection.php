<?php
// Database connection.
$db_host    = "mariadb";
$db_name    = "fews-blogs";
$db_user    = "fews-blogs";
$db_password = "fews-blogs";
$db_connection = mysqli_connect($db_host, $db_name, $db_user, $db_password);
// Check connection.
if (mysqli_connect_errno()){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// Set mysqli error reporting mode.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
