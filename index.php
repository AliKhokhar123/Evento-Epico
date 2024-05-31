<?php
session_start();
// Include database connection file
include('includes/config.php');
error_reporting(0);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize the email input
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';

    // Check if the email is valid
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Prepare and execute SQL statement to insert the email into the subscribers table
        $stmt = $dbh->prepare("INSERT INTO subscribers (email) VALUES (:email)");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Trigger the subscription success modal
            echo '<script>$("#subscriptionModal").modal("show");</script>';
        } else {
            echo "<p>Error: Unable to insert subscriber email.</p>";
        }
        
    } else {
        echo "<p>Please enter a valid email address</p>";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Prepare SQL statement
    $sql = "INSERT INTO user_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    // Execute SQL statement
    if ($stmt->execute()) {
        echo "Message sent successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
// Check if the form is submitted

?>

<!doctype html>
<html class="no-js" lang="en">
    <head>
        <title>Event Management System | Home Page </title>
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
        <!-- <link rel="stylesheet" href="style.css"> -->
   
        <!-- responsive css -->
   
        <!-- color css -->
        <link href="css/color/skin-default.css" rel="stylesheet">
      
        <!-- modernizr css -->
        <script src="js/vendor/modernizr-2.8.3.min.js"></script>
        <link href="theme/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
        <link href="theme/css/style.css" rel="stylesheet">
        <style>
   
            #faq-list .expanded {
    background-color: red;
}

              .p {
  height: 200px;
  width: 500px;
  margin: 10px auto;
  position: relative;
}

.img {
  height: 100px;
  position: absolute;
  left: 0;
  offset-path: path('m 0 50 q 50-30 100-30 t 100 30 100 0 100-30 100 30');
  box-shadow: 1px 1px 3px #0008;
  transition: transform .4s ease-out, offset-path .4s cubic-bezier(.77,-1.17,.75,.84),box-shadow .3s, z-index .3s;
  z-index: 0;
}

.img:hover {
  transform: scale(3);
  /* on hover, the path gets a bit shorter & flattened & shifted to left/bottom a bit for nicer movement */
  offset-path: path('m 5 65 q 45-0 90-0 t 90 0 90 0 90-0 90 0');
  box-shadow: 3px 4px 10px #0006;
  /* ensures that image gets on top of stack at the start of "popping" animation
     and gets back at the end of getting back. With smaller value, 2 different transitions would be needed */
  z-index: 999;
}

/* 3 images */
.img:nth-last-child(3):first-child {
  offset-distance: 17%;
}
.img:nth-last-child(2):nth-child(2) {
  offset-distance: 49%;
}
.img:last-child:nth-child(3) {
  offset-distance: 81%;
}

/* 4 images */
.img:nth-last-child(4):first-child {
  offset-distance: 10%;
}
.img:nth-last-child(3):nth-child(2) {
  offset-distance: 35%;
}
.img:nth-last-child(2):nth-child(3) {
  offset-distance: 65%;
}
.img:last-child:nth-child(4) {
  offset-distance: 90%;
}

/* 5 images */
.img:nth-last-child(5):first-child {
  offset-distance: 0%;
}
.img:nth-last-child(4):nth-child(2) {
  offset-distance: 25%;
}
.img:nth-last-child(3):nth-child(3) {
  offset-distance: 51%;
}
.img:nth-last-child(2):nth-child(4) {
  offset-distance: 75%;
}
.img:last-child:nth-child(5) {
  offset-distance: 100%;
}
            .popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    z-index: 1000;
}

.popup-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80%;
    height: 80%;
}

.popup-content iframe {
    width: 100%;
    height: 100%;
}

.close {
    position: absolute;
    top: 10px;
    right: 20px;
    color: white;
    font-size: 30px;
    font-weight: bold;
    cursor: pointer;
}
   .venue-gallery-container {
            padding: 30px;
        }
        .venue-gallery {
            position: relative;
            overflow: hidden;
        }
        .venue-gallery img {
            width: 100%;
            height: auto;
            display: block;
        }
        .venue-name-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .venue-gallery:hover .venue-name-overlay {
            opacity: 1;
        }
  
