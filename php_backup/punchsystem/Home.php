<?php
    session_start();
    
    $account = $_SESSION['account'];
    if (isset($_POST['searchingDate'])){
        $searchingDate = $_POST['searchingDate'];
    }
    
?>


<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {box-sizing: border-box}
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

        .tab img{
            width: 90%;
            border-radius: 50px;
        }

        .tab .headshot{
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
            display: flex;
            margin: auto;
            padding: 5% 5%;
            width: 85%;
            border-left: none;         
        }
        
        .profilecontent{
            display: flex;
            align-items: center;
            
        }
        .profilecontent .popcat{
            margin: auto;
        }
        .profilecontent .Welcome{
            margin: auto;
        }
        .profilecontent .popcat img{
            width: 300px;
        }

        .time-display{
            font-size: 72px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btncontainer{
            display: flex;
            margin-top: 15%;
        }

        .btncontainer button{
            margin:auto;
            padding: 20px 130px;
            background-color: #cfcecc;
            border: 0px;
            border-radius: 20px;
            font-size: xx-large;
            transition: .3s;
        }
        .btncontainer button:hover{
            background-color: #b4b4b4;

        }

        .typing-text {
            font-family: monospace;
            white-space: pre;
            overflow: hidden;
            border-right:0px;
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


        .record-text{
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .record-text h1{
            font-size: 72px;
        }

        .record-text p{
            font-size: 24px;
        }

        .input-container{
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .input-container input{
            
            font-size: 20px;
            margin-right: 10px;
            
        }

        .input-container .record-search-btn button{
            font-size: 20px;
            padding: 8px 15px;
            background-color: #cfcecc;
            border: 0px;
            border-radius: 20px;
            transition: .3s;
        }
        
        .input-container .record-search-btn button:hover{
            background-color: #b4b4b4;
        }
        .record {

            padding-top: 30px;
            margin:30px auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            
            font-size: xx-large;
            font-family: monospace;
        }

    </style>
</head>
<body onload="startTime()">

    <div class="tab">
        <div class="headshot">
            <img src="pics/Cat.jpg" alt="">
            <h3><?php echo "<script>console.log('test');</script>"?></h3>
        </div>
        <button class="tablinks" onclick="openTab(event, 'Punch')">Punch</button>
        <button class="tablinks" onclick="openTab(event, 'Record')" id="defaultOpen">RecordSearch</button>
        <button class="tablinks" onclick="openTab(event, 'Tokyo')">SalarySearch</button>
    </div>

    <div id="Punch" class="tabcontent" >
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
                <button onclick="punch('OUT')">punch out</button>
            </div>
        </form>
    </div>

    <div id="Record" class="tabcontent">
            <form method="POST" action="Home.php">
            
            <div class="record-text">
                <h1>Record</h1>
                <p>Search for your punch record.</p> 
            </div>

            <div class="input-container">
                <input type="date" id="searchingDate" name="searchingDate">
                <div class="record-search-btn">
                    <button type="submit">Search</button>
                </div>
            </div>
            <div class="record" id="">
                <?php echo ("Search Date: " .  $searchingDate ); ?>
            </div>
        </form>
    </div>


    <div id="Tokyo" class="tabcontent">
        <h3>Tokyo</h3>
        <p>Tokyo is the capital of Japan.</p>
    </div>

   
</body>


<script>
    
    /* Record data */
    let PunchinArray = [];
    let FixTestData = {
            Date:"2023-05-27",
            Time:"17:05:33",
            PunchState:"IN",
        }
    PunchinArray.push(FixTestData);
    
    /* bar controler */
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
    
    
    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
    
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
        document.getElementById('time').innerHTML =  h + ":" + m + ":" + s;
        setTimeout(startTime, 1000);
    }

    function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
    }


    /* Record search */
    function recordSearch() {
        
        const DateElement = document.getElementById('searchingDate');
        const DateVal = DateElement.value;

        // initialize
        
        const RecordElement = document.getElementById('record');
        RecordElement.innerText = "";
        for (let i=0;i<PunchinArray.length;i++){
            if (DateVal.localeCompare(PunchinArray[i].Date) === 0)
            {
                RecordElement.innerText += "Punch Time:"+PunchinArray[i].Time+" --- "+"Punch In/Out:" + PunchinArray[i].PunchState+"\n";
            }
        }
    }

    function punch(state) {
        const today = new Date();
        let h = today.getHours();
        let m = today.getMinutes(); 
        let s = today.getSeconds();

        m = checkTime(m);
        s = checkTime(s);

        let Y = today.getFullYear();
        let M = today.getMonth()+1; // month index start from 0
        let D = today.getDate();

        M = checkTime(M);
        D = checkTime(D);

        console.log(Y+"-"+M+"-"+D);
        let RecordData = {
            Date:Y+"-"+M+"-"+D,
            Time:h + ":" + m + ":" + s,
            PunchState:state,
        }

        const account = "<?php echo $account;?>"
        var formAction = "punch.php?account=" + encodeURIComponent(account)+"&Time=" + encodeURIComponent(RecordData.Time) + "&Date=" + encodeURIComponent(RecordData.Date) + "&status=" + encodeURIComponent(RecordData.PunchState); 
    
        document.getElementById('PunchForm').action = formAction;
        document.getElementById('PunchForm').submit();

        

    }

    </script>

</html> 
