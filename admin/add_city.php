<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(isset($_POST['add_city'])) {
    // Getting Post Values
    $city_name = $_POST['city_name'];

    // Query for inserting city name into the city table
    $sql = "INSERT INTO city (city_name) VALUES (:city_name)";
    
    // Preparing the query
    $query = $dbh->prepare($sql);
    
    // Binding the values
    $query->bindParam(':city_name', $city_name, PDO::PARAM_STR);
    
    // Execute the query
    if($query->execute()) {
        echo "<script>alert('City added successfully');</script>";
    } else {
        echo "<script>alert('Error: Could not add city');</script>";
    }
}
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <title>Add City</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <style>
        .form-container {
            width: 300px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .form-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }
        .form-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            border: none;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div id="wrapper">
<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
<!-- / Header -->
<?php include_once('includes/header.php');?>
<!-- / Leftbar -->
<?php include_once('includes/leftbar.php');?>
    </nav>
    <div class="form-container">
        <h2>Add City</h2>
        <form method="post">
            <input type="text" name="city_name" placeholder="City Name" required>
            <input type="submit" name="add_city" value="Add City">
        </form>
    </div>
    </div>
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>
    <script src="../dist/js/sb-admin-2.js"></script>
</body>
</html>
