<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="superAdd.css">
    <title>Add record</title>
</head>
<body onload="startTime()"> 
    <form action="" id="add-form" method="POST">
        <div class='Container'>
            <a href="superHome.php?account=admin" class="arrow">&#8617;</a>
        <h2>Add record</h2>
        <div class='form-input'>
            <label for="account">Account:</label>
            <input type="text"  name="account" id="account"/>
        </div>
        <div class='form-input'>
            <label for="date">date:</label>
            <input type="date" name="date" id="date"/>
        </div>
        <div class='form-input'>
            <label for="time">time:</label>
            <input type="time" name="time" id="time" min="00:00" max="23:59" step="1"/>
        </div>
        <div class='form-input'>
            <label for="status">status:</label>
            <input type="text" name="status" id="status" placeholder="IN/OUT"/>
        </div>
        <div class='btn-container'>
            <button type="button" onclick="Add()">Add</button>
        </div>
    </form>
</div>   
</body>

<script>
    function Add() {
        var acco = document.getElementById('account');
        var stat = document.getElementById('status');
        var date = document.getElementById('date');
        var time = document.getElementById('time');
        
        if (acco.value === "" ){
            alert("account cannot be empty.");
            return;
        }
        
        if (date.value === ""){
            alert("date cannot be empty.");
            return
        }
        
        if (time.value === ""){
            alert("time cannot be empty.");
            return
        }
        
        /** check stat input */
        if (stat.value.toLowerCase() != "in" && stat.value.toLowerCase() != "out"){
            alert("please input \"IN\" or \"OUT\"");
            return;
        }
        
        var act = "superPunch.php?acc="+encodeURIComponent(acco.value)+"&date="+encodeURIComponent(date.value)+"&time="+encodeURIComponent(time.value)+"&stat="+encodeURIComponent(stat.value.toUpperCase());
        document.getElementById('add-form').action = act;
        document.getElementById('add-form').submit();
        
    }
</script>

</html>