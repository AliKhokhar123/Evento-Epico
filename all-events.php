<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['usrid'])==0)
    {   
header('location:logout.php');
}
$uid=$_SESSION['usrid'];
// Fetching Upcoming events with status "approved" and joining with tblcreate_venu to get img1
$status = "approved";
$sql = "SELECT vb.event_name, vb.booking_date, vb.num_attendees, vb.ticket_price, v.image1, vb.event_details, vb.id, vb.selected_menu, vb.organizer_name 
        FROM tblvenue_booking vb 
        JOIN tblcreate_venu v ON vb.venu_id = v.id
        WHERE vb.status = :status";
$query = $dbh->prepare($sql);
$query->bindParam(':status', $status, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);


?>


<!doctype html>
<html class="no-js" lang="en">
<head>
    <title>Event Management System | User Signin </title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        @media only screen and (max-width:500px){
            .modal {
                padding-top:100px;
            }
        }
        #header{
            background-color:#2f3138;
        }
        .modal {
            margin-top:-200px;
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            overflow: auto;
            box-shadow:none !important;
        }
        .modal img{
            width: 100%;
            height:200px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            max-width:400px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .card-img-top {
    height: 200px;
    width: 350px;
        }
        #ticketDetails {
            color: black !important;

        }



     
         #header{
            background-color:#2f3138;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        .card-img-top {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }

        .container3 {
            max-width: 1200px;
            margin: auto;
            margin-top:100px;
            padding: 0 1rem;
        }

        .search-filter {
            padding: 2rem 1rem;
            background-color: #2f3138;
            margin-bottom: 2rem;
            color: #fff;
        }

        .premium-venues, .venues-list {
            padding: 2rem 1rem;
        }

        .card-body {
            background-color: #2f3138;
            color: #fff;
        }

        .btn-primary {
            background-color: #ff0000;
            border: none;
        }

        .btn-primary:hover {
            background-color: #cc0000;
        }
        .filters {
            display: flex;
            justify-content: space-around;
            margin: 2rem 0;
        }

        .filters select {
            padding: 0.5rem;
            border: 1px solid #444;
            border-radius: 4px;
            background-color: #1a1a1a;
            color: #fff;
        }

        .filters button {
            padding: 0.5rem 1rem;
            background-color: #e60000;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .filters button:hover {
            background-color: #cc0000;
        }

        .featured-section {
            margin: 4rem 0;
            text-align: center;
        }

        .featured-section h2 {
            font-size: 2.5rem;
            color: #e60000;
            margin-bottom: 2rem;
        }
        .venue-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: center;
        }

        .venue-card {
            background-color: #222;
            border-radius: 8px;
            overflow: hidden;
            width: 300px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .venue-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .venue-card .content {
            padding: 1rem;
        }

        .venue-card h3 {
            font-size: 1.5rem;
            color: #e60000;
            margin-bottom: 0.5rem;
        }

        .venue-card p {
            font-size: 1rem;
            color: #b3b3b3;
        }

        .venue-card .price {
            font-size: 1.2rem;
            color: #fff;
            margin-top: 1rem;
        }
      
       
 
    </style>
</head>
<body  style="background-color: #1a1a1a; color: #fff;">
   
        <div id="home" class="header-slider-area">
            <?php include_once('includes/header.php');?>
        </div>
        <div class="venues-list" style="margin-top:120px">
                        <h2 style='color:red;text-align:center;'">Upcoming Events</h2>
                        <div class="venue-grid" id="venue-grid">
                    
                    <?php
                    if ($query->rowCount() > 0) {
                        foreach ($results as $row) {
                    ?>
                            <div class="venue-card">
                                    <img  src="venu/upload/<?php echo htmlentities($row->image1);?>" alt="Venue Image">
                                    <div class="content">
                                        <h5 class="card-title" style='color:red'><?php echo htmlentities($row->event_name); ?></h5>
                                        <p class="card-text"><strong>Date:</strong> <?php echo htmlentities($row->booking_date); ?></p>
                                        <p class="card-text"><strong>Ticket Price:</strong> <?php echo htmlentities($row->ticket_price); ?> Rs</p>
                                        <p class="card-text"><strong>Tickets Available:</strong> <?php echo htmlentities($row->num_attendees); ?></p>

                                      <button class="btn btn-primary" onclick="<?php echo isset($_SESSION['role']) && $_SESSION['role'] == 'Attendee' ? "showTicketModal('" . htmlentities($row->image1) . "', '" . htmlentities($row->event_name) . "', '" . htmlentities($row->event_details) . "', '" . htmlentities($row->selected_menu) . "', '" . htmlentities($row->organizer_name) . "', '" . htmlentities($row->id) . "', '" . htmlentities($row->ticket_price) . "', '" . htmlentities($row->num_attendees) . "')" : "alert('Only attendees can access this.')"; ?>">Buy Ticket</button>



                                    </div>
                                </div>
                        
                    <?php
                        }
                    } else {
                    ?>
                        <div class="col-xs-12">
                            <p>No Record Found</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div id="ticketModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeTicketModal()">&times;</span>
                <img src="" id="ticketImage" alt="Venue Image">
                <div id="ticketDetails">
                    <p><strong>Event Name:</strong> <span id="eventName"></span></p>
                    <p><strong>Event Details:</strong> <span id="eventDetails"></span></p>
                    <p><strong>Menu:</strong> <span id="selectedMenu"></span></p>
                    <p><strong>Organizer:</strong> <span id="organizerName"></span></p>
                    <p><strong>Ticket Price:</strong> Rs <span id="ticketPrice"></span></p>
                    <p><strong>Availble ticketgs:</strong> <span id="availble_tickets"></span></p>
                    <p><strong>Id:</strong> <span id="booking_id"></span></p>
                    <label for="ticketQuantity">Number of Tickets:</label>
                    <input type="number" id="ticketQuantity" name="ticketQuantity" min="1">
                    <form id="stripeForm" action="stripe_paymentevent.php" method="POST">
    <input type="hidden" id="eventNameInput" name="event_name" value="">
    <input type="hidden" id="booking_id_input" name="booking_id" value="">
    <input type="hidden" id="ticket_price_input" name="ticket_price" value="">
    <input type="hidden" id="quantity_input" name="quantity" value="">
    <input type="hidden" id="stripeToken" name="stripeToken" value="">
    <input type="hidden" id="availble_tickets_input" name="availble_tickets" value="">
    <input type="hidden" id="user_id_input" name="user_id" value="<?php echo $_SESSION['usrid']; ?>">
    <button id="stripeButton" type="button" class="btn btn-primary" onclick="purchaseTickets()">Pay with Stripe</button>
</form>




                </div>
            </div>
        </div>
        <?php include_once('includes/footer.php');?>
    </div>
    <script src="js/vendor/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="https://checkout.stripe.com/checkout.js"></script>
    <script>
        var modal = document.getElementById('ticketModal');

function showTicketModal(image, eventName, eventDetails, selectedMenu, organizerName, id, ticketPrice, availableTickets) {
    document.getElementById('ticketImage').src = 'venu/upload/' + image;
    document.getElementById('eventName').textContent = eventName;
    document.getElementById('eventNameInput').value = eventName; // Set event name in the hidden input
    document.getElementById('eventDetails').textContent = eventDetails;
    document.getElementById('selectedMenu').textContent = selectedMenu;
    document.getElementById('organizerName').textContent = organizerName;
    document.getElementById('ticketPrice').textContent = ticketPrice;
    document.getElementById('booking_id').textContent = id;
    document.getElementById('availble_tickets').textContent = availableTickets; // Set available tickets
    document.getElementById('availble_tickets_input').value = availableTickets; // Set available tickets in hidden input
    document.getElementById('booking_id_input').value = id; // Set booking_id in the hidden input
    document.getElementById('ticket_price_input').value = ticketPrice;

    modal.style.display = "block";
}

function closeTicketModal() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

var stripeHandler = StripeCheckout.configure({
    key: '<?php echo STRIPE_PUBLISHABLE_KEY; ?>',
    locale: 'auto',
    token: function(token) {
        document.getElementById('stripeToken').value = token.id;
        document.getElementById('stripeForm').submit();
    }
});

function purchaseTickets() {
    var quantity = document.getElementById('ticketQuantity').value;
    var ticketPrice = document.getElementById('ticket_price_input').value;
    var availableTickets = document.getElementById('availble_tickets').textContent; // Retrieve available tickets from the text content

    if (quantity > availableTickets) {
        alert('You cannot purchase more tickets than are available.');
        return;
    }
    var totalAmount = ticketPrice * quantity * 100; // Convert to cents
    document.getElementById('quantity_input').value = quantity;

    stripeHandler.open({
        name: 'Event Management System',
        description: 'Payment for tickets',
        amount: totalAmount,
        currency: 'PKR' // Set currency to PKR
    });
}

function purchaseTickets() {
    var quantity = document.getElementById('ticketQuantity').value;
    var ticketPrice = document.getElementById('ticket_price_input').value;
    var availableTickets = document.getElementById('availble_tickets').textContent; // Retrieve available tickets from the text content

    if (quantity > availableTickets) {
        alert('You cannot purchase more tickets than are available.');
        return;
    }
    var totalAmount = ticketPrice * quantity * 100; // Convert to cents
    document.getElementById('quantity_input').value = quantity;

    stripeHandler.open({
        name: 'Event Management System',
        description: 'Payment for tickets',
        amount: totalAmount
    });
}

    </script>
</body>
</html>
