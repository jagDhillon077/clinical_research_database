<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Title  -->
    <title>Medilife - Health &amp; Medical Template | Home</title>

    <!-- Favicon  -->
    <link rel="icon" href="img/core-img/favicon.ico">

    <!-- Style CSS -->
    <link rel="stylesheet" href="style.css">

</head>

<!-- PHP body -->
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
        $post_process_string = implode(", ",$filter_field_list);
        $select_sql .= $post_process_string;

        // ADD FROM and JOIN
        $select_sql .= " FROM approved_clinical_trials a, employed_staff e WHERE a.PI_ID = e.Staff_ID";

        // ADD WHERE Fields
        $sql = "SELECT Study_ID, Title, Staff_FirstName, Staff_LastName, Research_Status, Research_Field, Approval_Date, NHD_Country, REB_ID, Description FROM approved_clinical_trials a, employed_staff e  WHERE a.PI_ID = e.Staff_ID";
        $sql2 = "SELECT count(*) as total_rows FROM approved_clinical_trials a, employed_staff e  WHERE a.PI_ID = e.Staff_ID";
        $sql3 = "SELECT Research_Status,count(*) as total_rows FROM approved_clinical_trials a, employed_staff e  WHERE a.PI_ID = e.Staff_ID";

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
        $select_sql .= $add_sql;
        $sql .= $add_sql;
        $sql2 .= $add_sql;
        $sql3 .= $add_sql;
        $sql_status .= $sql3 . " GROUP BY Research_Status";
        

        $result;
        
        if ($filterON) {
            // echo "using select sql: " . $select_sql . "<br>";
            $result = $db_conn->query($select_sql);
        } else {
            // echo "using sql: " . $sql . "<br>";
            $result = $db_conn->query($sql);
        }


        $row = $result->fetch_assoc();
        print_r($row);

        $count_table = $db_conn->query($sql2);
        $count_row = $count_table->fetch_assoc();

        echo "<div class='container'>";

        echo "<ul class='above_table'>";

        echo "<li> Displaying " . $count_row['total_rows'] . " entries </li>";

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
                    <td> <a href='study_id.php?study_ID= " . $row['Study_ID'] . "'>" . $row['Study_ID'] . "</a> </td> 
                    <td>" . $row['Title'] . "</td> 
                    <td>" . $row['Staff_FirstName'] . "</td> 
                    <td>" . $row['Staff_LastName'] . "</td> 
                    <td>" . $row['Research_Status'] . "</td> 
                    <td>" . $row['Research_Field'] . "</td> 
                    <td>" . $row['Approval_Date'] . "</td> 
                    <td>" . $row['NHD_Country'] . "</td> 
                    <td>" . $row['REB_ID'] . "</td> 
                    <td>" . $row['Description'] . "</td> 
                    </tr>";
                }
            }
            
        }

        echo "</table>";
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


?>