@media only screen and (min-width:800px) and (max-width:2000px){
    .speaker img,.venue-gallery img {
    max-width: 100%;
    height: 240px !important;
}
.venue-gallery img {
    max-width: 100%;
    height: 200px !important;
}
}


        </style>
    </head>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
    const playButton = document.getElementById('playButton');
    const videoPopup = document.getElementById('videoPopup');
    const videoFrame = document.getElementById('videoFrame');
    const closeButton = document.querySelector('.close');

    playButton.addEventListener('click', function(e) {
        e.preventDefault();
        videoFrame.src = "https://www.youtube.com/embed/jDDaplaOz7Q?autoplay=1";
        videoPopup.style.display = 'block';
    });

    closeButton.addEventListener('click', function() {
        videoPopup.style.display = 'none';
        videoFrame.src = '';
    });

    window.addEventListener('click', function(e) {
        if (e.target == videoPopup) {
            videoPopup.style.display = 'none';
            videoFrame.src = '';
        }
    });
});

        </script>

    <body>
        
        <!--body-wraper-are-start-->
         <div class="wrapper home-02">
         
            <!--slider header area are start-->
         <?php include_once('includes/header.php');?>
                <!-- header End-->
                <!--slider area are start-->
                <section id="intro">
    <div class="intro-container wow fadeIn">
      <h1 class="mb-4 pb-0">Seamless<br><span>Event</span> Planning</h1>
      <p class="mb-4 pb-0">Islamabad, Rawalpindi, Lahore, Karachi</p>
      <a href="#" class="venobox play-btn mb-4" id="playButton"></a>

<div id="videoPopup" class="popup">
    <div class="popup-content">
        <span class="close">&times;</span>
        <iframe id="videoFrame" src="https://youtu.be/XCBjnWDdAvQ?si=y2Z9barcF1USownO" frameborder="0" allowfullscreen></iframe>
    </div>
</div>
      <a href="all-events.php" class="about-btn scrollto">Upcomming events</a>
    </div>
  </section>
  <section id="about">
      <div class="container">
        <div class="row">
          <div class="col-lg-6">
            <h2>About EventoEpico</h2>
            <p>Welcome to EventoEpico, your ultimate destination for seamless event management in Pakistan. We bridge the gap between venue owners, organizers, and attendees, creating a cohesive and efficient event planning experience. Venue owners can easily register and showcase their spaces, while organizers find and book the perfect locations for their events. Attendees enjoy a diverse array of events, all in one place. Join us at EventoEpico and make your events truly epic!</p>
          </div>
          <div class="col-lg-3 mg">
            <h3>Where</h3>
            <p>We are organizing events in Islamabad, Rawalpindi, Lahore and karachi</p>
          </div>
         
        </div>
      </div>
    </section>



    <section id="speakers" class="wow fadeInUp">
    <div class="container">
        <div class="section-header">
            <h2>Upcoming Events</h2>
            <p>Events for the coming week</p>
        </div>
        <div class="row">
            <?php
            // Fetching Upcoming events for the coming week
            $currentDate = date('Y-m-d');
            $nextWeekDate = date('Y-m-d', strtotime('+1 week'));

            $sql = "SELECT 
                        vb.id,
                        vb.venu_id,
                        vb.venu_name,
                        vb.booking_date,
                        v.image1,
                        v.location
                    FROM 
                        tblvenue_booking vb
                    JOIN 
                        tblcreate_venu v ON vb.venu_id = v.id
                    WHERE 
                        vb.booking_date BETWEEN :currentDate AND :nextWeekDate
                    ORDER BY 
                        vb.booking_date ASC";
            $query = $dbh->prepare($sql);
            $query->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
            $query->bindParam(':nextWeekDate', $nextWeekDate, PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);

            if ($query->rowCount() > 0) {
                foreach ($results as $row) {
            ?>
                <div class="col-lg-4 col-md-6">
                    <div class="speaker">
                        <img src="venu/upload/<?php echo htmlentities($row->image1); ?>" alt="<?php echo htmlentities($row->venu_name); ?>" class="img-fluid">
                        <div class="details">
                            <h3><a href="event-details.php?venueid=<?php echo htmlentities($row->venu_id); ?>"><?php echo htmlentities($row->venu_name); ?></a></h3>
                            <p><?php echo htmlentities($row->booking_date); ?></p>
                            <p>Location: <?php echo htmlentities($row->location); ?></p>
                            <div class="social">
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-google-plus"></i></a>
                                <a href="#"><i class="fa fa-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                }
            } else {
                echo "No upcoming events.";
            }
            ?>
        </div>
    </div>
</section>

<section id="venue" class="wow fadeInUp">

<div class="container-fluid">

  <div class="section-header">
    <h2>Availble Venues</h2>
    <!-- <p>Event venue location info and gallery</p> -->
  </div>

</div>

<div class="container-fluid venue-gallery-container">
    <div class="row no-gutters">
        <?php
        // Fetching all venue images and names
        $sql = "SELECT venuname, image1 FROM tblcreate_venu";
        $query = $dbh->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            foreach ($results as $row) {
                if (!empty($row->image1)) {
        ?>
                <div class="col-lg-3 col-md-4">
                    <div class="venue-gallery">
                        <a href="venu/upload/<?php echo htmlentities($row->image1); ?>" class="venobox" data-gall="venue-gallery">
                            <img src="venu/upload/<?php echo htmlentities($row->image1); ?>" alt="<?php echo htmlentities($row->venuname); ?>" class="img-fluid">
                            <div class="venue-name-overlay">
                                <h3 style='color:white;'><?php echo htmlentities($row->venuname); ?></h3>
                            </div>
                        </a>
                    </div>
                </div>
        <?php
                }
            }
        } else {
            echo "No venue images available.";
        }
        ?>
    </div>
