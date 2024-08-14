
<html>
<h2>Update Assistant Information</h2>
<hr>

<head>
	<title>Update Assistant</title>
	<style type = "text/css">
	label {
    display: inline-block;
    float: left;
    clear: left;
    width: 250px;
    text-align: center;
    font-family: arial;
	font-size: 18px;
	background-color: #588c7e;
	color: white;
	}
	tr:nth-child(even) {background-color: #f2f2f4}
input {
  display: inline-block;
  float: left;
  
}
select {
  display: inline-block;
  width: 145px;
  float: left;
}
	</style>
</head>

<body>
<?php

include "dbConn.php";

$oldfirst = $_GET['first'];
$oldlast = $_GET['last'];

$sql = "select * from supervised_research_assistants where RA_FirstName='$oldfirst'AND RA_LastName='$oldlast' AND Researcher_ID=1";
$result = $db_conn->query($sql);
$row = $result->fetch_assoc();

if ($db_conn->connect_error) {
    echo "not successful";
}
  
if(isset($_POST['updateRA'])) {
        $first = $_POST['firstname'];
        $last = $_POST['lastname'];
        $dept = $_POST['department'];
        $phone = $_POST['phonenumber'];
	
    $sql = "update supervised_research_assistants set RA_FirstName='$first', RA_LastName='$last', Department='$dept', Phone_Number='$phone' WHERE Researcher_ID=1 AND RA_FirstName='$oldfirst' AND RA_LastName='$oldlast'";

    $result = $db_conn->query($sql);



	  if($result){
       
		$db_conn->close();
        header("location:profile.php"); // redirects to profile page
        exit;
    }
    else
    {
        echo "error";
    }  
}
?>

<form method="POST">  
<!-- action="profile.php"> -->
	<label>First Name:</label> <input type="text"  value="<?php echo $row['RA_FirstName'];?>" pattern="^\S+$" name="firstname" id="firstname" size="20" maxlength="20"> <br /><br />
	<label>Last Name:</label> <input type="text"  value="<?php echo $row['RA_LastName'];?>" pattern="^\S+$" name="lastname" id="lastname" size="20" maxlength="20"> <br /><br />
	<label>Department:</label> <select name="department" id="department">
	<option <?php echo ($row['Department'] == 'Cardiology') ? 'selected' : '' ?> value='Cardiology'>Cardiology</option>
    <option <?php echo ($row['Department'] == 'Gastroenterology') ? 'selected' : '' ?> value='Gastroenterology'>Gastroenterology</option>
	<option <?php echo ($row['Department'] == 'Haemotology') ? 'selected' : '' ?> value='Haemotology'>Haemotology</option>
	<option <?php echo ($row['Department'] == 'Obstetrics') ? 'selected' : '' ?> value='Obstetrics'>Obstetrics</option> 
	<option <?php echo ($row['Department'] == 'Oncology') ? 'selected' : '' ?> value='Oncology'>Oncology</option> 
    <option <?php echo ($row['Department'] == 'Neonatology') ? 'selected' : '' ?> value='Neonatology'>Neonatology</option> 
	<option <?php echo ($row['Department'] == 'Urology') ? 'selected' : '' ?> value='Urology'>Urology</option>
 	</select>
  	<br><br>
	<label>Phone Number: </label><input type="tel" value="<?php echo $row['Phone_Number'];?>" name="phonenumber"> <br /><br />
	<input type="submit" value="Update" name="updateRA"></p>
</form>




</body>

</html>