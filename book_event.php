<?php
include('includes/config.php');

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $numAttendees = $_POST['numAttendees'];
    $bookingDate = $_POST['bookingDate'];
    $venueId = $_GET['id']; // Assuming venue ID is passed in the URL

    // Dummy organizer name for now
    $organizerName = "John Doe";

    // Insert booking details into the database
    $sql = "INSERT INTO tblvenu_booking (venue_id, organizer_name, booking_date, num_attendees) VALUES (:venue_id, :organizer_name, :booking_date, :num_attendees)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':venue_id', $venueId, PDO::PARAM_INT);
    $stmt->bindParam(':organizer_name', $organizerName, PDO::PARAM_STR);
    $stmt->bindParam(':booking_date', $bookingDate, PDO::PARAM_STR);
    $stmt->bindParam(':num_attendees', $numAttendees, PDO::PARAM_INT);
    
    if($stmt->execute()) {
        echo "Booking successful.";
    } else {
        echo "Booking failed.";
    }
}
?>
