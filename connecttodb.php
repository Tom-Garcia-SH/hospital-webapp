<?php
/* Programmer ID: 87
       This file establishes a connection to the MySQL database.
       It uses the `mysqli_connect` function to connect to a database using the provided credentials
       If the connection fails, an error message is displayed with details about the failure using `mysqli_connect_errno` and `mysqli_connect_error`.
       This connection is stored in the `$conn` variable for later use in database queries. */

$dbhost = "localhost";
$dbuser= "root";
$dbpass = "cs3319";
$dbname = "assign2db";

$conn = mysqli_connect($dbhost, $dbuser,$dbpass,$dbname);

if (mysqli_connect_errno()) {
die("Database connection failed :" .
mysqli_connect_error() . " (" . mysqli_connect_errno() . ")" );
} //end of if statement
?>