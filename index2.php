<?php
session_start();
//datbase connection file
include "includes/config.php";
error_reporting(0);

if (strlen($_SESSION["usrid"]) == 0) {
    header("location:logout.php");
}else{

    $ticket_id = $_GET['id'];
    $sql = "SELECT * FROM tickets WHERE id = :ticket_id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT);
    $stmt->execute();
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ticket) {
        echo "<script>alert('Ticket Not Found.');</script>";
        echo "<script>window.location.href='support.php'</script>";
    }
    if ($ticket['user_id'] != $_SESSION["usrid"]) {
        echo "<script>alert('This Ticket is not associated with your user..');</script>";
        echo "<script>window.location.href='support.php'</script>";
    }


    // update Process
    if (isset($_POST["submit"])) {
        $tid = $_POST["ticket_id"];
        $subject = $_POST["subject"];
        $message = $_POST["message"];
        $sql =
            "INSERT INTO reply (Subject, Message, ticket_id, created_by) VALUES (:subject, :message, :tid, 'user')";
        $query = $dbh->prepare($sql);
        $query->bindParam(":subject", $subject, PDO::PARAM_STR);
        $query->bindParam(":message", $message, PDO::PARAM_STR);
        $query->bindParam(":tid", $tid, PDO::PARAM_STR);
        // Execute the query
        if ($query->execute()) {
            echo "<script>alert('Success: Ticket Reply Created successfully.');</script>";
            echo "<script>window.location.href='view_support.php?id=". $_GET['id'] ."  '</script>";
        } else {
            echo "<script>alert('Error: Failed to create ticket.');</script>";
            // Print error information for debugging
            echo "<pre>";
            print_r($query->errorInfo());
            die();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Support</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome for icons -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
         
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            flex-direction: row-reverse; /* Reversed for ticket info on the right */
        }

        .ticket-info {
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
            width: 30%; /* Allocate 30% width for ticket info */
        }

        .ticket-info h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .ticket-info p {
            margin: 5px 0;
            color: #666;
        }

        .chat-container {
            padding-left: 20px; /* Add padding to separate chat from ticket info */
            width: 50%; /* Allocate 70% width for chat */
            overflow-y: auto; /* Add scrollbar for overflow */
        }

        .chat-box {
            margin-bottom: 10px;
        }

        .message {
            position: relative;
            padding: 10px;
            border-radius: 10px;
            max-width: 70%;
            margin-bottom: 10px;
        }

        .user-message {
        
            align-self: flex-end; /* Align user messages to the right */
            text-align: right; /* Align text to the right */
        }

        .admin-message {
            background-color: #f0f0f0;
            color: #333;
            align-self: flex-start; /* Align admin messages to the left */
            text-align: left; /* Align text to the left */
        }

        .message .icon {
            position: absolute;
            bottom: -15px;
            font-size: 18px;
        }

        .user-message .icon {
            right: -20px;
        }

        .admin-message .icon {
            left: -20px;
        }

        .input-group {
            display: flex;
            margin-top: 10px;
            width:30%
        }

        .input-group input {
            flex: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            outline: none;
        }

        .input-group button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            outline: none;
            margin-left: 10px;
        }

        @media (max-width: 600px) {
            .container {
                flex-direction: column;
            }
            .ticket-info, .chat-container {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="ticket-info">
    <h2>Ticket Information</h2>
    <?php
    // Check if the $_GET['id'] variable is set
    if (!isset($_GET['id'])) {
        echo "Ticket ID not provided.";
        exit;
    }

    // Replace $ticket with your actual ticket data or retrieve it from a database
    // For demonstration purposes, I'm assuming $ticket is an associative array containing ticket information
  
    if ($ticket) {
        // Output the ticket data in an unordered list format
        echo "<ul>";
        echo "<li><strong>ID:</strong> " . $ticket['id'] . "</li>";
        echo "<li><strong>Subject:</strong> " . $ticket['subject'] . "</li>";
        echo "<li><strong>Priority:</strong> " . $ticket['priority'] . "</li>";
        $status = $ticket['status'];
        $badgeClass = '';

        if ($status == 'closed') {
            $badgeClass = 'label-success';
            $statusText = 'Closed';
        } elseif ($status == 'open') {
            $badgeClass = 'label-default';
            $statusText = 'Open';
        } elseif ($status == 'in progress') {
            $badgeClass = 'label-info';
            $statusText = 'In Progress';
        } else {
            $statusText = 'Open';
            $badgeClass = 'label-info';
        }

        echo "<li><strong>Status:</strong> <span class='label $badgeClass'>$statusText</span></li>";
        echo "</ul>";
    } else {
        echo "Ticket not found.";
    }
    ?>
</div>

        
<div class="chat-container">
                            <h2>Chat</h2>
                            <div class="chat-box">
                                <?php
                                try {
                                    echo '<div class="message user-message">
                                            <p>User: ' . $ticket['message'] . '</p>
                                            <i class="fas fa-user-circle icon"></i>
                                          </div>';

                                    $sql = "SELECT * FROM reply WHERE ticket_id = :ticket_id";
                                    $stmt = $dbh->prepare($sql);
                                    $stmt->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT);
                                    $stmt->execute();

                                    if ($stmt->rowCount() > 0) {
                                        $replies = $stmt->fetchAll(PDO::FETCH_OBJ);
                                        foreach ($replies as $row) {
                                            if ($row->created_by == 'user') {
                                                echo '<div class="message user-message">
                                                        <p>User: ' . $row->message . '</p>
                                                        <i class="fas fa-user-circle icon"></i>
                                                      </div>';
                                            } else {
                                                echo '<div class="message admin-message">
                                                        <p>Admin: ' . $row->message . '</p>
                                                        <i class="fas fa-user-circle icon"></i>
                                                      </div>';
                                            }
                                        }
                                    }
                                } catch (PDOException $e) {
                                    echo "Error: " . $e->getMessage();
                                }
                                ?>
                            </div>
                           
                        </div>
                        <h3 class="aside-title uppercase">Create Reply</h3>
                        <div class="row">
                            <form name="ticket" method="post">
                                <div class="col-md-12 col-sm-6 col-xs-12 lyt-left">
                                    <input type="hidden" value=" " placeholder="Subject" class="info" name="subject">
                                    <div class="input-box leave-ib">
                                        <input type="hidden" name="ticket_id" value="<?php echo $_GET['id']?>">
                                        <textarea type="text" placeholder="Message" name="message" id="message" cols="50" class="info" rows="5" style="margin-bottom: 20px;"></textarea>
                                    </div>
                                </div>
                                <div class="input-group">
                                  
                                        <input type="submit" value="Submit" id="update" name="submit" class="submit uppercase">
                                   
                                </div>
                            </form>
                        </div>
                    </div>
    </div>
</body>
</html>
<?php } ?>