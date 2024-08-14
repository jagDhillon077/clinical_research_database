<?php

$debug = FALSE;
$db_conn;

function connectToDB(){
    global $debug;
    global $db_conn;
    if ($debug) {echo "Connecting to DB... <br>";}
    
    $servername = "dbserver.students.cs.ubc.ca";
    $username = "atsai12";
    $password = "a99740268";

    $db_conn= new mysqli($servername, $username, $password, $username);

    if ($db_conn->connect_error) {
        if ($debug) {echo "Connect Failed" . $db_conn->connect_error;}
        return false;
    } else {
        if ($debug) {echo "Successfully Connected to MYSQL. <br>";}
        return true;
    }
}

function disconnectFromDB() {
    global $db_conn;
    $db_conn->close();
    if ($debug) {echo "Disconnected from Database. <br>";}
}

function handleDisplayRequest() {
    global $db_conn;
    $filterON = false;
    
    // Add SELECT Fields
    $select_sql = "SELECT ";
    
    // Get list of selected fields
    $first_item = true;
    $filter_field_list = [];
    foreach (array_keys($_GET) as $value) {
        $strpos_result = strpos($value, "show");
        if ($strpos_result === false) {} 
        else {
            $filterON = true;
            array_push($filter_field_list, $_GET[$value]);
        }
    }

    // Convert list of selected fields into SELECT sql string
    $post_process_string = implode(", ",$filter_field_list);
    $select_sql .= $post_process_string;

    // ADD FROM and JOIN
    $select_sql .= " FROM approved_clinical_trials a, employed_staff e WHERE a.PI_ID = e.Staff_ID";

    // ADD WHERE Fields
    $sql2 = "SELECT count(*) as total_rows FROM approved_clinical_trials a, employed_staff e  WHERE a.PI_ID = e.Staff_ID";
    $sql3 = "SELECT Research_Status,count(*) as total_rows FROM approved_clinical_trials a, employed_staff e  WHERE a.PI_ID = e.Staff_ID";
    
    $sql = "SELECT Study_ID, Title, Staff_FirstName, Staff_LastName, Research_Status, Research_Field, Approval_Date, NHD_Country, REB_ID, Description FROM approved_clinical_trials a, employed_staff e  WHERE a.PI_ID = e.Staff_ID";
    $add_sql = "";
    // Status
    if ($_GET["status"]) {
        if ($_GET["status"] != "Any") {
            $add_sql .= " AND Research_Status LIKE '" . $_GET["status"] ."'";
        }
    }

    // Keywords
    if ($_GET["keyword_search"] && $_GET["keyword_in"]) {
            $add_sql .= " AND " . $_GET["keyword_in"] . " LIKE '%" . $_GET["keyword_search"] ."%'";
        }

    // Dates
    if ($_GET["Approval_Date_Start"] || $_GET["Approval_Date_End"]) {
        if ($_GET["Approval_Date_Start"] && $_GET["Approval_Date_End"]) {
            $add_sql .= " AND Approval_Date >= '" . $_GET["Approval_Date_Start"] . "' AND Approval_Date < '" . $_GET["Approval_Date_End"] . "'";
        } else if ($_GET["Approval_Date_Start"]) {
            $add_sql .= " AND Approval_Date >= '" . $_GET["Approval_Date_Start"] . "'";
        } else if ($_GET["Approval_Date_End"]) {
            $add_sql .= " AND Approval_Date < '" . $_GET["Approval_Date_End"] . "'";
        }
    }
    $sql .= $add_sql;

    $select_sql .= $add_sql;
    $sql2 .= $add_sql;
    $sql3 .= $add_sql;
    $sql_status .= $sql3 . " GROUP BY " . $_GET["count_something"];

    $result = $filterON? $db_conn->query($select_sql) : $db_conn->query($sql);
    

    echo "<div class='container'>";

    make_pre_table_header($sql2, $sql_status);

    make_index_table($result, $filterON, $filter_field_list);

    echo "</div>";
}

function handleGETRequest() {
    // array_key_exists(name_of_key, array)
    // $_GET is a super global variable (dictionary) that stores info when a method="GET" is submitted. It can also collect data sent in the URL.
    if (connectToDB()) {
        if (array_key_exists('status', $_GET)) { echo handleDisplayRequest(); }
        disconnectFromDB();
    }
}

