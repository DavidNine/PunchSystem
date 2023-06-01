<?php
    /* handle request */
    $account = $_GET['account'];
    $email   = $_GET['email'];
    
    // database details
    $host = "localhost";
    $username = "root";
    $userpass = "";
    $dbname = "punchsystem";
    
    // creating a connection
    $con = mysqli_connect($host, $username, $userpass, $dbname);

    $sql = "SELECT `account`, `email` FROM `UserData` WHERE `account` = '$account';";
    $result = mysqli_query($con, $sql);
    if ($result)
    {
        if(mysqli_num_rows($result)>0)
        {
            $row = mysqli_fetch_assoc($result);
            $acc = $row['account'];
            $eml = $row['email'];
            
            /* check exist in database */
            if ($acc != $account || $email != $eml)
            {
                echo "
                <script> 
                    alert('Can not find your account/email');
                    window.location.href = 'forgetPass.php';
                 </script>
                 ";
            }
          
        }
    }
    mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ResetPass.css">
    <title>Document</title>
</head>
<body>
    <div class="container">
      <form id="ResetPass" method="POST" action="">
        <a href="index.html" class="arrow">&#8617;</a>
        <h2>Reset password</h2>
        <div class='input-container'>
            <label>New Password:</label>
            <input type="password" id="newPass"/>
        </div>
        <div class='input-container'>
            <label>Confirm Password:</label>
            <input type="password" id="comfirm-newPass"/>
        </div>
        <div class='btn-container'>
            <button onclick="resetPass()">Reset password</button>
        </div> 
        </form>
    </div>
</body>

    <script>
        function resetPass()
        {
            var newPass_elem = document.getElementById('newPass');
            var comfirm_newPass_elem = document.getElementById('comfirm-newPass');

            if (newPass_elem.value === comfirm_newPass_elem.value)
            {
                var account = "<?php echo $account; ?>";
                var act = "resetPassSuccess.php?account=" + encodeURIComponent(account) + "&newPass=" + encodeURIComponent(newPass_elem.value);
                document.getElementById('ResetPass').action = act;
                document.getElementById('ResetPass').submit();
            }
            else 
            {
                alert("your password and comfirm-password are not the same.");
                return;
            }
        }
    </script>
</html>