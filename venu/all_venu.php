<?php
session_start(); // Start the session

include ('../venu/include/function.php');
include('../includes/config.php');

// Pagination variables
$results_per_page = 10;
if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}
$offset = ($page - 1) * $results_per_page;

// Fetch data from the database with pagination
$Userid = $_SESSION['usrid'];
$sql = "SELECT * FROM tblcreate_venu WHERE Userid = ? LIMIT $offset, $results_per_page"; // Fetch all venues
$query = $dbh->prepare($sql);
$query->execute([$Userid]);
$venues = $query->fetchAll(PDO::FETCH_ASSOC);

// Fetch total number of rows for pagination
$sql_count = "SELECT COUNT(*) AS count FROM tblcreate_venu WHERE Userid = ?"; // Count all venues
$count_query = $dbh->prepare($sql_count);
$count_query->execute([$Userid]);
$row_count = $count_query->fetch(PDO::FETCH_ASSOC);
$total_records = $row_count['count'];
$total_pages = ceil($total_records / $results_per_page);
?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Dashboard</title>

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

        <!-- Navbar -->
        <?php include('../venu/layout/navbar.php')?>

        <!-- Main Sidebar Container -->
        <?php include('../venu/layout/sidebar.php')?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Dashboard</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard v1</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if(isset($_SESSION['message'])): ?>
                                <div class="alert alert-success">
                                    <?php
                                    echo $_SESSION['message'];
                                    unset($_SESSION['message']);
                                    ?>
                                </div>
                            <?php endif; ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Venue Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Location</th>
                                        <th>Approved</th>
                                       
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($venues) {
                                        foreach ($venues as $index => $venue) {
                                    ?>
                                    <tr>
                                        <td><?php echo $index + 1 + $offset; ?></td>
                                        <td><?php echo htmlentities($venue['venuname']); ?></td>
                                        <td><?php echo htmlentities($venue['start_date']); ?></td>
                                        <td><?php echo htmlentities($venue['end_date']); ?></td>
                                        <td><?php echo htmlentities($venue['location']); ?></td>
                                        <td><?php echo $venue['approved'] ? 'Yes' : 'No'; ?></td>
                                      
                                        <td>
                                            <a href="edit_venue.php?id=<?php echo $venue['id']; ?>" class="btn btn-warning">Edit</a>
                                            <a href="delete_venue.php?id=<?php echo $venue['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this venue?');">Delete</a>

                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                    ?>
                                    <tr>
                                        <td colspan="7">No venues found.</td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <!-- Pagination -->
                            <div class="clearfix">
                                <ul class="pagination pagination-sm m-0 float-right">
                                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                                        <li class="page-item"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- Main content -->
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include('../venu/layout/footer.php')?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</body>
</html>
