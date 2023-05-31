<?php
    session_start();
    
    $account = $_SESSION['account'];
    $searching_account = $_GET['account'];
    if (isset($_POST['searchingDate'])){
        $searchingDate = $_POST['searchingDate'];
    }

?>


<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
    * {
        box-sizing: border-box
    }

    body {
        font-family: "Lato", sans-serif;
        margin: 0;
        background-image: url(pics/bg.jpg);
        background-repeat: no-repeat;
        background-size: cover;
    }

    /* Style the tab */
    .tab {
        float: left;
        width: 15%;
        height: 100vh;
        padding-right: 20px;
        border: 1px rgb(206, 206, 206) solid;
    }

    .tab img {
        width: 90%;
        border-radius: 50px;
    }

    .tab .headshot {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: auto;
        height: 300px;
    }

    /* Style the buttons inside the tab */
    .tab button {
        display: block;
        background-color: inherit;
        color: black;
        padding: 22px 16px;
        width: 100%;
        border: none;
        outline: none;
        text-align: left;
        cursor: pointer;
        transition: 0.2s;
        font-size: 17px;
        border-radius: 0 30px 30px 0;
    }


    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
        width: 100%;
    }

    /* Create an active/current "tab button" class */
    .tab button.active {
        background-color: #757575;
        animation: buttonAnimation .3s;
        width: 100%;
    }

    @keyframes buttonAnimation {
        0% {
            width: 0%;
        }

        100% {
            width: 100%;
        }
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        margin: auto;
        padding: 5% 5%;
        width: 85%;
        border-left: none;
    }

    .profilecontent {
        display: flex;
        align-items: center;

    }

    .profilecontent .popcat {
        margin: auto;
    }

    .profilecontent .Welcome {
        margin: auto;
    }

    .profilecontent .popcat img {
        width: 300px;
    }

    .time-display {
        font-size: 72px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .btncontainer {
        display: flex;
        margin-top: 15%;
    }

    .btncontainer button {
        margin: auto;
        padding: 20px 130px;
        background-color: #cfcecc;
        border: 0px;
        border-radius: 20px;
        font-size: xx-large;
        transition: .3s;
    }

    .btncontainer button:hover {
        background-color: #b4b4b4;

    }

    .typing-text {
        font-family: monospace;
        white-space: pre;
        overflow: hidden;
        border-right: 0px;
        animation: typing 2s steps(40, end);
    }

    @keyframes typing {
        from {
            width: 0;
        }

        to {
            width: 100%;
        }
    }


    .record-text {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .record-text h1 {
        font-size: 72px;
    }

    .record-text p {
        font-size: 24px;
    }

    .input-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .input-container input {

        font-size: 20px;
        margin-right: 10px;

    }

    .input-container .record-search-btn button {
        font-size: 20px;
        padding: 8px 15px;
        background-color: #cfcecc;
        border: 0px;
        border-radius: 20px;
        transition: .3s;
    }

    .input-container .record-search-btn button:hover {
        background-color: #b4b4b4;
    }

    .record {

        padding-top: 30px;
        margin: 30px auto;
        display: flex;
        flex-direction: column;
        align-items: center;

        font-size: xx-large;
        font-family: monospace;
    }

    .recordData-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 65%;
        text-align: center;
    }
    
    .recordData-container-form {
        border-bottom: #757575 solid 1px;
        margin-top: 20px;
    }
    
    .recordData {
        font-family: monospace;
        font-size: 34px;
        margin: 7px auto;
        width: 50%;
    }
    
    .Salary{
        
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
        width: auto;
        
    }
    .Salary-display{
        padding-top: 30px;
        margin: 30px auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 65%;
        font-size: xx-large;
        font-family: monospace;
    }

    .Salary-searching-container {
        display: flex;
        justify-content: center;
    }

    .Salary-searching-container input {
        font-size: 24px;
    }

    .Salary-searching-container button {
        margin-left: 20px;
        font-size: 24px;
        padding: 8px 15px;
        background-color: #cfcecc;
        border: 0px;
        border-radius: 20px;
        transition: .3s;
    }

    .Salary-searching-container button:hover{
        background-color: #b4b4b4;
    }

    .Salary-display-content{
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        width: 100%;
    }
   
    .SalaryData{
        
        font-family: monospace;
        font-size: 34px;
        margin: 10px auto;
        width: 50%;
    }

    .SalaryDetail{
        margin-top: 5vh;
        padding: 20px;
    }
    </style>
</head>

<body onload="startTime()">

    <div class="tab">
        <div class="headshot">
            <img src="pics/Cat.jpg" alt="">
            <h3><?php echo $account?></h3>
        </div>
        <button class="tablinks" onclick="openTab(event, 'Punch')" id="Ppage" >Punch</button>
        <button class="tablinks" onclick="openTab(event, 'Record')" id="Rpage">RecordSearch</button>
        <button class="tablinks" onclick="openTab(event, 'Salary')" id="Spage">SalarySearch</button>
        <div class="logout-btn-container">
            <button class="tablinks" onclick="Logout()">Logout</button>
        </div>
    </div>

    <div id="Punch" class="tabcontent">
        <form id="PunchForm" method="POST" action="">

            <div class="time-display">
                current time:&nbsp;&nbsp;<p id="time"></p>
            </div>
            <div class="profilecontent">
                <div class="Welcome">
                    <h1 class="typing-text" id="typing-text"></h1>
                    <h2 class="typing-text" id="typing-ready"></h2>
                </div>
                <div class="popcat">
                    <img src="pics/popcat.gif" alt="">
                </div>
            </div>

            <div class="btncontainer">
                <button type="button" onclick="punch('IN')">punch in</button>
                <button type="button" onclick="punch('OUT')">punch out</button>
            </div>
        </form>
    </div>

    <div id="Record" class="tabcontent">
        <form id="RecordSear" method="POST" action="">

            <div class="record-text">
                <h1>Record</h1>
                <p>Search for your punch record.</p>
            </div>

            <div class="input-container">
                <input type="date" id="searchingDate" name="searchingDate">
                <div class="record-search-btn">
                    <button type="button" onclick="recordSearch()">Search</button>
                </div>
            </div>
            <div class="record" id="">
                <?php echo ("Search Date: " .  $searchingDate ); ?>
                <?php
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
                    
                    if ($result) {
                        // Check if there are any rows returned
                        if (mysqli_num_rows($result) > 0) {
                            // Fetch and display each account value
                            echo "
                                <script>
                                    sleep(3);

                                    document.getElementById('Spage').click();

                                </script>
                                <div class='recordData-container recordData-container-form'>
                                    <div class='recordData'>Time</div>
                                    <div class='recordData'>IN/OUT</div>
                                </div>";
                            while ($row = mysqli_fetch_assoc($result)) {
                                $acc = $row['account'];
                                $record_date = $row['date'];
                                $record_time = $row['time'];
                                $record_stat = $row['status'];
                                if ($record_date === $searchingDate && $searching_account === $acc){
                                    echo "
                                        <div class='recordData-container'>
                                            <div class='recordData'>$record_time</div>
                                            <div class='recordData'>$record_stat</div>
                                        </div>";
                                }
                            }
                        } 
                        else {
                            echo "No records found.";
                        }
                    } else {
                        echo "Error: " . mysqli_error($con);
                    }
                    
                    // Free the result set
                    mysqli_free_result($result);
                    
                    mysqli_close($con);
                 ?>
            </div>
        </form>
    </div>

                    
    <div id="Salary" class="tabcontent ">    
        <form action="" id="Salary-from" method="POST">    
            <div class="Salary">
                <div class="Salary-header-container">
                    <h1>Search your Salary</h1>
                </div>
                <div class="Salary-searching-container">
                    <input type="Text" placeholder="YYYYMM" id="Salary-search">
                    <button type="button" onclick="Salary()">search</button>
                </div>
                <div class="Salary-display">
                    <?php

                        $host = "localhost";
                        $username = "root";
                        $userpass = "";
                        $dbname = "punchsystem";
                        $con = mysqli_connect($host, $username, $userpass, $dbname);
                        
                        if (!$con) {
                            die("Connection failed!" . mysqli_connect_error());
                        }

                        $searchingMonth = $_GET['date'];

                        $sql = "SELECT * FROM `Salary` WHERE 1;";
                        $result = mysqli_query($con, $sql);
                    
                        if ($result) {
                            // Check if there are any rows returned
                            if (mysqli_num_rows($result) > 0) {
                                // Fetch and display each account value
                                echo "
                                    <div class='Salary-display-content'>
                                        <div class='SalaryData'>Month</div>
                                        <div class='SalaryData'>Salary</div>
                                    </div>";
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $acc = $row['account'];
                                        $Salary_date = $row['month'];
                                        $Salary_amount = $row['salary_amount'];
                                        $retired = $Salary_amount*0.03;
                                        $Wkhours = $Salary_amount/200;
                                        if ($Salary_date === $searchingMonth && $account === $acc){
                                            echo "
                                            <div class='Salary-display-content'>
                                            <div class='SalaryData'>$Salary_date</div>
                                            <div class='SalaryData'>$Salary_amount</div>
                                            </div>
                                            <div class='Salary-display-content' >
                                                <div class='SalaryData SalaryDetail'>Detail</div>
                                            </div>
                                            <div class='Salary-display-content'>
                                                <div class='SalaryData'>Retired</div>
                                                <div class='SalaryData'>$retired</div>
                                            </div>
                                            <div class='Salary-display-content'>
                                                <div class='SalaryData'>Working-hour</div>
                                                <div class='SalaryData'>$Wkhours</div>
                                            </div>
                                            
                                            
                                            ";
                                        }
                                }
                            } 
                            else {
                                echo "No records found.";
                            }
                        } 
                        else {
                            echo "Error: " . mysqli_error($con);
                        }
                        
                        // Free the result set
                        mysqli_free_result($result);
                        
                        mysqli_close($con);
                            
                            echo "<h1></h1>";
                    ?>
                </div>
            </div>
        </form>
    </div>