</div>


</section>

<section id="gallery" class="wow fadeInUp">

      <div class="container">
        <div class="section-header">
          <h2>Gallery</h2>
          <p>Check our gallery from the recent events</p>
        </div>
      </div>

      <p class="p">
  <img src="theme/img/gallery/1.jpg" class="img">
  <img src="theme/img/gallery/2.jpg" class="img">
  <img src="theme/img/gallery/3.jpg" class="img">
  <img src="theme/img/gallery/4.jpg" class="img">
  <img src="theme/img/gallery/5.jpg" class="img">
</p>

<p class="p">
  <img src="theme/img/gallery/1.jpg" class="img">
  <img src="theme/img/gallery/1.jpg" class="img">
  <img src="theme/img/gallery/1.jpg" class="img" >
</p>

<p class="p">
  <img src="theme/img/gallery/1.jpg" class="img">
  <img src="theme/img/gallery/1.jpg" class="img">
  <img src="theme/img/gallery/1.jpg" class="img">
  <img src="theme/img/gallery/1.jpg" class="img">
</p>
    </section>
                        
        
    <section id="faq" class="wow fadeInUp">
    <div class="container">
    <div class="section-header">
        <h2>F.A.Q</h2>
    </div>
    <div class="row justify-content-center">
    <div class="col-lg-9">
        <ul id="faq-list">
            <li>
                <a class="collapsed" onclick="toggleFAQ('faq1')">How do I register my venue on EventoEpico? <i class="fa fa-plus-circle"></i></a>
                <div id="faq1" class="collapse">
                    <p>To register your venue, simply create an account, fill in your venue details, and pay a registration fee of $5 per day.</p>
                </div>
            </li>
            <li>
                <a class="collapsed" onclick="toggleFAQ('faq2')">What types of events can be organized through EventoEpico? <i class="fa fa-plus-circle"></i></a>
                <div id="faq2" class="collapse">
                    <p>EventoEpico caters to a wide range of events, including weddings, corporate meetings, parties, conferences, and more.</p>
                </div>
            </li>
            <li>
                <a class="collapsed" onclick="toggleFAQ('faq3')">How can organizers book a venue? <i class="fa fa-plus-circle"></i></a>
                <div id="faq3" class="collapse">
                    <p>Organizers can browse listed venues, check availability, and book directly through our platform. Once the booking is confirmed, they can start planning their event.</p>
                </div>
            </li>
            <li>
                <a class="collapsed" onclick="toggleFAQ('faq4')">Is there a fee for attendees to join events? <i class="fa fa-plus-circle"></i></a>
                <div id="faq4" class="collapse">
                    <p>Attendee fees vary depending on the event. Some events may be free, while others may require a ticket purchase, which can be done through our site.</p>
                </div>
            </li>
            <li>
                <a class="collapsed" onclick="toggleFAQ('faq5')">Can I cancel or reschedule my event booking? <i class="fa fa-plus-circle"></i></a>
                <div id="faq5" class="collapse">
                    <p>Yes, you can cancel or reschedule your booking based on the venue's cancellation policy. Please refer to the specific venue's terms and conditions for more details.</p>
                </div>
            </li>
            <!-- Add more questions here -->
        </ul>
    </div>
</div>
</div>
</section>