function handleDisplayPatient() {
    global $db_conn;
    $sql = "SELECT * FROM admitted_participating_patients";
    $result = $db_conn->query($sql);
    make_patient_table($result);
}

function handleDivision(){
    if ($_GET["find_div"]) {
        
        global $db_conn;

        //Find clinical trials with all researchers in it
        $divide_by_entity = $_GET["divide_by"];
        $witness_id;
        $inner_witness;
        if ($divide_by_entity == "affiliated_researchers") {
            $inner_witness = "(SELECT c.Study_ID FROM conducts c WHERE ar.Researcher_ID = c.Researcher_ID AND act.Study_ID = c.Study_ID)";
        }

        $sql = "SELECT act.Study_ID, act.Title FROM approved_clinical_trials act WHERE NOT EXISTS(SELECT * FROM $divide_by_entity ar WHERE NOT EXISTS ". $inner_witness . "); ";
        
        $result = $db_conn->query($sql);

        make_div_table($result);
    }
}

function handleJoin() {
    global $db_conn;

    $list_of_selects=[];
    $list_of_froms=[];
    $list_of_wheres=[];
    $sql = "SELECT ";

    if ($_GET["Patient_ID"]) {
        array_push($list_of_selects, "app.Patient_ID, app.Patient_FirstName, app.Patient_LastName, app.Patient_Age");
        array_push($list_of_froms, "admitted_participating_patients app, approved_clinical_trials act");

        // WHERE Filter check and add
        if (is_numeric($_GET["Patient_ID"])) {
            $study_ID_join1 = "app.Study_ID = act.Study_ID";
            $study_ID_join2 = "app.Study_ID = " . $_GET["Patient_ID"];
            array_push($list_of_wheres, $study_ID_join1, $study_ID_join2);
        } else {
            echo "Please input only one study ID";
        }
    }

    if ($_GET["agg_or_join"] === "join" && $_GET["Patient_ID"]) {
        array_push($list_of_selects, "h.Is_Research_Hospital");
        array_push($list_of_froms, "hospitals h");
        array_push($list_of_wheres, "h.Hospital_Name = app.Hospital_Name");
    }


    // Convert list of selected fields into SELECT sql string
    $processed_selects = implode(", ",$list_of_selects);
    $processed_froms = implode(", ",$list_of_froms);
    $processed_wheres = implode(" AND ",$list_of_wheres);
    $sql .= $processed_selects . " FROM " . $processed_froms . " WHERE " . $processed_wheres;

    
    if ($_GET["agg_or_join"] === "agg" && $_GET["Patient_ID"]) {
        $sql = "SELECT CAST(" . $_GET["agg_select"] . "(a.Patient_Age) as DECIMAL(10,2)) as Result FROM (". $sql . ") a";
    }

    $result = $db_conn->query($sql);

    if ($result->num_rows > 0) {
        make_join_table($result);
    } elseif ($result->num_rows == 0 && $_GET['find']) {
        echo "No results found!";
    }
    
}

function make_index_table($result, $filterON, $filter_field_list) {
    echo "<table id='main_table'>";

    if ($result->num_rows > 0) {
        if ($filterON) {
            echo "<tr>";
            foreach ($filter_field_list as $field) {
                echo "<th>" . str_replace("_", " ", $field) . "</th>";
            }
            echo"</tr>";
        } else {
            echo "<tr>
            <th>Study ID</th> 
            <th>Title</th> 
            <th>PI First Name</th> 
            <th>PI Last Name</th> 
            <th>Status</th> 
            <th>Field</th> <th>Approval Date</th> 
            <th>NHD</th> 
            <th>REB</th> 
            <th>Description</th> 
            </tr>";
        }

        // output data of each row
        while($row = $result->fetch_assoc()) {
            if ($filterON) {
                echo "<tr>";
                foreach ($filter_field_list as $field) {
                    echo "<td>" . $row[$field] . "</td>";
                }
                echo "</tr>";
            } else {
                echo "<tr> 
                <td>" . $row['Study_ID']        . "</td> 
                <td>" . $row['Title']           . "</td> 
                <td>" . $row['Staff_FirstName'] . "</td> 
                <td>" . $row['Staff_LastName']  . "</td> 
                <td>" . $row['Research_Status'] . "</td> 
                <td>" . $row['Research_Field']  . "</td> 
                <td>" . $row['Approval_Date']   . "</td> 
                <td>" . $row['NHD_Country']     . "</td> 
                <td>" . $row['REB_ID']          . "</td> 
                <td>" . $row['Description']     . "</td> 
                </tr>";
            }
        }
        
    }

    echo "</table>";
    echo "</div>";
}

