<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['adminsession'])==0) {   
    header('location:logout.php');
} else { 

    if(isset($_POST['update']))
    {
        $venuid = intval($_GET['venuid']);
        $price = $_POST['Venu_Price'];
        $description = $_POST['Venu_Description'];
        $status = 1;

        $sql = "UPDATE tblvenu SET VenuPrice = :price, VenuDescription = :description, IsActive = :status WHERE id = :venuid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':price', $price, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':venuid', $venuid, PDO::PARAM_INT);
        if($query->execute()) {
            $_SESSION['updatemsg'] = "Venue updated successfully";
        } else {
            $_SESSION['updatemsg'] = "Error updating venue";
        }
        header('location:manage_venu.php');
    }

    // Fetch the current venue details
    if(isset($_GET['venuid'])) {
        $venuid = intval($_GET['venuid']);
        $sql = "SELECT * from tblvenu WHERE id = :venuid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':venuid', $venuid, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>EMS | Edit Venue</title>
    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
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
        <?php include_once('includes/header.php'); ?>
        <!-- / Leftbar -->
        <?php include_once('includes/leftbar.php'); ?>
    </nav>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Edit Venue</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Edit Venue
                    </div>

                    <?php if ($_SESSION['updatemsg'] != "") { ?>
                        <div class="succWrap">
                            <strong>Success :</strong>
                            <?php echo htmlentities($_SESSION['updatemsg']); ?>
                            <?php echo htmlentities($_SESSION['updatemsg'] = ""); ?>
                        </div>
                    <?php } ?>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <form role="form" method="post">
                                    <!-- Price -->
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input class="form-control" type="text" name="Venu_Price" value="<?php echo htmlentities($result->VenuPrice); ?>" required>
                                    </div>

                                    <!-- Description -->
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" name="Venu_Description" required><?php echo htmlentities($result->VenuDescription); ?></textarea>
                                    </div>

                                    <!-- Button -->  
                                    <div class="form-group" align="center">                     
                                        <button type="submit" class="btn btn-primary" name="update">Update</button>
                                    </div>
                                </form>
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
<!-- Bootstrap Core JavaScript -->
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="../vendor/metisMenu/metisMenu.min.js"></script>
<!-- Custom Theme JavaScript -->
<script src="../dist/js/sb-admin-2.js"></script>
</body>
</html>
<?php } ?>