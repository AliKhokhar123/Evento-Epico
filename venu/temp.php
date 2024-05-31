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
    echo "Welcome, " . $user->UserName . "!";
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


if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch venue details
    $sql = "SELECT * FROM tblcreate_venu WHERE id = ?";
    $query = $dbh->prepare($sql);
    $query->execute([$id]);
    $venue = $query->fetch(PDO::FETCH_ASSOC);
}

if(isset($_POST['update'])) {
    $venuname = $_POST['venuname'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $location = $_POST['location'];
    $perhead_price = $_POST['perhead_price']; // Add this line to capture the updated per-head price

    $sql = "UPDATE tblcreate_venu SET venuname = ?, start_date = ?, end_date = ?, location = ?, per_head_price = ? WHERE id = ?"; // Update the query to include per_head_price
    $query = $dbh->prepare($sql);
    $query->execute([$venuname, $start_date, $end_date, $location, $perhead_price, $id]); // Include perhead_price in the execute() function

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
    <title>EMS | Create Venue</title>

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
    <link rel="stylesheet" href="<?= url('plugins/daterangepicker/daterangepicker.css') ?>">
    <!-- summernote -->
    <link rel="stylesheet" href="<?= url('plugins/summernote/summernote-bs4.min.css') ?>">

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Header -->
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php include('../venu/layout/navbar.php')?>

        <!-- Main Sidebar Container -->
        <?php include('../venu/layout/sidebar.php')?>
       
        <!-- Left side column. contains the logo and sidebar -->
     
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Edit Venue
                </h1>
            </section>

            <!-- Main content -->
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
            <input type="text" class="form-control" id="end_date" name="end_date" value="<?php echo htmlentities($venue['end_date']); ?>" required>
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlentities($venue['location']); ?>" required>
        </div>
        <div class="form-group">
            <label for="perhead_price">Per Head Price</label>
            <input type="number" class="form-control" id="perhead_price" name="perhead_price" value="<?php echo htmlentities($venue['per_head_price']); ?>" required>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update</button>
    </form>
</div>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="pull-right hidden-xs">
                Anything you want
            </div>
            <!-- Default to the left -->
            <strong>Footer information</strong>
        </footer>
    </div>
    <!-- ./wrapper -->

    <script src="<?= url('plugins/jquery/jquery.min.js') ?>"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="<?= url('plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="<?= url('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- ChartJS -->
    <script src="<?= url('plugins/chart.js/Chart.min.js') ?>"></script>
    <!-- Sparkline -->
    <script src="<?= url('plugins/sparklines/sparkline.js') ?>"></script>
    <!-- JQVMap -->
    <script src="<?= url('plugins/jqvmap/jquery.vmap.min.js') ?>"></script>
    <script src="<?= url('plugins/jqvmap/maps/jquery.vmap.usa.js') ?>"></script>
    <!-- jQuery Knob Chart -->
    <script src="<?= url('plugins/jquery-knob/jquery.knob.min.js') ?>"></script>
    <!-- daterangepicker -->
    <script src="<?= url('plugins/moment/moment.min.js') ?>"></script>
    <script src="<?= url('plugins/daterangepicker/daterangepicker.js') ?>"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="<?= url('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
    <!-- Summernote -->
    <script src="<?= url('plugins/summernote/summernote-bs4.min.js') ?>"></script>
    <!-- overlayScrollbars -->
    <script src="<?= url('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= url('dist/js/adminlte.js') ?>"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?= url('dist/js/demo.js') ?>"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="<?= url('dist/js/pages/dashboard.js') ?>"></script>
</body>

</html>
