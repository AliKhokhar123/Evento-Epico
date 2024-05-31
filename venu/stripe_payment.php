<?php
include('../includes/config.php');
require_once('stripe-php-master/init.php'); 

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $venue_id = $_POST['venue_id'];
    $amount = $_POST['amount'];

    try {
        $charge = \Stripe\Charge::create([
            'amount' => $amount,
            'currency' => 'usd',
            'description' => 'Payment for Venue ID: ' . $venue_id,
            'source' => $_POST['stripeToken'],
        ]);

        // Update payment status in the database
        $sql = "UPDATE tblcreate_venu SET payment_status = 'Paid', pending_payment = 0,paid_amount= $amount WHERE id = :venue_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':venue_id', $venue_id, PDO::PARAM_INT);
        $query->execute();

        // Redirect to a success page
        header('Location: payment_success.php');
    } catch (Exception $e) {
        // Handle error
        echo 'Error: ' . $e->getMessage();
    }
} else {
    // Redirect to a failure page
    header('Location: payment_failure.php');
}
?>
