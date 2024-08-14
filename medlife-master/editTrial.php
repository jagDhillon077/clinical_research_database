<html>

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    
    <!-- Title  -->
    <title>Update Trial Data | Home</title>
    
    <!-- Favicon  -->
    <link rel="icon" href="img/core-img/favicon.ico">
    
    <!-- Style CSS -->
    <link rel="stylesheet" href="style.css">
    
</head>

<header>
<!--navbar -->
<?php include_once 'navbar.php';?>
</header>

<body>


<?php

include "dbConn.php";

$id = $_GET['id'];
$studyid = (int) $id;
$sql = "select * from approved_clinical_trials where Study_ID=$studyid";
$result = $db_conn->query($sql);
$row = $result->fetch_assoc();

if ($db_conn->connect_error) {
    echo "not successful";
}
  
if(isset($_POST['updateTrial'])) {
        $title = $_POST['title'];
        $desc = $_POST['description'];
        $status = $_POST['status'];
        $field = $_POST['fields'];
        $approvdate = $_POST['approvaldate'];
        $nhd = $_POST['nhd'];
        $reb = $_POST['reb'];
	
    $sql = "update approved_clinical_trials set Title='$title', Description='$desc', Research_Status='$status', Research_Field='$field', Approval_Date='$approvdate', NHD_Country='$nhd', REB_ID='$reb' WHERE Study_ID=$studyid";
	echo "<br>" . $sql . "<br>";
    $result = $db_conn->query($sql);

    if ($result === TRUE) {
        echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . $db_conn->error;
      }

    if($result){
        echo "gets here";
        global $db_conn;
		$db_conn->close();
        header("location:ownTrials.php"); // redirects to ownTrials page
        exit;
    }
    else
    {
        echo "error";
    }    	
}
?>

<div class="container">
<h1>Update Trial Data</h1>

<form method="POST" >
		Title: <input type="text"  value="<?php echo $row['Title'];?>" name="title" id="title" size="40" maxlength="40"> <br /><br />
		Description:<br /><br />
    <textarea id="description" name="description" rows="8" cols="50"><?php echo $row['Description'];?></textarea>
    <br /><br />
		Status: <select name="status" id="status">
		<option <?php echo ($row['Research_Status'] == 'Active') ? 'selected' : '' ?> value='Active'>Active</option> 
        <option <?php echo ($row['Research_Status'] == 'Inactive') ? 'selected' : '' ?> value='Inactive'>Inactive</option> 
	</select><br /><br />
	Field: <select name="fields" id="fields">
    <option <?php echo ($row['Research_Field'] == 'Alzheimers') ? 'selected' : '' ?> value='Alzheimers'>Alzheimer's</option> 
    <option <?php echo ($row['Research_Field'] == 'Cerebral Palsy') ? 'selected' : '' ?> value='Cerebral Palsy'>Cerebral Palsy</option> 
	<option <?php echo ($row['Research_Field'] == 'Chronic Pain') ? 'selected' : '' ?> value='Chronic Pain'>Chronic Pain</option> 
    <option <?php echo ($row['Research_Field'] == 'Dementia') ? 'selected' : '' ?> value='Dementia'>Dementia</option> 
    <option <?php echo ($row['Research_Field'] == 'Oncology') ? 'selected' : '' ?> value='Oncology'>Oncology</option> 
    <option <?php echo ($row['Research_Field'] == 'Pediatrics') ? 'selected' : '' ?> value='Pediatrics'>Pediatrics</option> 
    <option <?php echo ($row['Research_Field'] == 'Parkinsons') ? 'selected' : '' ?> value='Parkinsons'>Parkinson's</option> 
    <option <?php echo ($row['Research_Field'] == 'IBS') ? 'selected' : '' ?> value='IBS'>IBS</option> 
    <option <?php echo ($row['Research_Field'] == 'COVID-19') ? 'selected' : '' ?> value='COVID-19'>COVID-19</option> 
  </select>
  <br><br>

	Approval Date: <input type="date" id="approvaldate" name="approvaldate"
    value="<?php echo $row['Approval_Date'];?>"
    min="1950-01-01" max="2021-06-18"><br/><br />

	NHD: <select name="nhd" id="nhd">
    <option <?php echo ($row['NHD_Country'] == 'Canada') ? 'selected' : '' ?> value='Canada'>Canada</option>
    <option <?php echo ($row['NHD_Country'] == 'China') ? 'selected' : '' ?> value='China'>China</option>
    <option <?php echo ($row['NHD_Country'] == 'Germany') ? 'selected' : '' ?> value='Germany'>Germany</option>
    <option <?php echo ($row['NHD_Country'] == 'United Kingdom') ? 'selected' : '' ?> value='United Kingdom'>United Kingdom</option>
    <option <?php echo ($row['NHD_Country'] == 'United States') ? 'selected' : '' ?> value='United States'>United States</option>
  </select><br /><br />
		REB: <input type="text" value="<?php echo $row['REB_ID'];?>" name="reb"> <br /><br />
		<input type="submit" value="Update" name="updateTrial"></p>
</form>

</body>


</html>