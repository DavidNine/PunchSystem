<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="Login.css">
</head>
<body>
    <div class='Container'>
      <form method="POST" action="superForm.php" id="loginForm">
        <h2>Login</h2>
        <div class='form-input'>
          <label for="account">Account:</label>
          <input type="text"  name="account" id="account"/>
        </div>
        <div class='form-input'>
          <label for="password">Password:</label>
          <input type="password" name="password" id="password"/>
        </div>
        <div class='btn-container'>
          <button type="button" onclick="VerifyStatus()">Login</button>
        </div>

        </form>
    </div>  
</body>


<script>

  var acc = document.getElementById('account');
  var pas = document.getElementById('password');
  

  function VerifyStatus(){
    if (acc.value === "" || pas.value === ""){
      alert("account or password cannot be empty.");  
      return;
    }
    
    document.getElementById('loginForm').submit();
  }
</script>
</html>