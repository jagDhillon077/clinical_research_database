<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Medilife - Health &amp; Medical Template | Home</title>
    <link rel="icon" href="img/core-img/favicon.ico">
    <link rel="stylesheet" href="style.css">
    <?php require 'DB-Functions.php'; ?>
</head>

<body>
    <!-- <div class="second-navbar">
        <a href="profile.php">Profile</a>
        <a href="ownTrials.php">View Own CT's</a>
        <a href="index.php">View All CT's</a>
        <a href="patientLookup.php">Patient Lookup</a>
        <a href="login.html">Logout</a>
    </div> -->
    <?php include_once 'navbar.php'?>

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
                                                            <label for="status">Select a status:</label><br>
                                                            <select id="status" name="status">
                                                                <option value="Any">Any</option>
                                                                <option value="Active">Active</option>
                                                                <option value="Inactive">Inactive</option>
                                                            </select>

                                                            <br>
                                                            <br>

                                                            <label> Approval from </label><br>
                                                            <input type="date" name="Approval_Date_Start" id="Approval_Date_Start" value=Approval_Date_Start>
                                                            <label> to </label>
                                                            <input type="date" name="Approval_Date_End" id="Approval_Date_End" value=Approval_Date_End>

                                                            <br>
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
                                                            <label for="count_status">Count</label> <br> 
                                                            <select id="count_something" name="count_something">
                                                                <option value="Research_Status">Status</option>
                                                            </select>
                                                            <br> <br>

                                                            <input type="checkbox" id="count_entries" name="count_entries" value="True">
                                                            <label for="count_entries">Count Entries</label> <br>

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

    <!-- ***** Studies ***** -->
    <?php 
        connectToDB();
        handleDisplayRequest();
        
    ?>

    <div class="container">
        <form method="GET" action="index.php" class="container" style="
                margin-bottom: 50px;
                background-color: #20395a;
                color: black;
                border-radius:  10px;
                padding: 20px;
                width: 400px;
            ">

            <label> Find studies with </label> <br>
            <select id="divide_by" name="divide_by" style='float:inherit;margin:5px;'>
                <option value="affiliated_researchers">All Researchers</option>
            </select> 
            <br> <br>
            <button class="btn medilife-btn" name="find_div" style="display:grid;" value=1>Find</button>
        </form>

        <?php 
            handleDivision();
            disconnectFromDB();
        ?>
    </div>



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



</html>