function make_div_table($result) {
    //Header Row
    echo "<table id='main_table'>";
    if ($result->num_rows > 0) {
        echo "<tr>
            <th>Study_ID</th> 
            <th>Title</th>
            </tr>";
    }

    //Body Rows
    while($row = $result->fetch_assoc()) {
        echo "<tr> 
        <td>" . $row['Study_ID'] . "</td> 
        <td>" . $row['Title'] . "</td>
        </tr>";
    }

    echo "</table>";
    echo "</div>";
}

function make_join_table($result){
    //Header Row
    echo "<table id='main_table'>";
    if ($result->num_rows > 0) {
        if ($_GET["agg_or_join"] == "join") {
            echo "<tr>
            <th>ID</th> 
            <th>First Name</th>
            <th>Last Name</th>
            <th>Age</th>
            <th>Part of Research Hospital</th>
            </tr>";
        } elseif ($_GET["agg_or_join"] == "agg") {
            echo "<tr>
            <th>Result</th>
            </tr>";
        } else {
            echo "<tr>
                <th>ID</th> 
                <th>First Name</th>
                <th>Last Name</th>
                <th>Age</th>
                </tr>";
        }
    }

    //Body Rows
    while($row = $result->fetch_assoc()) {
        if ($_GET["agg_or_join"] == "join") {
            echo "<tr> 
            <td>" . $row['Patient_ID'] . "</td> 
            <td>" . $row['Patient_FirstName'] . "</td> 
            <td>" . $row['Patient_LastName'] . "</td>
            <td>" . $row['Patient_Age'] . "</td>
            <td>" . $row['Is_Research_Hospital'] . "</td>
            </tr>";
        } elseif ($_GET["agg_or_join"] == "agg") {
            echo "<tr>
            <td>" . $row['Result'] . "</td>
            </tr>";
        } else {
            echo "<tr> 
            <td>" . $row['Patient_ID'] . "</td> 
            <td>" . $row['Patient_FirstName'] . "</td> 
            <td>" . $row['Patient_LastName'] . "</td>
            <td>" . $row['Patient_Age'] . "</td>
            </tr>";
        }
    }

    echo "</table>";
    echo "</div>";

}

function make_patient_table($result){
    //Header Row
    echo "<table id='main_table'>";
    if ($result->num_rows > 0) {
        echo "<tr>
            <th>Patient_ID</th> 
            <th>DOB</th> 
            <th>Patient First Name</th> 
            <th>Patient Last Name</th> 
            <th>Phone Number</th> 
            <th>Hospital</th>
            </tr>";
    }

    //Body Rows
    while($row = $result->fetch_assoc()) {
        echo "<tr> 
        <td>" . $row['Patient_ID'] . "</td> 
        <td>" . $row['DOB'] . "</td> 
        <td>" . $row['Patient_FirstName'] . "</td> 
        <td>" . $row['Patient_LastName'] . "</td> 
        <td>" . $row['Phone'] . "</td> 
        <td>" . $row['Hospital_Name'] . "</td>
        </tr>";
    }

    echo "</table>";
    echo "</div>";
}

function make_pre_table_header($sql2, $sql_status){
    global $db_conn;

    echo "<ul class='above_table'>";
    $count_table = $db_conn->query($sql2);
    $count_row = $count_table->fetch_assoc();

    if ($_GET["count_entries"]) { echo "<li> Displaying " . $count_row['total_rows'] . " entries </li>"; }

    if ($_GET["count_status"]) {

        $status_result = $db_conn->query($sql_status);

        $active_results;
        $inactive_results;

        while ($row = $status_result->fetch_assoc()) {
            if ($row['Research_Status'] == "Active") {
                $active_results = $row['total_rows'];
            } else {
                $inactive_results = $row['total_rows'];
            }
        }

        if (!$active_results) {$active_results = 0;}
        if (!$inactive_results) {$inactive_results = 0;}
        
        echo "<li> Active: " . $active_results . "</li>";
        echo "<li> Inactive: " . $inactive_results . "</li>";
    }

    echo "</ul>";
}

?>