<?php
session_start();
include('../includes/config.php');

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Venue</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

   
</head>

<body >
<body >
    <div class="wrapper">




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
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard v1</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>   



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
<?php include('../venu/layout/footer.php')?>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function() {
        var previousEndDate = "<?php echo htmlentities($venue['end_date']); ?>";

        $("#end_date").flatpickr({
            dateFormat: "Y-m-d",
            minDate: previousEndDate // Set the minimum date for the end date to the previously selected end date
        });
    });
</script>
</body>
</html>
