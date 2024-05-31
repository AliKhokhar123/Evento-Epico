<?php
session_start();
include('includes/config.php');
require_once('venu/stripe-php-master/init.php'); 
if(strlen($_SESSION['usrid'])==0)
    {   
header('location:logout.php');
}
$uid=$_SESSION['usrid'];
\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $_POST['event_name'];
    $noofattendees = $_POST['quantity'];
    $amount_payed = $_POST['ticket_price'] * $noofattendees * 100; // Amount in cents
    $stripeToken = $_POST['stripeToken'];
    $booking_id = $_POST['booking_id']; // Assuming you pass booking_id in the form
   
    $sqlUser = "SELECT Emailid FROM tblusers WHERE Userid = :userid";
    $queryUser = $dbh->prepare($sqlUser);
    $queryUser->bindParam(':userid', $uid, PDO::PARAM_INT);
    $queryUser->execute();
    $resultUser = $queryUser->fetch(PDO::FETCH_OBJ);
    $user_email = $resultUser->Emailid;
    try {
        $charge = \Stripe\Charge::create([
            'amount' => $amount_payed,
            'currency' => 'usd',
            'description' => 'Payment for Event: ' . $event_name,
            'source' => $stripeToken,
        ]);

        // Insert data into the event_booking table
        $sql = "INSERT INTO event_booking (event_name, noofattendees, amount_payed,idd) VALUES (:event_name, :noofattendees, :amount_payed,:id)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':event_name', $event_name, PDO::PARAM_STR);
        $query->bindParam(':noofattendees', $noofattendees, PDO::PARAM_INT);
        $query->bindValue(':amount_payed', $amount_payed / 100, PDO::PARAM_STR);
        $query->bindValue(':id',  $uid , PDO::PARAM_STR);
        $query->execute();

        // Update the tblvenue_booking table
        $sqlUpdate = "UPDATE tblvenue_booking SET num_attendees = num_attendees - :noofattendees WHERE id = :booking_id";
        $queryUpdate = $dbh->prepare($sqlUpdate);
        $queryUpdate->bindParam(':noofattendees', $noofattendees, PDO::PARAM_INT);
        $queryUpdate->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
        $queryUpdate->execute();

        echo "Payment successful!";
   

        
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    header('Location: payment_failure.php');
    exit();
}
