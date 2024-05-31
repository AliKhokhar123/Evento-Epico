<?php
session_start();
include('includes/config.php');

if(strlen($_SESSION['usrid']) == 0) {   
    header('location: logout.php');
}

$uid = $_SESSION['usrid'];

    $sqlUser = "SELECT UserName FROM tblusers WHERE Userid = :userid";
    $queryUser = $dbh->prepare($sqlUser);
    $queryUser->bindParam(':userid', $uid, PDO::PARAM_INT);
    $queryUser->execute();
    $resultUser = $queryUser->fetch(PDO::FETCH_OBJ);
    $user_email = $resultUser->UserName;
   




// Check if event id is provided in the URL
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $eventId = $_GET['id'];

    // Fetch event details from the event_booking table
    $sql = "SELECT event_name, noofattendees, amount_payed FROM event_booking WHERE idd = :eventId AND idd = :userid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':eventId', $eventId, PDO::PARAM_INT);
    $query->bindParam(':userid', $uid, PDO::PARAM_INT);
    $query->execute();
    $event = $query->fetch(PDO::FETCH_OBJ);
} else {
    // Redirect if event id is not provided
    header('Location: user_event_details.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .receipt-heading {
            text-align: center;
            margin-bottom: 20px;
        }
        .receipt-details {
            margin-bottom: 20px;
        }
        .receipt-footer {
            font-style: italic;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <h2 class="receipt-heading">Receipt for Event: <?php echo $event->event_name; ?></h2>
        <div class="receipt-details">
      
        <p><strong>Person name:</strong> <?php echo  $resultUser->UserName;?></p>
            <p><strong>No. of Attendees:</strong> <?php echo $event->noofattendees; ?></p>
            <p><strong>Amount Paid:</strong> <?php echo $event->amount_payed; ?></p>
        </div>
        <p class="receipt-footer">Approved by EventoEpico</p>
        <a href="#" id="downloadButton" download="receipt.pdf">
            <button>Download Receipt</button>
        </a>
    </div>
    <script>
        // Function to trigger download when the button is clicked
        document.getElementById('downloadButton').addEventListener('click', function() {
            // Generate PDF or other format of the receipt
            // For simplicity, let's assume the receipt content is in a div with id 'receiptContent'
            var receiptContent = document.querySelector('.receipt-container').innerHTML;

            // Convert HTML content to PDF
            // For demonstration purpose, this will not work directly in a browser, 
            // You would need a library like jsPDF or other server-side solution to generate PDF
            var pdf = new jsPDF();
            pdf.text(20, 20, receiptContent);
            pdf.save('receipt.pdf');
        });
    </script>
</body>
</html>
