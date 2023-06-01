<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="forgetPass.css">
    <title>FogetPassword</title>
</head>
    <div class="container">
      <form id="forgetPass" method="POST" action="">
        <a href="index.html" class="arrow">&#8617;</a>
        <h2>Reset password</h2>
        <div class='input-container'>
            <label>Account:</label>
            <input type="account" id="accountVal"/>
        </div>
        <div class='input-container'>
            <label>Email:</label>
            <input type="email" id="emailVal"/>
        </div>
        <div class='btn-container'>
            <button onclick="resetPass()">Reset password</button>
        </div> 
        </form>
    </div>
</body>

    <script>
        
        
        function resetPass(){
            
            /* check value */

            var acc_elem = document.getElementById("accountVal");
            var email_elem = document.getElementById("emailVal");

            var accountValue = acc_elem.value;
            var emailValue = email_elem.value;
            
            if (accountValue.length === 0 && emailValue.length === 0){
                alert("No any value was given.");
                return;
            }
            else if(accountValue && emailValue === "")
            {
                alert("email can not be empty.");
                return;
            }
            else if (accountValue === "" && emailValue )
            {
                alert("account can not be empty.");
                return;
            }

            /* Reset password */
            
            var form_elem = document.getElementById('forgetPass');
            var act = "ResetPass.php?account="+encodeURIComponent(accountValue)+"&email="+encodeURIComponent(emailValue);
            form_elem.action=act;
            form_elem.submit();
        }
        
    </script>
</html> 