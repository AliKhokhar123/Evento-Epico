<?php
session_start();
// Database connection file
include('includes/config.php');
error_reporting(0);

if (strlen($_SESSION['usrid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['rating']) && isset($_POST['venu_id'])) {
        $rating = $_POST['rating'];
        $venu_id = $_POST['venu_id'];

        // Update the rating in the tblcreate_venu table
        $sql = "UPDATE tblcreate_venu SET rating = :rating WHERE id = :venu_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rating', $rating, PDO::PARAM_INT);
        $query->bindParam(':venu_id', $venu_id, PDO::PARAM_INT);
        $query->execute();

        // Redirect back to the bookings page
        header('location:mybookings.php');
    } else {
        echo "Invalid request.";
    }
}
?>
