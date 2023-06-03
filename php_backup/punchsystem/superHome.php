<?php
    $account = $_GET['account'];    
?>


<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="superHome.css">
</head>


<body onload="startTime()">

    <div class="tab">
        <div class="headshot">
            <img src="pics/admincat.jpg" alt="">
            <h3><?php echo $account?></h3>
        </div>
        <button class="tablinks" onclick="openTab(event, 'MainPage')" id="Ppage" >MainPage</button>
        <button class="tablinks" onclick="openTab(event, 'Record')" id="Rpage">RecordManagement</button>
        <button class="tablinks" onclick="openTab(event, 'Salary')" id="Spage">SalaryManagement</button>
        <div class="logout-btn-container">
            <button class="tablinks" onclick="Logout()">Logout</button>
        </div>
    </div>

    <div id="MainPage" class="tabcontent">
        <div class="time-display">  
            current time:&nbsp;&nbsp;<p id="time"></p>
        </div>
        <div class="profilecontent">
            <div class="Welcome">
                <h1 class="typing-text" id="typing-text"></h1>
            </div>
            <div class="popcat">
                <img src="pics/admincat.jpg" alt="">
            </div>
        </div>    
    </div>

    
    <div id="Record" class="tabcontent">
        <div class="Record-container">
            <form action="" id="Record-form" method="POST">
            <div class="header">
                <h1>Record management</h1>
            </div>
            <div class="content">
                <div class="searching-container">
                    <input type="date" name="recordDate" id="recordDate">
                    <button type="button" onclick="recordSearch()">Show</button>
                </div>
                <div class="searching-content-container">
                    <?php

                        $targetDate = $_GET['date'];

                        $host = "localhost";
                        $username = "root";
                        $userpass = "";
                        $dbname = "punchsystem";
                        $con = mysqli_connect($host, $username, $userpass, $dbname);
                        
                        if (!$con) {
                            die("Connection failed!" . mysqli_connect_error());
                        }
                        
                        // Execute a query to fetch data
                        $sql = "SELECT * FROM `punch-record` WHERE 1;";
                        $result = mysqli_query($con, $sql);
                        
                        echo "<table>";
                        echo "<tr><th>account</th><th>date</th><th>status</th></tr>";
                        if ($result) 
                        {
                            // Check if there are any rows returned
                            if (mysqli_num_rows($result) > 0) 
                            {
                                // Fetch and display each account value
                                
                                while ($row = mysqli_fetch_assoc($result)) 
                                {
                                    $acc = $row['account'];
                                    $record_date = $row['date'];
                                    $record_time = $row['time'];
                                    $record_stat = $row['status'];
                                    if ($targetDate === $record_date)
                                    {
                                        echo "<tr>
                                          <td>$acc</td>
                                          <td>$record_time</td>
                                          <td>$record_stat</td>
                                          <td><button type='button' onclick=\"deleteData('$acc','$record_time')\">刪除</button></td>
                                        </tr>";
                                    }
                                }
                            } 
                            else 
                            {
                                echo "No records found.";
                            }
                        } 
                        else 
                        {
                            echo "Error: " . mysqli_error($con);
                        }
                        echo "</table>";
                        // Free the result set
                        mysqli_free_result($result);
                        
                        mysqli_close($con);
                    ?>
                </div>
                <div class="add-container">
                    <h3>Want to add record?</h3>
                    <button type="button" onclick="addRecord()">Go to add</button>
                </div>
            </div>
            </form>
        </div>
    </div>

                    
    <div id="Salary" class="tabcontent ">
        <form action="" id="Salary-form" method="POST">
            <div class="Salary-container">
                <div class="header-container">
                    <h1>Salary management</h1>
                </div>
                <div class="input-container">
                    <input type="month" name="month" id="month">
                    <button type="button" onclick="salarySearch()">Search</button>
                </div>
                <div class="salary-display-container">
                    <?php
                        $targetDate = $_GET['month'];
                        
                        $host = "localhost";
                        $username = "root";
                        $userpass = "";
                        $dbname = "punchsystem";
                        $con = mysqli_connect($host, $username, $userpass, $dbname);
                        
                        if (!$con) {
                            die("Connection failed!" . mysqli_connect_error());
                        }
                        
                        $sql = "SELECT * FROM `Salary` WHERE `month` = '$targetDate';";
                        $result = mysqli_query($con, $sql);
                        
                        echo "<table>";
                        echo "<tr><th>account</th><th>month</th><th>salary</th></tr>";
                        if ($result) 
                        {
                            if (mysqli_num_rows($result) > 0) 
                            {
                                while ($row = mysqli_fetch_assoc($result)) 
                                {
                                    $acc = $row['account'];
                                    $salary  = $row['salary_amount'];
                                    $month   = $row['month'];

                                    if ($targetDate === $month)
                                    {
                                        echo "<tr>
                                        <td>$acc</td>
                                        <td>$month</td>
                                        <td>$salary</td>
                                        <td><button type='button' onclick=\"deleteSalary('$acc','$month')\">刪除</button></td>
                                        </tr>";
                                    }
                                }
                            } 
                            else 
                            {
                                echo "No records found.";
                            }
                        } 
                        else 
                        {
                            echo "Error: " . mysqli_error($con);
                        }
                        echo "</table>";
                        // Free the result set
                        mysqli_free_result($result);
                        mysqli_close($con);
                    ?>
                </div>
                <div class="salary-add-container">
                    <h3>Assign Salary</h3>
                    <button type="button" onclick="salaryAdd()">Go to Assign</button>
                </div>
            </div>
        </form>
    </div>


