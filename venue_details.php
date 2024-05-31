<?php
session_start();
include('includes/config.php');

// Check if the user is logged in and is an organizer
if (!isset($_SESSION['usrid'])) {
    // Redirect to the sign-in page if not logged in
    header("Location: signin.php");
    exit();
} elseif (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Organizer') {
    // Alert and redirect if the user is not an organizer
    echo "<script>alert('This page is only accessible by organizers.'); window.location.href = 'index.php';</script>";
    exit();
}
$organizerID = $_SESSION['usrid'];
// Fetch the organizer's username from the tblusers table
$sql = "SELECT UserName FROM tblusers WHERE Userid = :userid";
$query = $dbh->prepare($sql);
$query->bindParam(':userid', $organizerID, PDO::PARAM_INT);
$query->execute();
$organizer = $query->fetch(PDO::FETCH_OBJ);

if ($organizer) {
    $organizerUsername = htmlentities($organizer->UserName);
} else {
    echo "Organizer not found.";
    exit();
}

// Check if venue ID is provided in the URL
if (isset($_GET['id'])) {
    $venue_id = $_GET['id'];

    // Fetch venue details based on the provided ID
    $sql = "SELECT venuname, perheadpriceformenu1,perheadpriceformenu2,max_capacity,menu1,menu2,description, image1, image2,category,booking_fees, image3,city, per_head_price, start_date, end_date,other_services,restrictions,location FROM tblcreate_venu WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $venue_id, PDO::PARAM_INT);
    $query->execute();
    $venue = $query->fetch(PDO::FETCH_OBJ);

    if ($venue) {
        // Venue details
        $category=htmlentities($venue->category);
        $booking_fees=htmlentities($venue->booking_fees);
        $menu1=htmlentities($venue->menu1);
        $menu2=htmlentities($venue->menu2);
        $city=htmlentities($venue->city);
        $restrictions=htmlentities($venue->restrictions);
        $other_services=htmlentities($venue->other_services);
        $venuname = htmlentities($venue->venuname);
        $description = htmlentities($venue->description);
        $image1 = "venu/upload/" . $venue->image1;
        $image2 = "venu/upload/" . $venue->image2;
        $image3 = "venu/upload/" . $venue->image3;
        $per_head_price = htmlentities($venue->per_head_price);
        $start_date = htmlentities($venue->start_date);
        $end_date = htmlentities($venue->end_date);
        $location=htmlentities($venue->location);
        $perheadpriceformenu1=htmlentities($venue->perheadpriceformenu1);
        $perheadpriceformenu2=htmlentities($venue->perheadpriceformenu2);
        $max_capacity=htmlentities($venue->  max_capacity);
      
    } else {
        echo "Venue not found.";
        exit();
    }

    // Fetch booked dates for this venue
    $bookedDatesSql = "SELECT booking_date FROM tblvenue_booking WHERE venu_id = :venue_id";
    $bookedDatesQuery = $dbh->prepare($bookedDatesSql);
    $bookedDatesQuery->bindParam(':venue_id', $venue_id, PDO::PARAM_INT);
    $bookedDatesQuery->execute();
    $bookedDates = $bookedDatesQuery->fetchAll(PDO::FETCH_COLUMN, 0);
} else {
    echo "Venue ID not provided.";
    exit();
}

