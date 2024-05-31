<?php
include('../venu/include/function.php');
include('../includes/config.php');

session_start();
error_reporting(E_ALL);

// Fetch the user ID from session
$usrid = $_SESSION['usrid'];

$sql = "SELECT UserName FROM tblusers WHERE Userid = ?";
$query = $dbh->prepare($sql);
$query->execute([$usrid]); // Bind the session user ID
$user = $query->fetch(PDO::FETCH_OBJ);

if ($user) {
    $_SESSION['username'] = $user->UserName;
  
} else {
    echo "User not found.";
}

// Fetch categories
$sql = "SELECT id, CategoryName FROM tblcategory WHERE IsActive=1";
$query = $dbh->prepare($sql);
$query->execute();
$categories = $query->fetchAll(PDO::FETCH_OBJ);

// Fetch venue prices
$sql = "SELECT id, VenuPrice, VenuDescription FROM tblvenu WHERE IsActive=1";
$query = $dbh->prepare($sql);
$query->execute();
$venues = $query->fetchAll(PDO::FETCH_OBJ);

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch venue details including the additional fields
    $sql = "SELECT *, total_price FROM tblcreate_venu WHERE id = ?";
    $query = $dbh->prepare($sql);
    $query->execute([$id]);
    $venue = $query->fetch(PDO::FETCH_ASSOC);

    // Calculate totalPrice2
    $startDate = $venue['start_date'];
    $endDate = $venue['end_date'];
    $venuPrice = $venues[0]->VenuPrice;

    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $interval = $start->diff($end);
    $numberOfDays = $interval->days + 1; // Including the end date
    $totalPrice2 = $numberOfDays * $venuPrice;
}

if (isset($_POST['update'])) {
    $venuname = $_POST['venuname'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $location = $_POST['location'];
    $perhead_price = $_POST['perhead_price'];
    $perheadpriceformenu1 = $_POST['perheadpriceformenu1'];
    $perheadpriceformenu2 = $_POST['perheadpriceformenu2'];
    $menu1 = $_POST['menu1'];
    $menu2 = $_POST['menu2'];
    $booking_fees = $_POST['booking_fees'];
    $max_capacity = $_POST['max_capacity'];

    $sql = "UPDATE tblcreate_venu SET venuname = ?, start_date = ?, end_date = ?, location = ?, per_head_price = ?, perheadpriceformenu1 = ?, perheadpriceformenu2 = ?, menu1 = ?, menu2 = ?, booking_fees = ?, max_capacity = ?, total_Price = ? WHERE id = ?";
    $query = $dbh->prepare($sql);
    $query->execute([$venuname, $start_date, $end_date, $location, $perhead_price, $perheadpriceformenu1, $perheadpriceformenu2, $menu1, $menu2, $booking_fees, $max_capacity, $totalPrice2, $id]);

    $_SESSION['message'] = "Venue updated successfully!";
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EMS | Edit Venue</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Google Font: Source Sans Pro -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Bootstrap Datepicker CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= url('plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="<?= url('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?= url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
    <!-- JQVMap -->
    <link rel="stylesheet" href="<?= url('plugins/jqvmap/jqvmap.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= url('dist/css/adminlte.min.css') ?>">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?= url('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
    <!-- Daterange picker -->
 
    <!-- summernote -->
    <link rel="stylesheet" href="<?= url('plugins/summernote/summernote-bs4.min.css') ?>">
    <script src="<?= url('plugins/jquery/jquery.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <!-- Flatpickr JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function() {
            var previousEndDate = "<?php echo htmlentities($venue['end_date']); ?>";

            $("#end_date").flatpickr({
                dateFormat: "Y-m-d",
                minDate: previousEndDate
            });

            function calculateTotalPrice(startDate, endDate, venuPrice) {
                startDate = new Date(startDate);
                endDate = new Date(endDate);
                venuPrice = parseFloat(venuPrice);

                var totalPrice = (endDate - startDate) / (1000 * 3600 * 24) * venuPrice;
                return totalPrice.toFixed(2);
            }

            $('#total_price2').val(calculateTotalPrice('<?php echo $venue['start_date']; ?>', '<?php echo $venue['end_date']; ?>', '<?php echo $venues[0]->VenuPrice; ?>'));

            $('#end_date').change(function() {
                var totalPrice = calculateTotalPrice($('#start_date').val(), $('#end_date').val(), '<?php echo $venues[0]->VenuPrice; ?>');
                $('#total_price2').val(totalPrice);
            });

            $('form').submit(function() {
                var totalPrice = calculateTotalPrice($('#start_date').val(), $('#end_date').val(), '<?php echo $venues[0]->VenuPrice; ?>');
                $('#total_price2').val(totalPrice);
            });
        });
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php include('../venu/layout/navbar.php')?>
        <?php include('../venu/layout/sidebar.php')?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>Edit Venue</h1>
            </section>

            <section class="content">
                <div class="container mt-5">
                    <h2>Edit Venue</h2>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="venuname">Venue Name</label>
                            <input type="text" class="form-control" id="venuname" name="venuname" value="<?php echo htmlentities($venue['venuname']); ?>" required>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="start_date">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlentities($venue['start_date']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="text" class="form-control datepicker" id="end_date" name="end_date" value="<?php echo htmlentities($venue['end_date']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlentities($venue['location']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="perhead_price">Per Head Price</label>
                            <input type="number" class="form-control" id="perhead_price" name="perhead_price" value="<?php echo htmlentities($venue['per_head_price']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="perheadpriceformenu1">Per Head Price for Menu 1</label>
                            <input type="number" class="form-control" id="perheadpriceformenu1" name="perheadpriceformenu1" value="<?php echo htmlentities($venue['perheadpriceformenu1']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="perheadpriceformenu2">Per Head Price for Menu 2</label>
                            <input type="number" class="form-control" id="perheadpriceformenu2" name="perheadpriceformenu2" value="<?php echo htmlentities($venue['perheadpriceformenu2']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="menu1">Menu 1</label>
                            <input type="text" class="form-control" id="menu1" name="menu1" value="<?php echo htmlentities($venue['menu1']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="menu2">Menu 2</label>
                            <input type="text" class="form-control" id="menu2" name="menu2" value="<?php echo htmlentities($venue['menu2']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="booking_fees">Booking Fees</label>
                            <input type="number" class="form-control" id="booking_fees" name="booking_fees" value="<?php echo htmlentities($venue['booking_fees']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="max_capacity">Max Capacity</label>
                            <input type="number" class="form-control" id="max_capacity" name="max_capacity" value="<?php echo htmlentities($venue['max_capacity']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="total_price2">Total Price</label>
                            <input type="number" class="form-control" id="total_price2" name="total_price2" value="<?php echo htmlentities($totalPrice2); ?>" readonly>
                        </div>
                        <button type="submit" name="update" class="btn btn-primary">Update Venue</button>
                    </form>
                </div>
            </section>
        </div>

        <?php include('../venu/layout/footer.php')?>
    </div>
</body>

</html>
