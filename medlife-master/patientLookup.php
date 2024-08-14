<html>
    
<head>
    <?php require 'DB-Functions.php'; ?>

    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Patient Lookup</title>
    <link rel="icon" href="img/core-img/favicon.ico">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <?php include_once 'navbar.php'?>


    <div class="container">
        <h1> Patient Lookup </h1>

        <?php 
            connectToDB();
            handleDisplayPatient();
        ?>
        
        <form method="GET" action="patientLookup.php" class="container" style="
            margin-bottom: 50px;
            background-color: #20395a;
            color: black;
            border-radius:  10px;
            padding: 20px;
            width: 400px;
        ">

            <!-- Join -->

            <!-- Patients-Study -->
            <label for="search_for_patients"> Find patients involved in study: </label>
            <input type="text" class="form-control" id="Patient_ID" name="Patient_ID" placeholder="1">

            <!--Patients-Hospital-->
            <input type="radio" id="Is_Research_Hospital" name="agg_or_join" value="join">
            <label> Show if part of research hospital <br></label><br><br>

            <!--Age Aggregation-->
            <input type="radio" id="do_agg_select" name="agg_or_join" value="agg">
            <label for="agg_select">Find the</label>
                <select id="agg_select" name="agg_select">
                    <option value="AVG">Average</option>
                    <option value="MAX">Max</option>
                    <option value="MIN">Min</option>
                </select>
            <label> of patient ages in this study </label> <br> <br>

            <button class="btn medilife-btn" name="find" value=1>Find</button>

        </form>

        <?php
            handleJoin();
            disconnectFromDB();
        ?>
    </div>


    
</body>


</html>