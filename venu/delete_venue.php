<?php
session_start();
include('../includes/config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the SQL statement to delete the venue
    $sql = "DELETE FROM tblcreate_venu WHERE id = ?";
    $query = $dbh->prepare($sql);

    try {
        // Execute the deletion
        $query->execute([$id]);
        $_SESSION['message'] = "Venue deleted successfully!";
    } catch (PDOException $e) {
        // If an error occurs, store an error message in the session
        $_SESSION['error'] = "Error deleting venue: " . $e->getMessage();
    }

    // Redirect to the main page
    header('Location: index.php');
    exit();
} else {
    // If no ID is provided, redirect to the main page with an error message
    $_SESSION['error'] = "No venue ID provided.";
    header('Location: index.php');
    exit();
}
?>
