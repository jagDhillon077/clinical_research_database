<html>

<head>
	<title>Profile Page</title>
	<style type = "text/css">
	table {
		border-collapse: collapse;
		width: 100%;
		color: #d9645;
		font-family: arial;
		font-size: 18px;
		text-align: left;
	}
	th {
		background-color: #588c7e;
		color: white;
	}
	tr:nth-child(even) {background-color: #f2f2f2}
	</style>
</head>

<h2>Personal Information</h2>
<hr>

<body>
	<!-- php -->
	<?php
	$db_conn = NULL;
	
	function connectToDB(){
		//echo "Connecting to DB... <br>";
		global $db_conn;
		
		$servername = "dbserver.students.cs.ubc.ca";
		$username = "mabrosim";
		$password = "a12275756";

		$db_conn= new mysqli($servername, $username, $password, $username);

		if ($db_conn->connect_error) {
			//echo "Connect Failed" . $db_conn->connect_error;
			return false;
		} else {
			//echo "Successfully Connected to MYSQL. <br>";
			return true;
		}
	}

	function disconnectFromDB() {
		global $db_conn;
		$db_conn->close();
		//echo "Disconnected from Database. <br>";
	}

	function handleDisplayProfile() {
		global $db_conn;
		$sql = "SELECT Researcher_ID, a.Staff_ID, Staff_FirstName, Staff_LastName, Specialty, University_Name, Hospital_Name FROM affiliated_researchers a, employed_staff e  WHERE a.Staff_ID = e.Staff_ID AND Researcher_ID=1";

		$result = $db_conn->query($sql);

		echo "<table>";
		echo "<tr>
		<th>Research ID</th> 
		<th>Staff ID</th> 
		<th>Name</th>
		<th>Specialty</th>  
		<th>University</th> 
		<th>Hospital</th> 
		<th colspan='2' align='center'>Action</th>  
		
		</tr>";

		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				echo 
				 "</td><td>" . $row['Researcher_ID'] .
				 "</td><td>" . $row['Staff_ID'] .
				 "</td><td>" . $row['Staff_FirstName'] . " ". $row['Staff_LastName'] .
				 "</td><td>" . $row['Specialty'] .
				 "</td><td>" . $row['University_Name'] .
				 "</td><td>" .$row['Hospital_Name'] .
				 "</td><td>" . "<form method='POST' action='updateInfo.php?researcherid=" . $row['Researcher_ID'] .
				 "&staffid=" . $row['Staff_ID'] . "'>"
				  . "<input type='submit' value='Edit' name='updateRA'</p>"
				  . "</form>" .
				  "</td><td>" . "<form method='POST'>" .
				  "<input type='hidden' id='deleteProfileRequest' 'name='deleteProfileRequest'>"
				  . "<input type='hidden' name='researcherid' value= " . $row['Researcher_ID'] . ">"
				  . "<input type='submit' value='Delete' name='deleteProfile'</p>"
				  . "</form>"
				 . "</td></tr>";
			}
		} else { echo "You have no profile"; }


		echo "</table>";
	}
	?>

	<?php
	function handleDisplayResearchAssistants() {
		global $db_conn;
		$sql = "SELECT RA_FirstName, RA_LastName, Department, Phone_Number FROM affiliated_researchers a, supervised_research_assistants s  WHERE a.Researcher_ID = s.Researcher_ID AND a.Researcher_ID=1";

		$result = $db_conn->query($sql);

		echo "<table>";
		echo "<tr>
		<th>Name</th>
		<th>Department</th>
		<th>Phone Number</th>
		<th colspan='2' align='center'>Action</th> 
		</tr>";

		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				echo 
				 "</td><td>" . $row['RA_FirstName'] . " ". $row['RA_LastName'] .
				 "</td><td>" . $row['Department'] .
				 "</td><td>" . $row['Phone_Number'] .
				 "</td><td>" . "<form method='POST' action='updateRA.php?first=" . $row['RA_FirstName'] .
				 "&last=" . $row['RA_LastName'] .  "'>".
				  "<input type='hidden' id='updateQueryRequest' 'name='updateQueryRequest'>"
				  . "<input type='submit' value='Edit' name='updateInfo'</p>"
				  . "</form></td><td>" . 
				  "<form method='POST' action='profile.php'>" .
				  "<input type='hidden' id='deleteAssistantRequest' 'name='deleteAssistantRequest'>"
				  . "<input type='hidden' name='firstname' value= " . $row['RA_FirstName'] . ">"
				  . "<input type='hidden' name='lastname' value= " . $row['RA_LastName'] . ">"
				  . "<input type='submit' value='Delete' name='deleteAssistant'</p>"
				  . "</form></td></tr>";
			}
		  } 
		  echo "<hr>" . "<h2> Research Assistants </h2><hr>";

		  echo "</table>";
	
	}

	function handleDeleteProfileRequest(){
		global $db_conn;
		$id = $_POST['researcherid'];
		$sql = "DELETE FROM affiliated_researchers WHERE Researcher_ID=1";
		$result = $db_conn->query($sql);
	}

	function handleDeleteAssistantRequest(){
		global $db_conn;
		$first = $_POST['firstname'];
		$last = $_POST['lastname'];

		$sql = "DELETE FROM supervised_research_assistants WHERE Researcher_ID=1 AND RA_FirstName='$first' AND RA_LastName='$last'";
		$result = $db_conn->query($sql);
	}

	function handleGETRequest() {
		// array_key_exists(name_of_key, array)
		// $_GET is a super global variable (dictionary) that stores info when a method="GET" is submitted. It can also collect data sent in the URL.
		if (connectToDB()) {
			handleDisplayProfile();
			handleDisplayResearchAssistants();
			disconnectFromDB();
		}
	}

	function handlePOSTRequest() {
		if (connectToDB()) {
			if (array_key_exists('deleteProfile', $_POST)) {
				handleDeleteProfileRequest();
			} else if (array_key_exists('deleteAssistant', $_POST)) {
				handleDeleteAssistantRequest();
			} else if (array_key_exists('insertQueryRequest', $_POST)) {
				handleInsertRequest();
			}

			disconnectFromDB();
		}
	}

	if (isset($_POST['deleteProfile']) ||  isset($_POST['insertSubmit']) || isset($_POST['deleteAssistant'])) {
		 handlePOSTRequest(); }

	handleGETRequest();
	?>
	
<form method="POST" action="addRA.php">
<input type="submit" value="Add Assistant" name="addRASubmit"></p>
</form>

	
</body>


</html>
