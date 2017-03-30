<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Manager Panel</title>
	</head>
    
    <style>
        body{
        margin: 0px;
        border:0px;
        }
        #header{
            width:1004;
            height:120px;
            background:black;
            color:white;
        }
        #sidebar{
            width: 300px;
            height:600px;
            background:#9e9e9e;
            float:left;
        }
        #data{
            
            height: 800px;
            background:#607d8b;
            color:black;
            font-family: inherit;
            font-size: 25px;
            
        }
        #adminlogo{
            background: white;
            border-radius: 50px;
            height: 80px;
            width: 80px;
            margin-top: 20px;
        }
        ul li{
            padding: 20px;
            border-bottom: 2px solid grey;
            
        }
        ul li:hover{
            background: #9e9e9e;
            color:white;
        }
    </style>
	<body>
        <div id="header">
            <a href="manager.php"><center><img src="pics/User-icon.png" alt="adminlogo" id="adminlogo"><br>Manager logged in
            </center>
            </a>
        </div>
        
        <div id="sidebar">
            <ul>
                <a href="setSystem.php" style="color:black"><li>Set System</li></a>
                <a href="selectSpot.php" style="color:black"><li>Set Spots</li></a>
                <a href="findUser.php" style="color:black"><li>Find User</li></a>
                <a href="graph.php" style="color:black"><li>Data Graph</li></a>
                <a href="add.php" style="color:black"><li>Add User</li></a>
            </ul>
        
        </div>
        
        <div id="data">
            <form action="graph.php" method="post">
                Select time period you want to draw:<br>
                start <input type="month" name="stmonth"><br>
                end <input type="month" name="endmonth"><br>
                <input type="submit" value="Submit" name="submit"><br>
            </form>
            
            <?php
            
                include 'connection.php';
                 include("pChart/class/pData.class.php"); 
                include("pChart/class/pDraw.class.php"); 
                include("pChart/class/pImage.class.php"); 
            
                define("CLASS_PATH", "../../../class"); 
                define("FONT_PATH", "../../../fonts"); 
            /*

                if (!empty($_POST['submit']))
                {
                    $stmonth=$_POST['stmonth'];
                    $result = mysqli_query($conn,"SELECT * FROM profit");
                    $profit=array();
                    
                    while($t =mysqli_fetch_assoc($result) ){
                        
                        array_push($profit,$t["profits"]);
                        
                                                
                    }

                }
            */
            
        ?>
        
            
        </div>
        

	</body>
</html>