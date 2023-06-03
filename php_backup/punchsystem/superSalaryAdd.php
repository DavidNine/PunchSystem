<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="superAdd.css">
    <title>SalaryManagement</title>
</head>
<body onload="startTime()"> 
    <form action="" id="add-form" method="POST">
        <div class='Container'>
            <a href="superHome.php?account=admin&page=Spage" class="arrow">&#8617;</a>
        <h2>Assign Salary</h2>
        <div class='form-input'>
            <label for="account">Account:</label>
            <input type="text"  name="account" id="account"/>
        </div>
        <div class='form-input'>
            <label for="month">Month:</label>
            <input type="month" name="month" id="month"/>
        </div>
        <div class='form-input'>
            <label for="salary">salary:</label>
            <input type="int" name="salary" id="salary"/>
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
        var month = document.getElementById('month');
        var salary = document.getElementById('salary');
        
        
        if (acco.value === "" ){
            alert("account cannot be empty.");
            return;
        }
        
        if (salary.value === ""){
            alert("month cannot be empty.");
            return
        }
        
        if (month.value === ""){
            alert("month cannot be empty.");
            return
        }
        
        var act = "superAddSalary.php?acc="+encodeURIComponent(acco.value)+"&month="+encodeURIComponent(month.value.replace(/-/g,""))+"&salary="+encodeURIComponent(salary.value);
        document.getElementById('add-form').action = act;
        document.getElementById('add-form').submit();
        
    }
</script>

</html>