<section id="subscribe">
<div class="container wow fadeInUp">
    <div class="section-header">
        <h2>Newsletter</h2>
        <p>Subscribe to our newsletter to get daily updates</p>
    </div>

 

    <form method="POST" >
        <div class="form-row justify-content-center">
            <div class="col-auto">
                <input type="email" class="form-control" name="email" placeholder="Enter your Email" required>
            </div>
            <div class="col-auto">
                <button type="submit">Subscribe</button>
            </div>
        </div>
    </form>
</div>

    </section>

    <section id="contact" class="section-bg wow fadeInUp">

    <div class="container">
  <div class="section-header">
    <h2>Contact Us</h2>
    <p>Feel free to reach us with any query or information.</p>
  </div>
  <div class="row contact-info">
    <!-- Contact information goes here -->
  </div>
  <div class="form">
    <div id="sendmessage">Your message has been sent. Thank you!</div>
    <div id="errormessage"></div>
    <form  method="POST" role="form" class="contactForm" action='submit_message.php'>
      <div class="form-row">
        <div class="form-group col-md-6">
          <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" data-rule="minlen:4" data-msg="Please enter at least 4 characters." />
          <div class="validation"></div>
        </div>
        <div class="form-group col-md-6">
          <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email." />
          <div class="validation"></div>
        </div>
      </div>
      <div class="form-group">
        <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" data-rule="minlen:4" data-msg="Please enter at least 8 characters of subject." />
        <div class="validation"></div>
      </div>
      <div class="form-group">
        <textarea class="form-control" name="message" rows="5"  data-msg="Please write something for us." placeholder="Message"></textarea>
        <div class="validation"></div>
      </div>
      <div class="text-center"><button type="submit">Send Message</button></div>
    </form>
  </div>
</div>

</section>
       

            <!--information area are start-->
           <?php include_once('includes/footer.php');?>
            <!--footer area are start-->
         </div>   
        <!--body-wraper-are-end-->
        
        <!--==== all js here====-->
        <!-- jquery latest version -->
        <script>
function toggleFAQ(id) {
    var faq = document.getElementById(id);
    var question = document.querySelector('#' + id + ' a');
    if (faq.style.display === 'block') {
        faq.style.display = 'none';
        question.querySelector('i').className = 'fa fa-plus-circle';
        question.classList.remove('expanded');
    } else {
        faq.style.display = 'block';
        question.querySelector('i').className = 'fa fa-minus-circle';
        question.classList.add('expanded');
    }
}
</script>




<!--==== all js here====-->
<!-- jquery latest version -->
<script src="js/vendor/jquery-3.1.1.min.js"></script>
<!-- bootstrap js -->
<script src="js/bootstrap.min.js"></script>
<!-- owl.carousel js -->

<!-- meanmenu js -->
<script src="js/jquery.meanmenu.js"></script>
<!-- Nivo js -->

<script src="js/nivo-slider/nivo-active.js"></script>
<!-- wow js -->
<script src="js/wow.min.js"></script>
<!-- Vedio js -->
<script src="js/video.js"></script>
<!-- Youtube Background JS -->
<script src="js/jquery.mb.YTPlayer.min.js"></script>
<!-- datepicker js -->
<script src="js/bootstrap-datepicker.js"></script>
<!-- waypoint js -->
<script src="js/waypoints.min.js"></script>
<!-- onepage nav js -->
<script src="js/jquery.nav.js"></script>
<!-- Google Map js -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBuU_0_uLMnFM-2oWod_fzC0atPZj7dHlU"></script>
<script src="js/google-map.js"></script>
<!-- animate text JS -->
<script src="js/animate-text.js"></script>
<!-- plugins js -->
<script src="js/plugins.js"></script>
<!-- main js -->
<script src="js/main.js"></script>


<!-- theme js files -->
<script src="theme/lib/jquery/jquery-migrate.min.js"></script>
<script src="theme/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="theme/lib/easing/easing.min.js"></script>
<script src="theme/lib/superfish/hoverIntent.js"></script>
<script src="theme/lib/superfish/superfish.min.js"></script>
<script src="theme/lib/wow/wow.min.js"></script>
<script src="theme/lib/venobox/venobox.min.js"></script>
<script src="theme/lib/owlcarousel/owl.carousel.min.js"></script>
<!-- Contact Form JavaScript File -->


<!-- Template Main Javascript File -->
<script src="js/main.js"></script>


    </body>
</html>