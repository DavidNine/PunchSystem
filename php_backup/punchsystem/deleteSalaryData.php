<?php
    $del_account = $_GET['account'];
    $del_month = $_GET['month'];



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


    $sql = "DELETE FROM `Salary` WHERE `account` = '$del_account' AND `month` = '$del_month';";
    $result = mysqli_query($con, $sql);
    if ($result) {
        // Account is correct
        // Redirect to a success page or perform any necessary actions
        echo "<script> alert('Delete successful');window.location.href='superHome.php?month=$del_month&page=Spage';</script>";
    }
    else{
        echo "<script> alert('Delete Fail');window.history.back();</script>";
    }
    mysqli_close($con);
?>
