<?php
session_start();
//datbase connection file
include "includes/config.php";
// error_reporting(0);
if (strlen($_SESSION["usrid"]) == 0) {
    header("location:logout.php");
} else {
    // update Process
    if (isset($_POST["submit"])) {
        $uid = $_SESSION["usrid"];
        $subject = $_POST["subject"];
        $message = $_POST["message"];
        $priority = $_POST["priority"];
        $sql =
            "INSERT INTO tickets (Subject, Message, Priority, User_id, Status) VALUES (:subject, :message, :priority, :uid, 'open')";
        $query = $dbh->prepare($sql);
        $query->bindParam(":subject", $subject, PDO::PARAM_STR);
        $query->bindParam(":message", $message, PDO::PARAM_STR);
        $query->bindParam(":priority", $priority, PDO::PARAM_STR);
        $query->bindParam(":uid", $uid, PDO::PARAM_STR);
        // Execute the query
        if ($query->execute()) {
            echo "<script>alert('Success: Ticket created successfully.');</script>";
            echo "<script>window.location.href='support.php'</script>";
        } else {
            echo "<script>alert('Error: Failed to create ticket.');</script>";
            // Print error information for debugging
            echo "<pre>";
            print_r($query->errorInfo());
            die();
        }
    } 
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <title>Event Management System | Create Ticket </title>
    <!-- Add your CSS links here -->
    <style>
        /* Custom CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }
        .header-slider-area {
            background-color: #2f3138;
            color: #fff;
        }
        .single-blog-area {
            padding: 100px 0;
        }
        .aside-title {
            margin-top: 0;
            margin-bottom: 30px;
            color: #333;
        }
        .leave-ib {
            margin-bottom: 30px;
        }
        .info {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .submit {
            margin-left:15px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .submit:hover {
            background-color: #0056b3;
        }
        #header{
            background-color:#2f3138;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div id="home" class="header-slider-area">
    <?php include_once('includes/header.php');?>
    </div>

    <!-- Main content area -->
    <div class="single-blog-area ptb100 fix">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-11">
                    <div class="single-blog-body">
                        <div class="Leave-your-thought mt50">
                            <h3 class="aside-title uppercase">Create Ticket</h3>
                            
                            <!-- Ticket creation form -->
                            <div class="row">
                                <form name="ticket" method="post">
                                    <div class="col-md-12 col-sm-6 col-xs-12 lyt-left">
                                        <div class="input-box leave-ib">
                                            <input type="text" placeholder="Subject" class="info" name="subject" required="true">
                                            <textarea type="text" placeholder="Message" name="message" id="message" cols="30" class="info" rows="5" style="margin-bottom: 20px;"></textarea>
                                            <select class="info" name="priority" required="true">
                                                <option value="">Select Priority</option>
                                                <option value="low">Low</option>
                                                <option value="medium">Medium</option>
                                                <option value="high">High</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xs-12">
                                        <div class="input-box post-comment">
                                            <input type="submit" value="Submit" id="update" name="submit" class="submit uppercase">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <!-- Your footer content goes here -->
    </footer>

    <!-- Add your JavaScript links here -->

</body>
</html>
<?php } ?>
