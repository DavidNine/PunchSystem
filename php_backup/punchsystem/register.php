<?php
    // getting all values from the HTML form
   
    
    $account    = $_POST['account'];
    $password   = $_POST['password'];
    $email      = $_POST['email'];
    
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

    // $sql = "INSERT INTO `UserData`(`account`, `password`) VALUES (\'acut74569\',\'456852aa\');";

    // $sql = "SELECT * FROM `UserData` WHERE 1;";
    $sql = "INSERT INTO UserData (account, password, email) VALUES ('$account','$password','$email');";
    // $query = "INSERT INTO UserData (`account`, `password`) VALUES ('$account','$password');";
    $result = mysqli_query($con, $sql);
    
    // Check if a matching record was found
    if ($result) {
        // Account is correct
        // Redirect to a success page or perform any necessary actions
        echo "<script> alert('Register successful');window.location.href = 'index.html';</script>";
    }
    else{
        echo "<script> alert('Register Fail');window.history.back();</script>";
    }
    mysqli_close($con);
?>
 