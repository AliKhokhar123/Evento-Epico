<?php
// Database connection file
include('includes/config.php');
error_reporting(1);

// Signup Process
if(isset($_POST['signup']))
{
    // Getting Post values
    $fname=$_POST['name'];
    $uname=$_POST['username'];
    $emailid=$_POST['email'];   
    $pnumber=$_POST['phonenumber']; 
    $gender=$_POST['gender']; 
    $password=md5($_POST['pass']);  
    $role=$_POST['role']; // Get the role value
    $status = 1;
    // Query for data insertion
    $sql="INSERT INTO tblusers(FullName,UserName,Emailid,PhoneNumber,UserGender,UserPassword,role,IsActive) VALUES(:fname,:uname,:emailid,:pnumber,:gender,:password,:role,:status)";
    // Preparing the query
    $query = $dbh->prepare($sql);
    // Binding the values
    $query->bindParam(':fname',$fname,PDO::PARAM_STR);
    $query->bindParam(':uname',$uname,PDO::PARAM_STR);
    $query->bindParam(':emailid',$emailid,PDO::PARAM_STR);
    $query->bindParam(':pnumber',$pnumber,PDO::PARAM_STR);
    $query->bindParam(':gender',$gender,PDO::PARAM_STR);
    $query->bindParam(':password',$password,PDO::PARAM_STR);
    $query->bindParam(':role',$role,PDO::PARAM_STR); // Bind the role value
    $query->bindParam(':status',$status,PDO::PARAM_STR);
    // Execute the query
    $query->execute();
    // Check if the insertion was successful
    $lastInsertId = $dbh->lastInsertId();
    if($lastInsertId)
    {
        echo "<script>alert('Success : User signup successful. Now you can sign in');</script>";
        echo "<script>window.location.href='signin.php'</script>";    
    }
    else 
    {
        echo "<script>alert('Error : Something went wrong. Please try again');</script>";    
    }
}
?>

<!doctype html>
<html class="no-js" lang="en">

<head>
    <title>Event Management System | User Signup</title>
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
    <link rel="stylesheet" href="style.css">
    <!-- responsive css -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- color css -->
    <link href="css/color/skin-default.css" rel="stylesheet">

    <!-- modernizr css -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    <script>
        function checkusernameAvailability() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "check_availability.php",
                data: 'uname=' + $("#username").val(),
                type: "POST",
                success: function (data) {
                    $("#username-availabilty-status").html(data);
                    $("#loaderIcon").hide();
                },
                error: function () { }
            });
        }
    </script>
 <style>
       
        #header{
            background-color:#2f3138;
        }
        /* Center the form and set its width */
        .form-container {
            box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
            width: 450px;
            margin: 50px auto; /* Center horizontally */
            padding: 20px 0; /* Add padding to center vertically */
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top:100px;
            border:1px solid;
         
            border-radius:10px;
            background-color:#2F3138;
            color:white;
          /* Full viewport height */
        }
        .info{
            color: white;
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
        .link:hover{
    text-decoration:underline;
}
.aside-title {
    text-align:center;
}
        /* Additional styles for input boxes and buttons */
        .form-container .input-box {

            width: 100%;
        }
::placeholder{
    color:white !important;
}
        .form-container .input-box input {
            border-bottom: 1px solid red;
            color:white;
            width: 100%;
            margin-bottom: 10px;
        }

        .form-container .input-box .submit {
            border-radius: 50px;
  
    border: none;
    background-color: #F82249;
    color: white;
    width: 50% !important;
    margin: 0 auto;
    margin-left: 25%;
    margin-top: 5px
            border: 1px solid white;
    float: left;
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
    @media screen and (max-width:600px) {
            .form-container{
                width: 330px;
            }
        }
        .info{
        background-color: #2f3138;
    }
    </style>
</head>

<body>
    <!--body-wraper-are-start-->
    <div class="wrapper single-blog">

        <!--slider header area are start-->
        <div id="home" class="header-slider-area">
            <!--header start-->
            <?php include_once('includes/header.php'); ?>
            <!-- header End-->
        </div>
        <!--slider header area are end-->

        <!-- breadcumb-area start-->

        <!-- breadcumb-area end-->

        <!-- main blog area start-->
        <div class="single-blog-area ptb30 fix">
            <div  class="container"">
                <div >
                    <div >
                        <div class="single-blog-body">
                            <div >
                                <div class="form-container"> 
                              
                            
                              
                                    <form name="signup" method="post">
                                        <div class="col-md-12 col-sm-6 col-xs-12 lyt-left">
                                            <div class="input-box leave-ib ">
                                            <h3 class="aside-title ">
                                    User Signup</h3>
                                                <input type="text" placeholder="Name" class="info" name="name" required="true">
                                                <input type="text" placeholder="Username" class="info" name="username" id="username" required="true" onBlur="checkusernameAvailability()">
                                                <span id="username-availabilty-status" style="font-size:14px; color:white"></span>
                                                <input type="email" placeholder="Email Id" class="info" name="email" required="true">
                                                <input type="tel" placeholder="Phone Number" pattern="[0-9]{10}" title="10 numeric characters only" class="info" name="phonenumber" maxlength="10" required="true">
                                                <select class="info" name="gender" required="true">
                                                    <option value="">Select Gender</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    <option value="Transgender">Transgender</option>
                                                </select>
                                                <select class="info" name="role" required="true">
                                                    <option value="">Select Role</option>
                                                    <option value="Attendee">Attendee</option>
                                                    <option value="Organizer">Organizer</option>
                                                    <option value="Venue">Venue Owner</option>
                                                </select>
                                                <input type="password" name="pass" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" placeholder="Password" title="at least one number and one uppercase and lowercase letter, and at least 6 or more characters" class="info" required />
                                                <span style="font-size:11px; color:white">Password should contain at least one number, one uppercase and lowercase letter, and be at least 6 characters long</span>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="input-box post-comment">
                                                <input type="submit" value="Submit" id="signup" name="signup" class="submit uppercase">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 mt30">
                                            <div class="input-box post-comment" style="text-align:center;color: white;">
                                                Already Registered? <a href="signin.php" class='link'>Sign in here</a>
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
        <!-- main blog area end-->
      
        <!-- information area are start-->
        <?php include_once('includes/footer.php'); ?>
        <!--footer area are end-->
    </div>
    <!--body-wraper-are-end-->

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
