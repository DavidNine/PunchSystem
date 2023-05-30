<?php

    $account = $_GET['account'];
    $Time = $_GET['Time'];
    $Date = $_GET['Date'];
    $status = $_GET['status'];
    

    $host = "localhost";
    $username = "root";
    $userpass = "";
    $dbname = "punchsystem";

     $con = mysqli_connect($host, $username, $userpass, $dbname);

    if (!$con) {
        die("Connection failed!" . mysqli_connect_error());
    }
    $sql = "INSERT INTO `punch-record`(`account`, `date`, `time`, `status`) VALUES ('$account','$Date','$Time','$status');";
    $ret = mysqli_query($con,$sql);

    // // $sql = "INSERT INTO punch-record (account,date,time,status) VALUES ('$account','$Date','$Time','$status');";

    if ($ret){
        echo "<script> alert('punch '+'$status'+' successful');window.location.href='Home.php';</script>";
    }
    else {
        echo "<script> alert('punch '+'$status'+' fail');window.location.href='Home.php';</script>";
    }
    
    mysqli_close($con);

?>