<?php
    // getting all values from the HTML form
    session_start(); // Start the session

    $account = $_POST['account'];
    $password = $_POST['password'];

    $_SESSION['account'] = $account;

    // database details
    $host = "localhost";
    $username = "root";
    $userpass = "";
    $dbname = "punchsystem";

    // creating a connection
    $con = mysqli_connect($host, $username, $userpass, $dbname);

    // to ensure that the connection is made
    if (!$con)
    {
        die("Connection failed!" . mysqli_connect_error());
    }

    // $sql = "SELECT * FROM `UserData` WHERE 1;";

    $query = "SELECT * FROM `UserData` WHERE account = '$account' AND password = '$password'";
    $result = mysqli_query($con, $query);
    
    // Check if a matching record was found
    if (mysqli_num_rows($result) > 0) {
        // Account is correct
        // Redirect to a success page or perform any necessary actions
        echo "<script> alert('Login successful');window.location.href = 'Home.php'; </script>";
    } else {
        // Account is incorrect
        // Redirect back to the login page with an alert
        echo "<script> window.location.href = 'LoginError.html';</script>";
    }
    mysqli_close($con);
?>