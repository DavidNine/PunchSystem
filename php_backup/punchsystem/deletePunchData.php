<?php
    $del_acc = $_GET['account'];
    $del_time = $_GET['time'];



    $host = "localhost";
    $username = "root";
    $userpass = "";
    $dbname = "punchsystem";

    // creating a connection
    $con = mysqli_connect($host, $username, $userpass, $dbname);
    if (!$con)
    {
        die("Connection failed!" . mysqli_connect_error());
    }


    $sql = "DELETE FROM `punch-record` WHERE `account` = '$del_acc' AND `time` = '$del_time';";
    $result = mysqli_query($con, $sql);
    if ($result) {
        // Account is correct
        // Redirect to a success page or perform any necessary actions
        echo "<script> alert('Delete successful');window.location.href='superHome.php?account=admin';</script>";
    }
    else{
        echo "<script> alert('Delete Fail');window.history.back();</script>";
    }
    mysqli_close($con);
?>