</body>


<script>
/* bar controler */
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
    RecordElement.innerText = ""
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
function startTime() {
    const today = new Date();
    let h = today.getHours();
    let m = today.getMinutes();
    let s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('time').innerHTML = h + ":" + m + ":" + s;
    setTimeout(startTime, 1000);
}

function checkTime(i) {
    if (i < 10) {
        i = "0" + i
    }; // add zero in front of numbers < 10
    return i;
}


/* Record search */
function recordSearch() {

    const account = "<?php echo $account;?>";
    var Action = "Home.php?account=" + encodeURIComponent(account)+"&page=Rpage";
    document.getElementById('RecordSear').action = Action;
    document.getElementById('RecordSear').submit();
}

function Salary() {
    
    const date = document.getElementById('Salary-search').value;
    const account = "<?php echo $account;?>";

    
    var Action = "Home.php?account=" + encodeURIComponent(account) + "&date=" + encodeURIComponent(date)+"&page=Spage";
    document.getElementById('Salary-from').action = Action;
    document.getElementById('Salary-from').submit();
    
}

function punch(state) {
    const today = new Date();
    let h = today.getHours();
    let m = today.getMinutes();
    let s = today.getSeconds();

    m = checkTime(m);
    s = checkTime(s);

    let Y = today.getFullYear();
    let M = today.getMonth() + 1; // month index start from 0
    let D = today.getDate();

    M = checkTime(M);
    D = checkTime(D);

    console.log(Y + "-" + M + "-" + D);
    let RecordData = {
        Date: Y + "-" + M + "-" + D,
        Time: h + ":" + m + ":" + s,
        PunchState: state,
    }

    const account = "<?php echo $account;?>"
    var formAction = "punch.php?account=" + encodeURIComponent(account) + "&Time=" + encodeURIComponent(RecordData
        .Time) + "&Date=" + encodeURIComponent(RecordData.Date) + "&status=" + encodeURIComponent(RecordData
        .PunchState);

    document.getElementById('PunchForm').action = formAction;
    document.getElementById('PunchForm').submit();



}

// logout function


function Logout() {
    alert("Logout successful");
    window.location.href = "index.html";
}
</script>

</html>