<body>
    <div class="second-navbar">
        <a href="profile.html">Profile</a>
        <a href="#news">View Own CT's</a>
        <a href="#news">View All CT's</a>
      </div>
    <!-- ***** Header Area Start ***** -->
    <header class="header-area">
        <!-- Top Header Area -->
        <div class="top-header-area">
            <div class="container h-100">
                <div class="row h-100">
                    <div class="col-12 h-100">
                        <div class="h-100 d-md-flex justify-content-between align-items-center">
                            <p>Welcome to <span>Medifile</span></p>
                            <a href="login.html"><span>Logout</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header Area -->
        <div class="main-header-area" id="stickyHeader">
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-12 h-100">
                        <div class="main-menu h-100">
                            <nav class="navbar h-100 navbar-expand-lg">
                                <!-- Logo Area  -->
                                <a class="navbar-brand" href="index.html"><img src="img/core-img/logo.png" alt="Logo"></a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->



    

    <!-- ***** Book An Appoinment Area Start ***** -->
    <div class="medilife-book-an-appoinment-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="appointment-form-content">
                        <div class="row no-gutters align-items-center">
                            <div class="col-12 col-lg-9">
                                <div class="medilife-appointment-form">
                                    <h2 class="FindStudyHeader">Find a Study</h2>
                                        <div class="row align-items-end">
                                            <div class="col-12 col-md-4">
                                                <div>
                                                    <form method="GET" action="index.php" class="appointment-form-container">
                                                        <div class="where_form">
                                                            <label for="status">Select a status:</label>
                                                            <select id="status" name="status">
                                                                <option value="Any">Any</option>
                                                                <option value="Active">Active</option>
                                                                <option value="Inactive">Inactive</option>
                                                            </select>

                                                            <br>

                                                            <label> Approval from </label>
                                                            <input type="date" name="Approval_Date_Start" id="Approval_Date_Start" value=Approval_Date_Start>
                                                            <label> to </label>
                                                            <input type="date" name="Approval_Date_End" id="Approval_Date_End" value=Approval_Date_End>

                                                            <br>

                                                            <label for="keyword_search">Search for keyword:</label>
                                                            <input type="text" class="form-control" id="keyword_search" name="keyword_search" placeholder="[keyword]">

                                                            <label >in:</label> <br>

                                                            <input type="radio" id="title_radio_button" name="keyword_in" value="Title">
                                                            <label for="title_radio_button">Title</label> <br>

                                                            <input type="radio" id="description_radio_button" name="keyword_in" value="Description">
                                                            <label for="description_radio_button">Description</label> <br>

                                                            <input type="radio" id="field_radio_button" name="keyword_in" value="Research_Field">
                                                            <label for="field_radio_button">Field</label> <br>
                                                            
                                                            <input type="radio" id="first_name_radio_button" name="keyword_in" value="Staff_FirstName">
                                                            <label for="first_name_radio_button">First Name</label> <br>

                                                            <input type="radio" id="last_name_radio_button" name="keyword_in" value="Staff_LastName">
                                                            <label for="last_name_radio_button">Last Name</label> <br>

                                                            <input type="radio" id="NHD_radio_button" name="keyword_in" value="NHD_Country">
                                                            <label for="NHD_radio_button">NHD Country</label> <br>

                                                            <input type="radio" id="REB_radio_button" name="keyword_in" value="REB_ID">
                                                            <label for="REB_radio_button">REB ID</label> <br> <br>

                                                            <input type="checkbox" id="count_status" name="count_status" value="True">
                                                            <label for="count_status">Count Status</label> <br>
                                                        </div>

                                                        <div class="show_only">
                                                            <ul> <label> Select which fields to display: </label> </ul>
                                                                <li><input type="checkbox" name="show_field_id" value="Study_ID"> <label>Study ID</label></li>
                                                                <li><input type="checkbox" name="show_field_title" value="Title"> <label>Study Title</label></li>
                                                                <li><input type="checkbox" name="show_field_firstname" value="Staff_FirstName"> <label>First Name</label></li>
                                                                <li><input type="checkbox" name="show_field_lastname" value="Staff_LastName"> <label>Last Name</label></li>
                                                                <li><input type="checkbox" name="show_field_status" value="Research_Status"> <label>Research Status</label></li>
                                                                <li><input type="checkbox" name="show_field_field" value="Research_Field"> <label>Research Field</label></li>
                                                                <li><input type="checkbox" name="show_field_approval" value="Approval_Date"> <label>Approval Date</label></li>
                                                                <li><input type="checkbox" name="show_field_NHD" value="NHD_Country"> <label>NHD</label></li>
                                                                <li><input type="checkbox" name="show_field_REB" value="REB_ID"> <label>REB</label></li>
                                                                <li><input type="checkbox" name="show_field_description" value="Description"> <label>Description</label></li>
                                                        </div>



                                                        <button class="btn medilife-btn" name="view_filter">Find</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Book An Appoinment Area End ***** -->



    <!-- ***** Studies ***** -->
    <?php 
        connectToDB();
        handleDisplayRequest();
    
        // if (isset($_GET['view_filter'])) { handleGETRequest(); }
    
        disconnectFromDB();
    ?>

    <!-- jQuery (Necessary for All JavaScript Plugins) -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <!-- Popper js -->
    <script src="js/popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Plugins js -->
    <script src="js/plugins.js"></script>
    <!-- Active js -->
    <script src="js/active.js"></script>

</body>

<footer>&copy; 2021 </footer>

</html>