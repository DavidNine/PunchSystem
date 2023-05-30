<?php

    session_start();

    // Establish a database connection
    $host = "localhost";
    $username = "root";
    $userpass = "";
    $dbname = "punchsystem";
    $con = mysqli_connect($host, $username, $userpass, $dbname);

    if (!$con) {
        die("Connection failed!" . mysqli_connect_error());
    }

    // Execute a query to fetch data
    $sql = "SELECT * FROM `punch-record` WHERE 1;";
    $result = mysqli_query($con, $sql);

    echo $result;
   
    mysqli_close($con);
?>

