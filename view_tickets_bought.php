<?php
session_start();
include('includes/config.php');

if(strlen($_SESSION['usrid']) == 0) {   
    header('location: logout.php');
}

$uid = $_SESSION['usrid'];

// Fetch event details from the event_booking table
$sql = "SELECT idd, event_name, noofattendees, amount_payed FROM event_booking WHERE idd = :userid";
$query = $dbh->prepare($sql);
$query->bindParam(':userid', $uid, PDO::PARAM_INT);
$query->execute();
$events = $query->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Event Details</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .receipt-link {
            color: blue;
            text-decoration: underline;
            cursor: pointer;
        }

        .receipt-link:hover {
            color: darkblue;
        }
    </style>
</head>
<body>
    <h2>User Event Details</h2>
    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>No. of Attendees</th>
                <th>Amount Paid</th>
                <th>See Receipt</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $event) { ?>
                <tr>
                    <td><?php echo $event->event_name; ?></td>
                    <td><?php echo $event->noofattendees; ?></td>
                    <td><?php echo $event->amount_payed; ?></td>
                    <td><a class="receipt-link" href="view_receipt.php?id=<?php echo $event->idd; ?>">View Receipt</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
