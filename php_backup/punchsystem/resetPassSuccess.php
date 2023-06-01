<?php
    /* handle request */
    $account = $_GET['account'];
    $newPass = $_GET['newPass'];
    
    // database details
    $host = "localhost";
    $username = "root";
    $userpass = "";
    $dbname = "punchsystem";
    
    // creating a connection
    $con = mysqli_connect($host, $username, $userpass, $dbname);

    $sql = "UPDATE `UserData` SET `password` = '$newPass' WHERE `UserData`.`account` = '$account';";
    $result = mysqli_query($con, $sql);
    
    if ($result)
    {
        echo 
        "<script> 
            alert('Change password successful!');
            window.location.href = 'index.html';
        </script>" 
        ;
        
    }
    else 
    {
        echo 
        "<script> 
            alert('Change password Fail!');
            window.location.href = 'ResetPass.php';
        </script>" 
        ;
    }

    mysqli_close($con);
?>