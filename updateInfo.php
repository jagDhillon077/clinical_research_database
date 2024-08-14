
<html>
<h2>Profile Information</h2>
<hr>
<head>
	<title>Own Information</title>
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

$researcherid = $_GET['researcherid'];
$staffid = $_GET['staffid'];
$sql = "SELECT Researcher_ID, a.Staff_ID, e.Staff_FirstName, e.Staff_LastName, Specialty, University_Name, e.Hospital_Name FROM affiliated_researchers a, employed_staff e  WHERE a.Staff_ID = e.Staff_ID AND Researcher_ID=1";

$result = $db_conn->query($sql);
$row = $result->fetch_assoc();

if(isset($_POST['updateInfo'])) {
	$first = $_POST['first'];
	$last = $_POST['last'];
    $specialty = $_POST['specialty'];
    $university = $_POST['university'];
	$hospital = $_POST['hospital'];

	$sql1 = "update affiliated_researchers set University_Name='$university', Specialty='$specialty' WHERE Researcher_ID=$researcherid";
    $result1 = $db_conn->query($sql1);

	$sql2 = "update employed_staff set Staff_FirstName='$first', Staff_LastName='$last', Hospital_Name='$hospital' WHERE Staff_ID=$staffid";
    $result2 = $db_conn->query($sql2);
	
	if($result1 && $result2){
		$db_conn->close();
        header("location:profile.php"); // redirects back to profile page
        exit;
    }
}
    
?>

<form method="POST">

<label>First Name:</label><input type="text" value="<?php echo $row['Staff_FirstName'];?>" name="first"><br /><br />
<label>Last Name:</label><input type="text" value="<?php echo $row['Staff_LastName'];?>" name="last"><br /><br />
<label>Specialty: </label><input type="text" value="<?php echo $row['Specialty'];?>" name="specialty"><br /><br />
<label>University:</label><input type="text" value="<?php echo $row['University_Name'];?>" name="university"><br /><br />
<label>Hospital: </label><input type="text" value="<?php echo $row['Hospital_Name'];?>" name="hospital"><br /><br />

<input type="submit" value="Update" name="updateInfo"></p>

</form>



</body>

</html>