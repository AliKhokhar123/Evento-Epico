<?php
include('includes/config.php');
require_once('venu/stripe-php-master/init.php'); 

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $venue_id = $_POST['venu_id']; // Corrected variable name
    $amount = $_POST['amount'];

    try {
        $charge = \Stripe\Charge::create([
            'amount' => $amount,
            'currency' => 'usd',
            'description' => 'Payment for Venue ID: ' . $venue_id,
            'source' => $_POST['stripeToken'],
        ]);

        // Update payment status and upfront in the database
        $sql = "UPDATE tblvenue_booking SET payment_status = 'Paid', upfront = 0 WHERE id = :venue_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':venue_id', $venue_id, PDO::PARAM_INT); // Corrected binding parameter
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
