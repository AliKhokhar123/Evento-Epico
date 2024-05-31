<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('stripe-php-master/init.php'); // Include the Stripe PHP library
include('../includes/config.php');

\Stripe\Stripe::setApiKey('sk_test_51PHWXC04qeG2jPHmkhUyefu0PovJaAtt4JWBazWhbI9ERMrYb95sXK4lKO4eXh1aOkLEHtbO1dyYbl6Avo9HVtN000JnzxyZ5e'); // Set your secret key

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $venue_id = $_POST['venue_id'];

    // Fetch the venue details including pending_payment
    $sql = "SELECT * FROM tblcreate_venu WHERE id = ?";
    $query = $dbh->prepare($sql);
    $query->execute([$venue_id]);
    $venue = $query->fetch(PDO::FETCH_OBJ);

    if ($venue) {
        // Use pending_payment as the amount for Stripe
        $pending_payment = $venue->pending_payment * 100; // Convert to cents
        try {
            // Create a payment intent
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $pending_payment,
                'currency' => 'usd',
                'description' => 'Payment for venue: ' . $venue->venuname,
            ]);

            if ($paymentIntent->status === 'succeeded') {
                // Update pending payment to 0 for the corresponding venue ID
                $update_sql = "UPDATE tblcreate_venu SET pending_payment = 0 WHERE id = :venue_id";
                $update_query = $dbh->prepare($update_sql);
                $update_query->bindParam(':venue_id', $venue_id, PDO::PARAM_INT);
                $update_query->execute();
            
                // Redirect the user to a success page or display a success message
                header('Location: payment_success.php');
                exit;
            }
            
            if ($paymentIntent->status === 'incomplete') {
                // Update pending payment to 0 for the corresponding venue ID
                
            
                // Redirect the user to a success page or display a success message
                header('Location: incpmplete.php');
                exit;
            }
            
            
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            exit;
        }
    }
}
?>
