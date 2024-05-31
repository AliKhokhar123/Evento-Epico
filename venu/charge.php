<?php

require_once('path_to_stripe_php/init.php'); // Include the Stripe PHP library

\Stripe\Stripe::setApiKey('YOUR_SECRET_KEY'); // Replace 'YOUR_SECRET_KEY' with your own secret key

// Token is created using Checkout or Elements!
// Get the payment token ID submitted by the form:
$token = $_POST['stripeToken'];

// Create a charge:
try {
  $charge = \Stripe\Charge::create(array(
    'amount' => 1000, // Amount in cents
    'currency' => 'usd',
    'description' => 'Example charge',
    'source' => $token,
  ));
  echo "Payment successful!";
} catch (\Stripe\Exception\CardException $e) {
  // Since it's a decline, \Stripe\Exception\CardException will be caught
  $body = $e->getJsonBody();
  $err  = $body['error'];
  echo 'Status is:' . $e->getHttpStatus() . "\n";
  echo 'Type is:' . $err['type'] . "\n";
  echo 'Code is:' . $err['code'] . "\n";
  // param is '' in this case
  echo 'Param is:' . $err['param'] . "\n";
  echo 'Message is:' . $err['message'] . "\n";
} catch (\Stripe\Exception\RateLimitException $e) {
  // Too many requests made to the API too quickly
} catch (\Stripe\Exception\InvalidRequestException $e) {
  // Invalid parameters were supplied to Stripe's API
} catch (\Stripe\Exception\AuthenticationException $e) {
  // Authentication with Stripe's API failed
  // (maybe you changed API keys recently)
} catch (\Stripe\Exception\ApiConnectionException $e) {
  // Network communication with Stripe failed
} catch (\Stripe\Exception\ApiErrorException $e) {
  // Display a very generic error to the user, and maybe send
  // yourself an email
} catch (Exception $e) {
  // Something else happened, completely unrelated to Stripe
}

?>
