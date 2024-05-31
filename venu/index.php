<?php
include ('../venu/include/function.php');
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

// Fetch venues
$sql = "SELECT id, VenuPrice, VenuDescription FROM tblvenu WHERE IsActive=1";
$query = $dbh->prepare($sql);
$query->execute();
$venues = $query->fetchAll(PDO::FETCH_OBJ);

// Fetch cities
$sql = "SELECT id, city_name FROM city";
$query = $dbh->prepare($sql);
$query->execute();
$cities = $query->fetchAll(PDO::FETCH_OBJ);

if (isset($_POST['submit'])) {
    $venuName = $_POST['venuname'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    // Fetch the venuPrice from the fetched venues
    $venuPrice = $venues[0]->VenuPrice; // Assuming there is only one venue fetched

    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $location = $_POST['location'];
    $city = $_POST['city'];
    $menu1 = $_POST['menu1'];
    $menu2 = $_POST['menu2'];
    $perheadpriceformenu1 = $_POST['perheadpriceformenu1'];
    $perheadpriceformenu2 = $_POST['perheadpriceformenu2'];
    $other_services = $_POST['other_services'];
    $restrictions = $_POST['restrictions'];
    $max_capacity = $_POST['max_capacity'];
    $booking_fees=$_POST['booking_fees'];
    // Calculate total price based on number of days and venue price
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $interval = $start->diff($end);
    $numberOfDays = $interval->days + 1; // Including the end date
    $totalPrice = $numberOfDays * $venuPrice;
    $refreshment =$_POST['refreshment'];

    // Handle file uploads
    $uploadDir = 'upload/';
    $image1Name = '';
    $image2Name = '';
    $image3Name = '';

    if (isset($_FILES['image1']) && $_FILES['image1']['error'] == UPLOAD_ERR_OK) {
        $image1Name = time() . '_' . $_FILES['image1']['name'];
        move_uploaded_file($_FILES['image1']['tmp_name'], $uploadDir . $image1Name);
    }
    if (isset($_FILES['image2']) && $_FILES['image2']['error'] == UPLOAD_ERR_OK) {
        $image2Name = time() . '_' . $_FILES['image2']['name'];
        move_uploaded_file($_FILES['image2']['tmp_name'], $uploadDir . $image2Name);
    }
    if (isset($_FILES['image3']) && $_FILES['image3']['error'] == UPLOAD_ERR_OK) {
        $image3Name = time() . '_' . $_FILES['image3']['name'];
        move_uploaded_file($_FILES['image3']['tmp_name'], $uploadDir . $image3Name);
    }

 // Insert data into tblcreate_venu
 $sqlInsert = "INSERT INTO `tblcreate_venu`(`venuname`, `category`, `description`, `venu_price`, `start_date`, `end_date`, `total_price`, `location`, `city`, `menu1`, `menu2`, `perheadpriceformenu1`, `perheadpriceformenu2`, `other_services`, `restrictions`, `image1`, `image2`, `image3`, `Userid`, `max_capacity`, `booking_fees`, `refreshment`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


 $stmt = $dbh->prepare($sqlInsert);
 $stmt->execute([$venuName, $category, $description, $venuPrice, $startDate, $endDate, $totalPrice, $location, $city, $menu1, $menu2, $perheadpriceformenu1, $perheadpriceformenu2, $other_services, $restrictions, $image1Name, $image2Name, $image3Name, $usrid, $max_capacity, $booking_fees, $refreshment]);




   
    // Insert data into tblcreate_venu
  

    // Redirect after insertion
    header('Location: index.php'); // Redirect to a success page
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

        <!-- Navbar -->
        <?php include('../venu/layout/navbar.php')?>

        <!-- Main Sidebar Container -->
     <?php include('../venu/layout/sidebar.php')?> 
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
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Create Venue</h3>
                                </div>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="form-group col-md-5">
                                                <label for="VenuName">Venue Name</label>
                                                <input type="text" name="venuname" required class="form-control" id="VenuName" placeholder="Venue Name">
                                            </div>
                                            <div class="form-group col-md-7">
    <label for="CategoryName">Category Name</label>
    <select name="category" required class="form-control" id="CategoryName">
        <option value="">Select Category</option>
        <?php foreach ($categories as $category) { ?>
            <option value="<?php echo htmlentities($category->CategoryName); ?>"><?php echo htmlentities($category->CategoryName); ?></option>
        <?php } ?>
    </select>
</div>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <input type="text" name="description" required class="form-control" id="description" placeholder="Venue Description">
                                        </div>
                                        <div class="form-row">
                                        <div class="form-group col-md-4" id="perHeadPrice1Group">
        <label for="perheadpriceformenu1">Per Head Price for Menu 1</label>
        <input type="number" name="perheadpriceformenu1"  class="form-control" id="perheadpriceformenu1" placeholder="Enter per head price for Menu 1">
    </div>
    <div class="form-group col-md-4" id="perHeadPrice2Group">
        <label for="perheadpriceformenu2">Per Head Price for Menu 2</label>
        <input type="number" name="perheadpriceformenu2"  class="form-control" id="perheadpriceformenu2" placeholder="Enter per head price for Menu 2">
    </div>
                                            <div class="form-group col-md-4">
                                                <label for="start_date">Start Date</label>
                                                <input type="text" name="start_date" required class="form-control datepicker" id="start_date" placeholder="Start Date">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="end_date">End Date</label>
                                                <input type="text" name="end_date" required class="form-control datepicker" id="end_date" placeholder="End Date">
                                            </div>
                                            <div class="form-group col-md-4">
    <label for="max_capacity">Maximum Capacity</label>
    <input type="number" name="max_capacity" required class="form-control" id="max_capacity" placeholder="Enter Maximum Capacity">
</div>

                                        </div>
                                        <div class="form-group">
                                            <label for="location">Location</label>
                                            <input type="text" name="location" required class="form-control" id="location" placeholder="Venue Location">
                                        </div>
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <select name="city" required class="form-control" id="city">
                                                <option value="">Select City</option>
                                                <?php foreach ($cities as $city) { ?>
                                                    <option value="<?php echo htmlentities($city->city_name); ?>"><?php echo htmlentities($city->city_name); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group" id="menu1Group">
    <label for="menu1">Menu 1</label>
    <input type="text" name="menu1"  class="form-control" id="menu1" placeholder="Enter Menu 1">
</div>
<div class="form-group" id="menu2Group">
    <label for="menu2">Menu 2</label>
    <input type="text" name="menu2"  class="form-control" id="menu2" placeholder="Enter Menu 2">
</div>
                                        <div class="form-group">
                                            <label for="other_services">Other Services</label>
                                            <input type="text" name="other_services" required class="form-control" id="other_services" placeholder="Enter Other Services">
                                        </div>
                                        <div class="form-group" id='bookingfees'>
                                            <label for="bookingfees"> bookingfees</label>
                                            <input type="text" name="booking_fees"  class="form-control" id="booking_fees" placeholder="Enter Boooking Fees">
                                        </div>
                            
                                        
                                        <div class="form-group" id='refreshments'>
                                            <label for="refreshment"> refreshments</label>
                                            <input type="text" name="refreshment"  class="form-control" id="refreshment" placeholder="Enter refreshment item">
                                        </div>
                                        <div class="form-group">
                                            <label for="restrictions">Restrictions</label>
                                            <input type="text" name="restrictions" required class="form-control" id="restrictions" placeholder="Enter Restrictions">
                                        </div>
                                        <div class="form-group">
                                            <label for="image1">Image 1</label>
                                            <input type="file" name="image1" class="form-control-file" id="image1">
                                        </div>
                                        <div class="form-group">
                                            <label for="image2">Image 2</label>
                                            <input type="file" name="image2" class="form-control-file" id="image2">
                                        </div>
                                        <div class="form-group">
                                            <label for="image3">Image 3</label>
                                            <input type="file" name="image3" class="form-control-file" id="image3">
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
        </div>
        </section>
    </div>

    <!-- /.content-wrapper -->
    <?php include('../venu/layout/footer.php') ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
  

    <script src="<?= url('plugins/jquery/jquery.min.js') ?>"></script>
    <script>
    $(document).ready(function() {
        $('#CategoryName').change(function() {
            $('#bookingfees').hide();
            $('#refreshments').hide();
            var selectedCategory = $(this).val();
            console.log("cc"+selectedCategory)
            if (selectedCategory === "Musical Party"||selectedCategory === "Conference")  {
                // Hide menu1, menu2, perheadpriceformenu1, and perheadpriceformenu2 fields
                $('#bookingfees').show();
                $('#refreshments').show();
                $('#menu1Group').hide();
                $('#menu2Group').hide();
                $('#perHeadPrice1Group').hide();
                $('#perHeadPrice2Group').hide();
                $('#menu1').val('');
    $('#menu2').val('');
    $('#perheadpriceformenu1').val('');
    $('#perheadpriceformenu2').val('');
    
   
            } else {
                // Show menu1, menu2, perheadpriceformenu1, and perheadpriceformenu2 fields
                $('#menu1Group').show();
                $('#menu2Group').show();
                $('#perHeadPrice1Group').show();
                $('#perHeadPrice2Group').show();
            }
        });
    });
</script>
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


    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function () {
            $(".datepicker").flatpickr({
                dateFormat: "Y-m-d",
                minDate: "today", // Start date cannot be before today
                onChange: function (selectedDates, dateStr, instance) {
                    // Set the minimum date for the end date picker to the selected start date
                    if (instance.element.id === "start_date") {
                        $("#end_date").flatpickr({
                            minDate: dateStr,
                            dateFormat: "Y-m-d"
                        });
                    }
                }
            });
        });
    </script>
</body>

</html>
