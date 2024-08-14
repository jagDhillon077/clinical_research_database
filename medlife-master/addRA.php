<?php

include "dbConn.php";

if(isset($_POST['addAssistant'])) {
    $first = $_POST['firstname'];
    $last = $_POST['lastname'];
    $dept = $_POST['department'];
    $phone = $_POST['phonenumber'];
    $sql = "INSERT INTO supervised_research_assistants VALUES(1,'$first', '$last', '$phone', '$dept')";
    $result = $db_conn->query($sql);
     if ($result) {
        echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . $db_conn->error;
      }
    
      if($result){
        //echo "gets here";
        //global $db_conn;
		$db_conn->close();
        header("location:profile.php"); // redirects to ownTrials page
        exit;
    }
    else
    {
        echo "error";
    }  
}


?>



<html>

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    
    <!-- Title  -->
    <title>Add Research Assistant</title>
    
    <!-- Favicon  -->
    <link rel="icon" href="img/core-img/favicon.ico">
    
    <!-- Style CSS -->
    <link rel="stylesheet" href="style.css">
    
    <style type = "text/css">
      form label {
        margin-right:10px;
          /* display: inline-block;
          float: left;
          clear: left;
          width: 250px;
          text-align: center;
          font-family: arial;
          font-size: 18px;
          background-color: #588c7e;
          color: white; */
        }
        /* tr:nth-child(even) {background-color: #f2f2f4} */
      /* input { */
        /* display: inline-block;
        float: left; */
      /* } */
      /* select {
        display: inline-block;
        width: 145px;
        float: left;
      } */
	</style>
</head>

<header>
<!--navbar -->
<?php include_once 'navbar.php';?>
</header>


<body>
<div class="container">
 <div class="card mt-5" style="border-radius:10px;" >
    <div class="card-header">
        <h2> Add a Research Assistant</h2>
        <hr>
    </div>
    <form method="POST" style="padding-left:25px; padding-top:25px;">
         <!-- action="profile.php"> -->
        <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            <label for="firstname">First Name</label><input type="text" name="firstname"  pattern="^\S+$" id="firstname"><br /><br />
            <label for="lastname">Last Name</label><input type="text" name="lastname" pattern="^\S+$" id="lastname"><br /><br />
            <label for="department">Department</label><select name="department" id="department">
            <option value="Cardiology">Cardiology</option>
            <option value="Gastroenterology">Gastroenterology</option>
            <option value="Haemotology">Haemotology</option>
	        <option value="Neonatology">Neonatology</option>
            <option value="Obstetrics">Obstetrics</option>
            <option value="Urology">Urology</option></select>
            <br><br>
        <label for="phonenumber">Phone Number</label><input type="tel" name="phonenumber" id="phonenumber" placeholder="(123) 450-6789" pattern="({1}[0-9]{3}){1} [0-9]{3}-[0-9]{4}" class="form-control" style="color:#8f9096;"><br /><br />
        <input type="submit" value="Add" name="addAssistant"></p>
    </form>
</div>

</body>



</html>