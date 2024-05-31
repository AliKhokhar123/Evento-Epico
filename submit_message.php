<?php
include('includes/config.php');
error_reporting(1);

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Prepare SQL statement
    $sql = "INSERT INTO user_messages(name, email, subject, message) VALUES(:name, :email, :subject, :message)";
    $query = $dbh->prepare($sql);
    
    // Binding the values
    $query->bindParam(':name', $name, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':subject', $subject, PDO::PARAM_STR);
    $query->bindParam(':message', $message, PDO::PARAM_STR);

    // Execute SQL statement
    if ($query->execute()) {
        echo "Message sent successfully.";
    } else {
        echo "Error: " . $query->errorInfo()[2];
    }

    // Close statement and connection
    $query = null;
    $dbh = null;
}
?>
