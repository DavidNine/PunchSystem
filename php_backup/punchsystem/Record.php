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
    $sql = "SELECT account, column2 FROM YourTable";
    $result = mysqli_query($con, $sql);

    // Fetch and store the data
    $data = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row; // Add each row to the data array
        }
    }

    mysqli_close($con);
?>

