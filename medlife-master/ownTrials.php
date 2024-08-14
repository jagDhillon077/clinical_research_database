<html>

<head>
	<link rel="icon" href="img/core-img/favicon.ico">
    <link rel="stylesheet" href="style.css">
	<title>Own Trials</title>

	<?php include_once 'navbar.php'?>
	


</head>

<body>

	<div class="container">

	<!-- Insert -->
	<h2>Insert Trials</h2>
	<form method="POST" action="ownTrials.php">
		<input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
		Title: <input type="text"  value="<?php echo $title;?>" name="title"> <br /><br />
		Description: <br /><br />
	<textarea id="description" value="<?php echo $desc;?>" name="description" rows="8" cols="30"></textarea> <br /><br />
		Status: <select name="status" id="status">
		<option value="Active">Active</option>
    <option value="Inactive">Inactive</option>
	</select><br /><br />
	Field: <select name="fields" id="fields">
	<option value="Alzheimers">Alzheimer's</option>
	<option value="Cerebral Palsy">Cerebral Palsy</option>
	<option value="Chronic Pain">Chronic Pain</option>
	<option value="Dementia">Dementia</option>
    <option value="Oncology">Oncology</option>
    <option value="Pediatrics">Pediatrics</option>
    <option value="Parkinson's">Parkinson's</option>
	<option value="IBS">IBS</option>
	<option value="COVID-19">COVID-19</option>
  </select>
  <br><br>

	Approval Date: <input type="date" id="approvaldate" name="approvaldate"
    value="2021-06-14"
    min="1950-01-01" max="2021-06-16"><br/><br />

	NHD: <select name="nhd" id="nhd">
    <option value="Canada">Canada</option>
    <option value="China">China</option>
	<option value="Germany">Germany</option>
    <option value="United Kingdom">United Kingdom</option>
	<option value="United States">United States</option>
  </select><br /><br />
		REB: <input type="text" name="reb"> <br /><br />
		<input type="submit" value="Insert" name="insertSubmit"></p>
	</form>

<?php
	$db_conn;
	
	function connectToDB(){
		global $db_conn;
		
		$servername = "dbserver.students.cs.ubc.ca";
		$username = "atsai12";
		$password = "a99740268";

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
	}

	function handleDisplayRequest() {
		global $db_conn;
		$sql = "SELECT Study_ID, Title, Description, Research_Status, Research_Field, Approval_Date, NHD_Country, REB_ID FROM approved_clinical_trials WHERE PI_ID=1";
		$result = $db_conn->query($sql);

		echo "<table>";
		echo "<tr>
		<th>ID</th> 
		<th>Title</th> 
		<th>Description</th> 
		<th>Status</th> 
		<th>Field</th> 
		<th>Approval Date</th> 
		<th>NHD</th> 
		<th>REB</th> 
		<th colspan='2' align='center'>Action</th> 
		</tr>";

		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				echo 
				"<tr><td>" . $row['Study_ID'] .
				 "</td><td>" . $row['Title'] .
				 "</td><td>" . $row['Description'] .
				 "</td><td>" . $row['Research_Status'] .
				 "</td><td>" . $row['Research_Field'] .
				 "</td><td>" . $row['Approval_Date'] .
				 "</td><td>" . $row['NHD_Country'] .
				 "</td><td>" . $row['REB_ID'] .
				  "</td><td>" . "<form method='POST' action='ownTrials.php' style='width:auto;'>" .
				  "<input type='hidden' id='deleteQueryRequest' 'name='deleteQueryRequest'>"
				  . "<input type='hidden' name='row' value= " . $row['Study_ID'] . ">"
				  . "<input type='submit' value='Delete' name='deleteTrial'</p>"
				  . "</form>"
				  .  "</td><td>" . "<form method='POST' action='editTrial.php?id=" . $row['Study_ID'] . "'style='width:auto;'>"
				  . "<input type='submit' value='Edit' name='id'</p>"
				  . "</form>" .
				  "</td></tr>";

			}
		  } else { echo "0 results"; }
		
		echo "</table>";
		echo "<label> Delete Trial by ID: </label>". "<form method='POST' action='ownTrials.php'" ."'>"
		. "<input type='text'" . "name='studyid' id='studyid'" . "size='5'" .  "maxlength='5'>" .
		"</td><td>" . "<input type='submit' value='Delete' name='deletebyid'</p>"
		. "</form>" .
		"</td></tr>";
	}

	function handleInsertRequest() {
		global $db_conn;

		//Getting the values from user and insert data into the table
		$sql = "select * from approved_clinical_trials";
		$result = $db_conn->query($sql);
		
		$studyid =  $result->num_rows+1;
		$title = $_POST['title'];
		$rid = 1;
		$desc = $_POST['description'];
		$status = $_POST['status'];
		$field = $_POST['fields'];
		$date =  $_POST['approvaldate'];
		$nhd = $_POST['nhd'];
		$reb = $_POST['reb'];

		$sql = "insert into approved_clinical_trials values ($studyid, '$title', $rid, '$desc', '$status', '$field', '$date', '$nhd', '$reb')";
		$result = $db_conn->query($sql);
	}

	function handleDeleteRequest() {
		global $db_conn;
		$id = $_POST['row'];

		$sql = "DELETE FROM approved_clinical_trials WHERE Study_ID=$id";
		$db_conn->query($sql);
	}

	function handleDeleteByID() {
		global $db_conn;
		
		if (isset($_POST['studyid'])) {
			$id = (int) $_POST['studyid'];
			$sql = "DELETE FROM approved_clinical_trials WHERE Study_ID=$id";
			$db_conn->query($sql);
		}
	}


	function handleGETRequest() {
		// array_key_exists(name_of_key, array)
		// $_GET is a super global variable (dictionary) that stores info when a method="GET" is submitted. It can also collect data sent in the URL.
		if (connectToDB()) {
			handleDisplayRequest();
			disconnectFromDB();
		}
	}

	function handlePOSTRequest() {
		if (connectToDB()) {
			if (array_key_exists('updateTrial', $_POST)) {
				handleUpdateRequest();
			} else if (array_key_exists('deletebyid', $_POST)) {
				handleDeleteByID();
			} else if (array_key_exists('insertQueryRequest', $_POST)) {
				handleInsertRequest();
			} else if (array_key_exists('deleteTrial', $_POST)) {
				handleDeleteRequest();
			}

			disconnectFromDB();
		}
	}
	
	if (isset($_POST['insertSubmit']) || isset($_POST['updateTrial']) || isset($_POST['deleteTrial']) || isset($_POST['deletebyid'])) {
		handlePOSTRequest(); 
	}

	handleGETRequest();
	?>
	
</body>
</html>