</body>


<script> 
    /* bar controler */1
    const page = 
    '<?php
        $page = $_GET['page'];
        if ($page){
            echo $page;
        }
        else {
            echo 'Ppage';
        }
    ?>'
    document.getElementById(page).click();
    function openTab(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";

        const RecordElement = document.getElementById('record');
        RecordElement.innerText = "";
    }

    /* text typing animation */
    function typeText(element, text, delay, callback) {
        var index = 0;
        var interval = setInterval(function() {
            element.textContent = text.slice(0, index);
            index++;

            if (index > text.length) {
                clearInterval(interval);
                if (callback) {
                    callback();
                }
            }
        }, delay);
    }
    var textElement1 = document.getElementById('typing-text');
    var text1 = 'Welcome <?php echo ( $account );?>!';

    var textElement2 = document.getElementById('typing-ready');
    var text2 = 'What will happen if the world without you ?';
    typeText(textElement1, text1, 100, function() {
        typeText(textElement2, text2, 100);
    });




    /* clock controler */
    function startTime() 
    {
        const today = new Date();
        let h = today.getHours();
        let m = today.getMinutes();
        let s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('time').innerHTML = h + ":" + m + ":" + s;
        setTimeout(startTime, 1000);
    }

    function checkTime(i) 
    {
        if (i < 10) {
            i = "0" + i
        }; // add zero in front of numbers < 10
        return i;
    }


    /* Record search */
    function recordSearch() 
    {
        var elem = document.getElementById('recordDate');
        var targetDate = elem.value;
        var account = "<?php echo $account?>";
        var act = "superHome.php?account=" + encodeURIComponent(account)+"&date=" + encodeURIComponent(targetDate)+"&page=Rpage";
        document.getElementById('Record-form').action = act;
        document.getElementById('Record-form').submit();
    }

    function deleteData(account,time)
    {
        var Date = "<?php echo $_GET['date'];?>";
        var actw = "deletePunchData.php?account="+encodeURIComponent(account)+"&time="+encodeURIComponent(time)+"&date="+encodeURIComponent(Date);
        document.getElementById('Record-form').action = actw;
        document.getElementById('Record-form').submit();
    }


    function addRecord()
    {
        window.location.href = "superAdd.php";
    }


    function salaryAdd()
    {
        window.location.href = "superSalaryAdd.php";
    }
    function salarySearch()
    {
        var month = document.getElementById('month');

        var act = "superHome.php?month="+encodeURIComponent(month.value.replace(/-/g,""))+"&page=Spage";
        
        document.getElementById('Salary-form').action = act;
        document.getElementById('Salary-form').submit();
    }
    
    function deleteSalary(account,month)
    {
        var act = "deleteSalaryData.php?account="+encodeURIComponent(account)+"&month="+encodeURIComponent(month);
        
        document.getElementById('Salary-form').action = act;
        document.getElementById('Salary-form').submit();
    }
    
    // logout function

    function Logout() {
        alert("Logout successful");
        window.location.href = "superLogin.php";
    }

    

</script>

</html>