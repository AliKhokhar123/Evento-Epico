<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['adminsession']) == 0) {
    header('location:logout.php');
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $booking_id = $_POST['booking_id'];
        $status = $_POST['status'];

        // Update the booking status
        $sql = "UPDATE tblvenue_booking SET status = :status WHERE id = :booking_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
        $query->execute();

        // Set success message
        $_SESSION['success'] = "Booking status updated successfully.";

        // Redirect back to the same page
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>EMS | Manage Bookings</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- DataTables CSS -->
    <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <style>
    .errorWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #dd3d36;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
    }

    .succWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #5cb85c;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
    }
    </style>
</head>

<body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <!-- / Header -->
            <?php include_once('includes/header.php');?>
            <!-- / Leftbar -->
            <?php include_once('includes/leftbar.php');?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"> Manage Bookings</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row" style="margin-top:1%">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Manage Bookings
                        </div>

                        <?php if($_SESSION['delmsg']!="")
                        {?>
                            <div class="errorWrap">
                                <strong>Success :</strong>
                                <?php echo htmlentities($_SESSION['delmsg']);?>
                                <?php echo htmlentities($_SESSION['delmsg']="");?>
                            </div>
                        <?php } ?>

                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table width="100%" class="table table-striped table-bordered table-hover"
                                        id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Booking ID</th>
                                                <th>Venue Name</th>
                                                <th>Organizer Name</th>
                                                <th>Selected Menu</th>
                                                <th>Number of Attendees</th>
                                                <th>Total Payment</th>
                                                <th>Payment Status</th>
                                                <th>Approval Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT id, venu_name, organizer_name, selected_menu, num_attendees, total_payment,status,payment_status FROM tblvenue_booking";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            $cnt = 1;
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $row) {
                                            ?>
                                                <tr>
                                                    <td><?php echo htmlentities($cnt); ?></td>
                                                    <td><?php echo htmlentities($row->id); ?></td>
                                                    <td><?php echo htmlentities($row->venu_name); ?></td>
                                                    <td><?php echo htmlentities($row->organizer_name); ?></td>
                                                    <td><?php echo htmlentities($row->selected_menu); ?></td>
                                                    <td><?php echo htmlentities($row->num_attendees); ?></td>
                                                    <td><?php echo htmlentities($row->total_payment); ?></td>
                                                    <td><?php echo htmlentities($row->payment_status); ?></td>
                                                    <td><?php echo htmlentities($row->status); ?></td>
                                                    <td>
    <?php if ($row->status == 'Pending') { ?>
        <form  method="POST">
            <input type="hidden" name="booking_id" value="<?php echo htmlentities($row->id); ?>">
            <input type="hidden" name="status" value="Approved">
            <button type="submit" class="btn btn-success">Approve</button>
        </form>
        <form  method="POST">
            <input type="hidden" name="booking_id" value="<?php echo htmlentities($row->id); ?>">
            <input type="hidden" name="status" value="Rejected">
            <button type="submit" class="btn btn-danger">Reject</button>
        </form>
    <?php } else { ?>
        <button type="button" class="btn btn-success disabled">Approved</button>
    <?php } ?>
</td>

                                                </tr>
                                            <?php 
                                                $cnt++;
                                                }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>
    <script src="../dist/js/sb-admin-2.js"></script>
    <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
    </script>
</body>

</html>
<?php  ?>
