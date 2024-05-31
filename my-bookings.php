<?php
session_start();
// Database connection file
include('includes/config.php');
error_reporting(0);

if (strlen($_SESSION['usrid']) == 0) {
    header('location:logout.php');
} else {
    // Get the organizer's ID from the session
    $organizerID = $_SESSION['usrid'];
    // Fetch the organizer's username from the tblusers table
    $sql = "SELECT UserName FROM tblusers WHERE Userid = :userid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':userid', $organizerID, PDO::PARAM_INT);
    $query->execute();
    $organizer = $query->fetch(PDO::FETCH_OBJ);

    if ($organizer) {
        $organizerUsername = htmlentities($organizer->UserName);

        // Fetch current date
        $currentDate = date("Y-m-d");

        // Fetch booking details based on organizer's name
        $sql = "SELECT tblvenue_booking.id, tblvenue_booking.venu_id, tblvenue_booking.venu_name, tblvenue_booking.organizer_name, tblvenue_booking.booking_date, tblvenue_booking.num_attendees, tblvenue_booking.selected_menu, tblvenue_booking.total_payment, tblvenue_booking.payment_status, tblcreate_venu.rating FROM tblvenue_booking JOIN tblcreate_venu ON tblvenue_booking.venu_id = tblcreate_venu.id WHERE tblvenue_booking.organizer_name = :organizerUsername";
        $query = $dbh->prepare($sql);
        $query->bindParam(':organizerUsername', $organizerUsername, PDO::PARAM_STR);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_OBJ);
    } else {
        echo "Organizer not found.";
        exit();
    }

    ?>

    <!doctype html>
    <html class="no-js" lang="en">
        <head>
            <title>Event Management System | My Bookings</title>
            <!-- Bootstrap v3.3.6 CSS -->
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <!-- Animate CSS -->
            <link rel="stylesheet" href="css/animate.css">
            <!-- Meanmenu CSS -->
            <link rel="stylesheet" href="css/meanmenu.min.css">
            <!-- Owl.carousel CSS -->
            <link rel="stylesheet" href="css/owl.carousel.css">
            <!-- Icofont CSS -->
            <link rel="stylesheet" href="css/icofont.css">
            <!-- Nivo CSS -->
            <link rel="stylesheet" href="css/nivo-slider.css">
            <!-- Animation Text CSS -->
            <link rel="stylesheet" href="css/animate-text.css">
            <!-- Material Iconic Fonts CSS -->
            <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
            <!-- Style CSS -->
            <link rel="stylesheet" href="style.css">
            <!-- Responsive CSS -->
            <link rel="stylesheet" href="css/responsive.css">
            <!-- Font Awesome Icons CSS -->
            <link rel="stylesheet" href="css/faicons.css">
            <!-- Color CSS -->
            <link href="css/color/skin-default.css" rel="stylesheet">
            <!-- Modernizr CSS -->
            <script src="js/vendor/modernizr-2.8.3.min.js"></script>
            <style>
            #header{
               background-color:#2f3138;
            }
            </style>
        </head>
        <body>
            <!-- Body Wrapper Start -->
            <div class="wrapper single-blog">
                <!-- Slider Header Area Start -->
                <div id="home" class="header-slider-area">
                    <!-- Header Start -->
                    <?php include_once('includes/header.php'); ?>
                    <!-- Header End -->
                </div>
                <!-- Slider Header Area End -->
                
                <!-- Breadcrumb Area Start -->
            
                <!-- Breadcrumb Area End -->

                <!-- Main Blog Area Start -->
                <div class="single-blog-area ptb100 fix">
                    <div class="container">
                        <div class="row">
                            <?php include_once('includes/myaccountbar.php'); ?>
                            <div class="col-md-8 col-sm-7">
                                <div class="single-blog-body">
                                    <div class="Leave-your-thought mt50">
                                        <h3 class="aside-title uppercase">My Bookings</h3>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-6 col-xs-12 lyt-left">
                                                <div class="input-box leave-ib">
                                                    <div class="table-responsive">
                                                        <table border="2" class="table">
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Booking Id</th>
                                                                <th>Venue Name</th>
                                                                <th>Booking Date</th>
                                                                <th>Number of Attendees</th>
                                                                <th>Selected menu</th>
                                                                <th>Amount</th>
                                                                <th>Payment Status</th>
                                                                <th>Rating</th>
                                                                <th>Submit</th>
                                                            </tr>
                                                          
                                                            <?php
                                                    $cnt = 1;
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) {
                                                    ?>
                                                    <tr>
                                                     <td><?php echo htmlentities($cnt); ?></td>
                                                        <td><?php echo htmlentities($row->id); ?></td>
                                                        <td><?php echo htmlentities($row->venu_name); ?></td>
                                                        <td><?php echo htmlentities($row->booking_date); ?></td>
                                                        <td><?php echo htmlentities($row->num_attendees); ?></td>
                                                        <td>
                                                        <?php echo htmlentities($row->selected_menu); ?>
                                                        <td> 
                                                        <?php echo htmlentities($row->total_payment); ?>
                                                        </td>
                                                            <td> <?php echo htmlentities($row->payment_status); ?>
                                                          
                                                        </td>
                                                        <td>
                                                            <?php
                                                            if ($row->rating === null && $row->booking_date < $currentDate) {
                                                                ?>
                                                                <form method="post" action="submit_rating.php">
                                                                    <input type="number" name="rating" min="1" max="5" required>
                                                                    <input type="hidden" name="venu_id" value="<?php echo htmlentities($row->venu_id); ?>">
                                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                                </form>
                                                                <?php
                                                            } else {
                                                                echo $row->rating !== null ? htmlentities($row->rating) : "N/A";
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
        <?php
        $cnt++;
    }
} else {
    ?>
    <tr>
        <td colspan="10">No bookings found.</td>
    </tr>
    <?php
}
?>

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Sidebar -->
                        </div>
                    </div>
                </div>
                <!-- Main Blog Area End -->

                <!-- Information Area Start -->
                <?php include_once('includes/footer.php'); ?>
                <!-- Footer Area End -->
            </div>
            <!-- Body Wrapper End -->

            <!--==== All JS Here ====-->
            <!-- jQuery Latest Version -->
            <script src="js/vendor/jquery-3.1.1.min.js"></script>
            <!-- Bootstrap JS -->
            <script src="js/bootstrap.min.js"></script>
            <!-- Owl.carousel JS -->
            <script src="js/owl.carousel.min.js"></script>
            <!-- Meanmenu JS -->
            <script src="js/jquery.meanmenu.js"></script>
            <!-- Nivo JS -->
            <script src="js/nivo-slider/jquery.nivo.slider.pack.js"></script>
            <script src="js/nivo-slider/nivo-active.js"></script>
            <!-- WOW JS -->
            <script src="js/wow.min.js"></script>
            <!-- Youtube Background JS -->
            <script src="js/jquery.mb.YTPlayer.min.js"></script>
            <!-- Datepicker JS -->
            <script src="js/bootstrap-datepicker.js"></script>
            <!-- Waypoint JS -->
            <script src="js/waypoints.min.js"></script>
            <!-- Onepage Nav JS -->
            <script src="js/jquery.nav.js"></script>
            <!-- Animate Text JS -->
            <script src="js/animate-text.js"></script>
            <!-- Plugins JS -->
            <script src="js/plugins.js"></script>
            <!-- Main JS -->
            <script src="js/main.js"></script>
        </body>
    </html>
<?php } ?>
