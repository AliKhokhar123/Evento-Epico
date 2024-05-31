<?php
include('../venu/include/function.php');
include('../includes/config.php');
require_once('stripe-php-master/init.php'); 
session_start();
error_reporting(E_ALL);

// Fetch the user ID from session
$usrid = $_SESSION['usrid'];

$sql = "SELECT id, venuname, start_date, end_date, total_price, paid_amount, payment_status, pending_payment FROM tblcreate_venu WHERE Userid = ?";
$query = $dbh->prepare($sql);
$query->execute([$usrid]); // Bind the session user ID
$venues = $query->fetchAll(PDO::FETCH_OBJ);

// Update the 'pending_payment' column in the database
foreach ($venues as $venue) {
    $pending_payment = $venue->total_price - $venue->paid_amount;
    $total_price = $venue->total_price;
    $venue_id = $venue->id;
    // Determine payment status
    $payment_status = ($pending_payment > 0) ? "Pending" : "Paid";
    // Update the 'pending_payment' and 'payment_status' columns in the database
    $update_sql = "UPDATE tblcreate_venu 
                   SET pending_payment = :pending_payment, payment_status = :payment_status
                   WHERE id = :venue_id";
    $update_query = $dbh->prepare($update_sql);
    $update_query->bindParam(':pending_payment', $pending_payment, PDO::PARAM_INT);
    $update_query->bindParam(':payment_status', $payment_status, PDO::PARAM_STR);
    $update_query->bindParam(':venue_id', $venue_id, PDO::PARAM_INT);
    $update_query->execute();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EMS | Payments</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= url('plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= url('dist/css/adminlte.min.css') ?>">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include('../venu/layout/navbar.php')?>
        <?php include('../venu/layout/sidebar.php')?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Payments</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Payments</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Venues List</h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Venue Name</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Total Price</th>
                                                <th>Payment Status</th>
                                                <th>Pending Payment</th>
                                                <th>Pay</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($venues as $venue) { ?>
                                                <tr>
                                                    <td><?php echo htmlentities($venue->venuname); ?></td>
                                                    <td><?php echo htmlentities($venue->start_date); ?></td>
                                                    <td><?php echo htmlentities($venue->end_date); ?></td>
                                                    <td><?php echo htmlentities($venue->total_price); ?></td>
                                                    <td><?php echo htmlentities($venue->payment_status); ?></td>
                                                    <td><?php echo htmlentities($venue->pending_payment); ?></td>
                                                    <td>
                                                        <?php if ($venue->pending_payment > 0) { ?>
                                                            <form action="stripe_payment.php" method="POST">
                                                                <input type="hidden" name="venue_id" value="<?php echo $venue->id; ?>">
                                                                <input type="hidden" name="amount" value="<?php echo $venue->pending_payment; ?>">
                                                                <script
                                                                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                                    data-key="<?php echo STRIPE_PUBLISHABLE_KEY; ?>"
                                                                    data-currency="PKR"
                                                                    data-amount="<?php echo $venue->pending_payment * 100; ?>"
                                                                    data-name="Venue Payment"
                                                                    data-description="Payment for <?php echo htmlentities($venue->venuname); ?>"
                                                                    data-image="https://example.com/your_logo.jpg"
                                                                    data-locale="auto">
                                                                </script>
                                                            </form>
                                                        <?php } else { ?>
                                                            <button class="btn btn-secondary" disabled>Paid</button>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php include('../venu/layout/footer.php')?>
        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>

    <script src="<?= url('plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= url('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= url('dist/js/adminlte.js') ?>"></script>
</body>
</html>