// Handle form submission
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $event_name = $_POST['event_name'];
    $organizerEmail = $_POST['organizerEmail'];
    $selectedMenu = $_POST['menuselected']; // Menu selection
    $bookingDate = $_POST['bookingDate'];
    $numAttendees = $_POST['numAttendees'];
    $eventDetails = $_POST['eventDetails'];
    $ticket_price = $_POST['tprice'];
   

    // Calculate total price based on the selected menu and number of attendees
    if ($category === 'Musical Party' || $category === 'Conference') {
        $total_payment = $numAttendees * $booking_fees;
    } else {
        $total_payment = ($selectedMenu === 'menu1') ? $perheadpriceformenu1 * $numAttendees : $perheadpriceformenu2 * $numAttendees;
    }
    $upfront= $total_payment/2;
    // Insert booking data into the database, including the total price
    $insertSql = "INSERT INTO tblvenue_booking (venu_id, venu_name, organizer_name, event_name, organizer_email, selected_menu, booking_date, num_attendees, event_details, total_payment,upfront,ticket_price) 
    VALUES (:venueID, :venueName, :organizerName, :event_name, :organizerEmail, :selectedMenu, :bookingDate, :numAttendees, :eventDetails, :total_payment,:upfront,:ticket_price)";
    $insertQuery = $dbh->prepare($insertSql);
    $insertQuery->bindParam(':venueID', $venue_id, PDO::PARAM_INT);
    $insertQuery->bindParam(':venueName', $venuname, PDO::PARAM_STR);
    $insertQuery->bindParam(':event_name', $event_name, PDO::PARAM_STR);
    $insertQuery->bindParam(':organizerName', $organizerUsername, PDO::PARAM_STR);
    $insertQuery->bindParam(':organizerEmail', $organizerEmail, PDO::PARAM_STR);
    $insertQuery->bindParam(':selectedMenu', $selectedMenu, PDO::PARAM_STR);
    $insertQuery->bindParam(':bookingDate', $bookingDate, PDO::PARAM_STR);
    $insertQuery->bindParam(':numAttendees', $numAttendees, PDO::PARAM_INT);
    $insertQuery->bindParam(':eventDetails', $eventDetails, PDO::PARAM_STR);
    $insertQuery->bindParam(':total_payment', $total_payment, PDO::PARAM_INT); 
    $insertQuery->bindParam(':upfront', $upfront, PDO::PARAM_INT);
    $insertQuery->bindParam(':ticket_price', $ticket_price, PDO::PARAM_INT);
    // Bind total price parameter
  
    if ($insertQuery->execute()) {
        echo "Booking successful!";
    } else {
        echo "Error occurred while booking.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venue Details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.10.1/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/index.global.min.js'></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
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
   
    <!-- color css -->
    <link href="css/color/skin-default.css" rel="stylesheet">
    <href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- modernizr css -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    <link rel="stylesheet" href="css/venu_detail.css">
    <style>
        .hide{
            display:none;
        }
        @media (max-width: 600px) {
    .custom-column {
        flex: 100%;
        margin-right: 0;
    }
    .left-side {
        display: none;
    }
    .tcenter{
        text-align:center;
    }
    .container2{
        padding: 10px 20px;
    }
    .green-treat-box,.btn-warning-full{
        width: 100% !important;
    }
    .nav-tabs .nav-item{
        padding:0px;
    }
    .csection{
        padding-left:0px !important;
        display:block !important;
    }
    .bookingcalender {
    width: 100% !important;
}
.csectiondetail {
    margin-top:10px;
    margin-left: 0px;
}
    }
        </style>
</head>
<body>
<div id="home" class="header-slider-area">
    <!--header start-->
    <?php include_once "includes/header.php"; ?>
    <!-- header End-->
</div>
<div class="container2 ">
    <div class="left-side">
    <div class="manual-container">
        <h4 class="text-center mb-5">Do you know how to create an event at EventoEpico?</h2>
        <div class="row">
            <div class="col-md-4 step">
                <div class="step-number">1</div>
                <i class="fas fa-search step-icon"></i>
                <div class="step-title">Select Venue</div>
                <div class="step-desc">Choose a venue that matches your specific category.</div>
            </div>
            <div class="col-md-4 step">
                <div class="step-number">2</div>
                <i class="fas fa-calendar-alt step-icon"></i>
                <div class="step-title">Select Date</div>
                <div class="step-desc">Pick an available date from the calendar.</div>
            </div>
            <div class="col-md-4 step">
                <div class="step-number">3</div>
                <i class="fas fa-utensils step-icon"></i>
                <div class="step-title">Select Menu</div>
                <div class="step-desc">Choose the menu for your event.</div>
            </div>
        </div>
        <!-- <div class="step-line"></div> -->
        <div class="row">
            <div class="col-md-4 step">
                <div class="step-number">4</div>
                <i class="fas fa-edit step-icon"></i>
                <div class="step-title">Write Description</div>
                <div class="step-desc">Provide any additional details for your event.</div>
            </div>
            <div class="col-md-4 step">
                <div class="step-number">5</div>
                <i class="fas fa-check-circle step-icon"></i>
                <div class="step-title">Click Book</div>
                <div class="step-desc">Finalize your booking by clicking the book button.</div>
            </div>
            <div class="col-md-4 step">
                <div class="step-number">6</div>
                <i class="fas fa-credit-card step-icon"></i>
                <div class="step-title">Make Payment</div>
                <div class="step-desc">Complete the payment in your dashboard section.</div>
            </div>
        </div>
    </div>
    </div>
    <div class="center-side">
    <h3  class='venunameheading tcenter'><?php echo $venuname; ?></h3>
    <h5  class='venunamesubheading tcenter'><i class="fa fa-map-marker"  ></i>

        <?php echo $location; ?></h3>
        <div class="slider-container">
            <div class="slider">
                <img src="<?php echo $image1; ?>" alt="Venue Image 1">
                <img src="<?php echo $image2; ?>" alt="Venue Image 2">
                <img src="<?php echo $image3; ?>" alt="Venue Image 3">
            </div>
            <button class="prev">&#10094;</button>
            <button class="next">&#10095;</button>
        </div>
        <h2 class='desc'>Description</h2>
        <p ><?php echo $description; ?></p>
        <!-- Booking form -->
       
    </div>
    <div class="right-side">
        <h2>Highlights<h2>
        <div class="green-treat-box" style="padding-top:5px!important; border-radius: 10px; padding-bottom: 24px !important;width: 90%;">
	            
					
<!-- 		
		<p class="booking-item-header-price"><small style="text-align:center; color:#fff; font-size:18px">Price per head</small><br><strong style="font-size:20px;color:#fed100;"> -->
  
        <?php if($category == 'Conference' || $category == 'Musical Party'): ?>
            <p style='color:white'>
                                        Booking Fees: <?php echo htmlentities($booking_fees); ?> 
                                    <?php else: ?>
                                        Per head price: <?php echo htmlentities($perheadpriceformenu1); ?> - <?php echo htmlentities($perheadpriceformenu2); ?> Rs
                                    <?php endif; ?> Rs</strong>
    </p>
				<p class="booking-item-header-price" style='color:white'><small style="text-align:center;color:white">
                Maximum capacity: 
                <?php echo $max_capacity; ?> persons</small></p>	</div>
                <a href="#booking_now" class="btn btn-warning-full" style="    background-color: #fed100 !important; color: #000 !important; padding: 10px 98px; font-size: 18px; margin-top: 5px; border-radius: 10px; border-color: #000">Book Now</a>
        <h3>Other Services</h3>
        <p>  <?php echo $other_services ; ?></p>
        <h3>Restrictions</h3>
        <p>  <?php echo $restrictions ; ?></p>
        <h3 style="text-decoration:underline"><a href='#'>Check our payment refund policy<a/></h4>
        <!-- Add more content as needed -->
        <h3>Why choose us?</h3>
        <li>  Top venue of  <?php echo $city ; ?></li>
        <li> Multi Purpose</li>
        <li>  Great Location</li>

    </div>
</div>

<div class="tab-container" id='booking_now'>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link active " data-tab="calender" href="#calender" role="tab" aria-controls="calender" aria-selected="true">Select Available Date</a>
    </li>
    <li class="nav-item">
        <a class="nav-link disabled" data-tab="menu" href="#menu" role="tab" aria-controls="menu" aria-selected="false">Select Menu</a>
    </li>
    <li class="nav-item">
        <a class="nav-link disabled" data-tab="orderDetails" href="#orderDetails" role="tab" aria-controls="orderDetails" aria-selected="false">Other</a>
    </li>
</ul>

    <div class="tab-content">
        <div class="tab-pane active" id="calender">
        <div class='csection'>
            <div class='bookingcalender'>
                <div id="calendar"></div>
    </div>
                <div class='csectiondetail'>
                <h3>Refund and Cancellation</h3>
                <h4>   Payments</h4>
             <ul>
                <li>Advance payment is 50%</li>
                <li>Advance must be paid within 15 days of the booking.</li>
                <li>Full payment must be paid 7 days before the event date.</li>
    </ul>
    <h4>   Refunds</h4>
             <ul>
                <li>Refund is not available</li>
                <li>Refund for advance payment can't be claimed</li>
                <li>Refund for your full payment can't be claimed.</li>
    </ul>
    
    <button id="openMenuTab" disabled>Proceed to Select Menu</button>
    </div>
            </div>
        </div>
        <div class="tab-pane" id="menu">
        <div class="container mt-4">
    <h2 class="text-center">Select Your Menu</h2>
    <div class="row">
       
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">Menu 1</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><?php echo $menu1; ?></li>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <input type="radio" name="menu" value="menu1" id="menu1">
                        <label for="menu1">Select Menu 1</label>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title">Menu 2</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><?php echo $menu2; ?></li>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <input type="radio" name="menu" value="menu2" id="menu2">
                        <label for="menu2">Select Menu 2</label>
                    </div>
                </div>
            </div>
   
    </div>
    <div class="row mt-4">
        <div class="col-md-12 text-center">
            <label for="attendees">Number of Attendees:</label>
            <input type="number" id="attendees" name="attendees" class="form-control w-25 d-inline-block" placeholder="Enter number of attendees">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12 text-center">
            <button id="proceedToOthersTab" disabled>Proceed to Others</button>
        </div>
    </div>
</div>


</div>

<<div class="tab-pane" id="orderDetails">
    <form id="bookingForm" method="POST" >
        <div class="custom-container">
            <h2 class="custom-heading">Event Details</h2>
            <form id="bookingForm" action="" method="POST">
            <div class="custom-row">
                <div class="custom-column">
                    <label for="eventName">Event Name:</label>
                    <input type="text" id="eventName" name="event_name" class="custom-input" placeholder="Enter the name of your event">
                    <label for="oemail">Organzier email:</label>
                    <input type="email" id="oemail" name="organizerEmail" class="custom-input" placeholder="Enter the email">
                    <label for="tprice">Enter Ticket Price (Note: Leave it empty if it is a wedding/walima or any kind of party):</label>
                    <input type="number" id="tprice" name="tprice" class="custom-input" placeholder="Enter ticket price">
                </div>
                <div class="custom-column">
                <label for="eventDetails">Event Details:</label>
                <textarea id="eventDetails" name="eventDetails" class="custom-textarea" rows="5" placeholder="Write details about your event"></textarea>
               
          <input type="text" id="bookingDate" name="bookingDate" readonly required class='hide'>
         
          <input type="text" id="menuselected" name="menuselected" readonly required class='hide'>
     
          <input type="text" id="attendes" name="numAttendees" readonly required class='hide'>>
          <button type="submit">Book this Event</button>
      </form>
</div>    
        
    
        
            </div>
        </div>
    
    </form>
</div>



</div>

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
    // Variables to store the form data
    let selectedDate = null;
    let selectedMenu = null;
    let numberOfAttendees = 0;
    let eventName = '';
    let eventDetails = '';

    // Select DOM elements
    const calendar = document.getElementById("calendar");
    const menuRadios = document.getElementsByName("menu");

    const attendeesInput = document.getElementById("attendees");
    const eventNameInput = document.getElementById("eventName");
    const eventDetailsInput = document.getElementById("eventDetails");
    const openMenuTabButton = document.getElementById("openMenuTab");
    const proceedToOthersTabButton = document.getElementById("proceedToOthersTab");

    // Mock calendar selection (you can replace this with actual calendar library events)
    calendar.addEventListener("click", function(event) {
        selectedDate = "2024-05-27"; // example date
        openMenuTabButton.disabled = false; // Enable the button when a date is selected
    });

    // Store menu selection
    menuRadios.forEach(radio => {
        radio.addEventListener("change", function(event) {
            selectedMenu = event.target.value;
            document.getElementById('menuselected').value = selectedMenu;
            proceedToOthersTabButton.disabled = false; // Enable the button when a menu is selected
        });
    });

    // Store number of attendees
    attendeesInput.addEventListener("input", function(event) {
        numberOfAttendees = event.target.value;
        document.getElementById('attendes').value = numberOfAttendees;
    });

    // Store event name
    eventNameInput.addEventListener("input", function(event) {
        eventName = event.target.value;
    });

    // Store event details
    eventDetailsInput.addEventListener("input", function(event) {
        eventDetails = event.target.value;
    });

    // Log the values when needed (for demonstration, logging on form submission)
    document.querySelector('input[type="submit"]').addEventListener("click", function(event) {
        event.preventDefault(); // Prevent the form from submitting
        console.log("Selected Date: ", selectedDate);
        console.log("Selected Menu: ", selectedMenu);
        console.log("Number of Attendees: ", numberOfAttendees);
        console.log("Event Name: ", eventName);
        console.log("Event Details: ", eventDetails);
    });
});
    document.addEventListener('DOMContentLoaded', function() {

        
    // Function to check if both menu and number of attendees are selected
    function checkConditions() {
        var menuSelected = document.querySelector('input[name="menu"]:checked');
        var attendeesInput = document.getElementById('attendees');
        var proceedButton = document.getElementById('proceedToOthersTab');

        if (menuSelected && attendeesInput.value !== '') {
            proceedButton.disabled = false;
        } else {
            proceedButton.disabled = true;
        }
    }

    // Event listeners for menu selection and number of attendees input
    var menuInputs = document.querySelectorAll('input[name="menu"]');
    menuInputs.forEach(function(input) {
        input.addEventListener('change', checkConditions);
    });

    var attendeesInput = document.getElementById('attendees');
    attendeesInput.addEventListener('input', checkConditions);

    // Event listener for the button to proceed to the "Others" tab
    var proceedButton = document.getElementById('proceedToOthersTab');
    proceedButton.addEventListener('click', function() {
        // Enable the "Other" tab and disable the "Select Menu" tab
        // var otherTabLink = document.querySelector('a[data-tab="orderDetails"]');
        // otherTabLink.classList.remove('disabled');
        // otherTabLink.click();
        
        const menuTab = document.querySelector('[data-tab="orderDetails"]');
            const menuPane = document.getElementById('orderDetails');
            menuTab.classList.remove('disabled');
            menuTab.classList.add('active');
            menuPane.classList.add('active');
            menuTab.click();  
            const calendarTab = document.querySelector('[data-tab="menu"]');
            const calendarPane = document.getElementById('menu');
            calendarTab.classList.remove('active');
            calendarPane.classList.remove('active');
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var bookedDates = <?php echo json_encode($bookedDates); ?>;
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        selectOverlap: function(event) {
            return !bookedDates.includes(event.startStr);
        }
    });
    calendar.render();

    var startDate = moment('<?php echo $start_date; ?>');
    var endDate = moment('<?php echo $end_date; ?>');
    var currentDate = startDate.clone();
    var highlightedDates = [];
    while (currentDate.isSameOrBefore(endDate)) {
        if (!bookedDates.includes(currentDate.format('YYYY-MM-DD'))) {
            calendar.addEvent({
                start: currentDate.format('YYYY-MM-DD'),
                end: currentDate.format('YYYY-MM-DD'),
                rendering: 'background',
                classNames: 'highlighted-date'
            });
            highlightedDates.push(currentDate.format('YYYY-MM-DD'));
        }
        currentDate.add(1, 'days');
    }

    var selectedDate = '';
    var openMenuTabButton = document.getElementById('openMenuTab');

    calendar.on('dateClick', function(info) {
        var date = info.dateStr;
        if (highlightedDates.includes(date)) {
            selectedDate = date;
            document.getElementById('bookingDate').value = date;
            openMenuTabButton.disabled = false;
        } else {
            alert('You can only book the highlighted dates.');
        }
    });

    // Slider functionality
    var slider = document.querySelector('.slider');
    var images = document.querySelectorAll('.slider img');
    var prev = document.querySelector('.prev');
    var next = document.querySelector('.next');
    var currentIndex = 0;

    function showImage(index) {
        slider.style.transform = 'translateX(' + (-index * 100) + '%)';
    }

    prev.addEventListener('click', function() {
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
        showImage(currentIndex);
    });

    next.addEventListener('click', function() {
        currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
        showImage(currentIndex);
    });

    // Handle tab switching and enabling
    openMenuTabButton.addEventListener('click', function() {
        if (selectedDate) {
            // Enable the "Select Menu" tab
            const menuTab = document.querySelector('[data-tab="menu"]');
            const menuPane = document.getElementById('menu');
            menuTab.classList.remove('disabled');
            menuTab.classList.add('active');
            menuPane.classList.add('active');
            menuTab.click();  // Programmatically open the "Select Menu" tab

            // Disable the "Select Available Date" tab
            const calendarTab = document.querySelector('[data-tab="calender"]');
            const calendarPane = document.getElementById('calender');
            calendarTab.classList.remove('active');
            calendarPane.classList.remove('active');
        } else {
            alert('Please select a date first.');
        }
    });

    // Prevent manual tab switching by clicking
    const tabs = document.querySelectorAll('.nav-link');
    tabs.forEach(tab => {
        tab.addEventListener('click', function(event) {
            if (tab.classList.contains('disabled')) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });
});


</script>
</body>
</html>
