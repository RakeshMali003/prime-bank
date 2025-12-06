<?php
/* database connect */

$server = "localhost";
$username = "root";
$password = "";
$dbname = "primebank";

$con = mysqli_connect($server, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>