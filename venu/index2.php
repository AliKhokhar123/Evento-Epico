<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Stripe Payment</title>
<script src="https://js.stripe.com/v3/"></script>
</head>
<body>
<h1>Stripe Payment Demo</h1>
<form action="" method="post" id="payment-form">
  <div class="form-row">
    <label for="card-element">
      Credit or debit card
    </label>
    <div id="card-element">
      <!-- A Stripe Element will be inserted here. -->
    </div>
    <!-- Used to display form errors. -->
    <div id="card-errors" role="alert"></div>
  </div>

  <button type="submit">Submit Payment</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require_once('stripe-php-master/init.php'); // Include the Stripe PHP library

  \Stripe\Stripe::setApiKey('sk_test_51PHWXC04qeG2jPHmkhUyefu0PovJaAtt4JWBazWhbI9ERMrYb95sXK4lKO4eXh1aOkLEHtbO1dyYbl6Avo9HVtN000JnzxyZ5e'); // Replace 'YOUR_SECRET_KEY' with your own secret key

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
    echo "<p>Payment successful!</p>";
  } catch (\Stripe\Exception\CardException $e) {
    // Since it's a decline, \Stripe\Exception\CardException will be caught
    $body = $e->getJsonBody();
    $err  = $body['error'];
    echo '<p>Status is:' . $e->getHttpStatus() . "</p>\n";
    echo '<p>Type is:' . $err['type'] . "</p>\n";
    echo '<p>Code is:' . $err['code'] . "</p>\n";
    // param is '' in this case
    echo '<p>Param is:' . $err['param'] . "</p>\n";
    echo '<p>Message is:' . $err['message'] . "</p>\n";
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
}
?>

<script>
  var stripe = Stripe('pk_test_51PHWXC04qeG2jPHmFLaX6i9D27zmE3MdtCc3k9RymZKARxnpIC3jNPrSW4w9qjWO4lkNrFYfSBZO3ve0UyePcPPF00ZGqrQBio');
  var elements = stripe.elements();
  var card = elements.create('card');
  card.mount('#card-element');
  card.addEventListener('change', function(event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
      displayError.textContent = event.error.message;
    } else {
      displayError.textContent = '';
    }
  });

  var form = document.getElementById('payment-form');
  form.addEventListener('submit', function(event) {
    event.preventDefault();

    stripe.createToken(card).then(function(result) {
      if (result.error) {
        var errorElement = document.getElementById('card-errors');
        errorElement.textContent = result.error.message;
      } else {
        stripeTokenHandler(result.token);
      }
    });
  });

  function stripeTokenHandler(token) {
    var form = document.getElementById('payment-form');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);
    form.submit();
  }
</script>
</body>
</html>
