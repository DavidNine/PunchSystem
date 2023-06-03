<?php

    $account = $_GET['acc'];
    $month = $_GET['month'];
    $salary = $_GET['salary'];

    

    $host = "localhost";
    $username = "root";
    $userpass = "";
    $dbname = "punchsystem";

     $con = mysqli_connect($host, $username, $userpass, $dbname);

    if (!$con) {
        die("Connection failed!" . mysqli_connect_error());
    }

    $chk_acc = "SELECT * FROM `UserData` WHERE `account` = '$account';";
    $chk_acc_ret = mysqli_query($con,$chk_acc);
    if ($chk_acc_ret){
        if (!mysqli_num_rows($chk_acc_ret)) {
            
            echo "<script>";
            echo "alert('Fail to assign salary, Account: $account does not exist.');";
            echo "window.history.back();";
            echo "</script>";
        }
    }

    $chk = "SELECT * FROM `Salary` WHERE `account` = '$account' AND `month` = '$month';";
    $chk_ret = mysqli_query($con,$chk);
    
    if ($chk_ret){
        if (mysqli_num_rows($chk_ret) > 0) {
            
            echo "<script>";
            echo "alert('Assign salary fail,The account $account has assigned salary at $month.');";
            echo "window.history.back();";
            echo "</script>";
        }
    }


    
    $sql = "INSERT INTO `Salary`(`account`, `month`, `salary_amount`) VALUES ('$account','$month','$salary');";
    $ret = mysqli_query($con,$sql);

    // // $sql = "INSERT INTO punch-record (account,date,time,status) VALUES ('$account','$Date','$month','$month');";

    if ($ret){
        echo "<script> alert('Assign salary successful');window.location.href='superHome.php?account=admin';</script>";
    }
    else {
        echo "<script> alert('Assign salary fail');window.location.href='superHome.php?account=admin';</script>";
    }
    
    mysqli_close($con);

?>