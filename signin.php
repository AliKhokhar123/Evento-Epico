<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Process for Signin
if(isset($_POST['signin'])) {
    // Getting Post Values
    $uname = $_POST['username'];
    $password = md5($_POST['password']);

    // Query for matching username and password with db details
    $sql = "SELECT Userid, IsActive, role FROM tblusers WHERE UserName = :uname and UserPassword = :password";
    
    // Preparing the query
    $query = $dbh->prepare($sql);
    
    // Binding the values
    $query->bindParam(':uname', $uname, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    
    // Execute the query
    $query->execute();
    
    // Fetch the results
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0) {
        foreach ($results as $result) {
            $status = $result->IsActive;
            $_SESSION['usrid'] = $result->Userid;
            $_SESSION['role'] = $result->role;
            $_SESSION['uname'] = $result->UserName; // Store the user's role in the session
        } 
        
        if($status == 0) {
            echo "<script>alert('Your account is Inactive. Please contact admin');</script>";
        } else {
            // Redirect to profile.php for all roles
            header("Location: profile.php");
            exit();
        }
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <title>Event Management System | User Signin</title>
    <!-- bootstrap v3.3.6 css -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- animate css -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- meanmenu css -->
    <link rel="stylesheet" href="css/meanmenu.min.css">
    <!-- owl.carousel css -->
    <link rel="stylesheet" href="css/owl.carousel.css">
    <!-- icofont css -->
    <link rel="stylesheet" href="css/icofont.css">
    <!-- Nivo css -->
    <link rel="stylesheet" href="css/nivo-slider.css">
    <!-- animaton text css -->
    <link rel="stylesheet" href="css/animate-text.css">
    <!-- Metrial iconic fonts css -->
    <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
    <!-- style css -->
  
    <!-- responsive css -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- color css -->
    <link href="css/color/skin-default.css" rel="stylesheet">
    <!-- modernizr css -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    <style>
        
        #header{
            background-color:#2f3138;
        }
        /* Center the form and set its width */
        .form-container {
            box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
            width: 300px;
            margin: 50px auto; /* Center horizontally */
            padding: 20px 0; /* Add padding to center vertically */
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top:100px;
            border:1px solid;
            margin-top:150px;
            border-radius:10px;
            background-color:#2F3138;
            color:white;
          /* Full viewport height */
        }
        .info{
            border:none;
            border-bottom:1px solid  red;
            margin-bottom:20px !important;
        }
      
        .custom-placeholder {
            position: relative;
        
        }
        
        .submit{
            border-radius: 50px;
    padding: 6px;
    border: none;
    background-color: #F82249;
    color:white;
    width: 50% !important;

    margin:0 auto;
    margin-left:25%;
    margin-top:5px;
        }
        
        .custom-placeholder input::placeholder {
            color:white;
            position: absolute;
            top: 0;
            left: 10px; /* Adjust as per your design */
            transform: translateY(-20%);
            line-height: 1; /* Adjust line height */
        /* Adjust padding to move the placeholder text up */
        }
      
.aside-title {
    text-align:center;
}
        /* Additional styles for input boxes and buttons */
        .form-container .input-box {
            width: 100%;
        }

        .form-container .input-box input {
            font-size: 15px;
            padding-left: 10px;
            padding-bottom:5px;
            width: 100%;
            margin-bottom: 10px;
        }

        .form-container .input-box .submit {
            width: 100%;
        }
        .aside-title {
            font-size:24px;
    font-weight: bold;
    color: red !important;
    text-align: center;
    margin-bottom:30px;
}
.form-container .input-box input {
    outline: none;
    background-color: transparent;}
.fpassword{
    color:red;
}
.link:hover{
    text-decoration:underline;
}
    </style>
</head>
<body>
    <!-- body-wrapper-area-start -->
    <div class="wrapper single-blog">
        <!-- slider header area start -->
        <div id="home" class="header-slider-area">
            <!-- header start -->
            <?php include_once('includes/header.php'); ?>
            <!-- header End -->
        </div>
        <!-- slider header area end -->
        
        <!-- breadcumb-area start -->
   
        <!-- breadcumb-area end -->

        <!-- main blog area start -->
        <div class="single-blog-area ptb100 fix">
            <div class="container">
                <div>
                    <div >
                        <div class="single-blog-body">
                            <div >
                                <div class="form-container">
                                 
                                   
                                        <form name="signin" method="post">
                                       
                                            <div class="col-md-12 col-sm-6 col-xs-12 lyt-left">
                                                <div class="input-box leave-ib custom-placeholder">
                                                <h3 class="aside-title uppercase style="color: white; font-wight:bold;">Sign in</h3>
                                                    <input type="text" placeholder="Username" class="info" name="username" required="true">
                                                    <input type="password" name="password" placeholder="Password" class="info" required />
                                                    <a href="forgot-password.php" style="
                                                    margin-left:35%;margin-bottm:10px" class='link'>Forgot Password</a>
                                                </div>
                                                <input type="submit" value="Signin" name="signin" class="submit uppercase">
                                            </div>
                                            
                                              
                                                  
                                             
                                           
                                            <div class="col-xs-12 mt30">
                                                <div class="input-box post-comment" style="color:white;margin-top:10px;">
                                                    Not Register yet? <a href="signup.php">Signup here</a>
                                                </div>
                                            </div>
                                        </form>
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- sidebar -->
                   
                </div>
            </div>
        </div>
        <!-- main blog area end -->

        <!-- information area start -->
        <?php include_once('includes/footer.php'); ?>
        <!-- footer area end -->
    </div>
    <!-- body-wrapper-area-end -->

    <!--==== all js here====-->
    <!-- jquery latest version -->
    <script src="js/vendor/jquery-3.1.1.min.js"></script>
    <!-- bootstrap js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- owl.carousel js -->
    <script src="js/owl.carousel.min.js"></script>
    <!-- meanmenu js -->
    <script src="js/jquery.meanmenu.js"></script>
    <!-- Nivo js -->
    <script src="js/nivo-slider/jquery.nivo.slider.pack.js"></script>
    <script src="js/nivo-slider/nivo-active.js"></script>
    <!-- wow js -->
    <script src="js/wow.min.js"></script>
    <!-- Youtube Background JS -->
    <script src="js/jquery.mb.YTPlayer.min.js"></script>
    <!-- datepicker js -->
    <script src="js/bootstrap-datepicker.js"></script>
    <!-- waypoint js -->
    <script src="js/waypoints.min.js"></script>
    <!-- onepage nav js -->
    <script src="js/jquery.nav.js"></script>
    <!-- animate text JS -->
    <script src="js/animate-text.js"></script>
    <!-- plugins js -->
    <script src="js/plugins.js"></script>
    <!-- main js -->
    <script src="js/main.js"></script>
</body>
</html>
