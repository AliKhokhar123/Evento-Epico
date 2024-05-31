<?php
session_start();
//datbase connection file
include('includes/config.php');
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

      

      

        .container2 {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 1rem;
            padding-top:70px;
        }

        .container2 h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #e60000;
        }

        p {
            font-size: 1rem;
            margin-bottom: 1rem;
            color: #b3b3b3;
        }

        .container2 ul {
            list-style-type: disc;
            padding-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .container2 li {
            margin-bottom: 0.5rem;
        }

        .footer {
            background-color: #333;
            color: #fff;
            padding: 1rem;
            text-align: center;
            margin-top: 2rem;
        }

        .footer p {
            margin: 0;
        }
    </style>
</head>
<body style=' line-height: 1.6;
            background-color: #1a1a1a;
            color: #fff;'>
<div id="home" class="header-slider-area">
                <!-- Header Start -->
                <?php include_once('includes/header.php'); ?>
                <!-- Header End -->
            </div>

    <div class="container2">
        <section>
            <h2>Introduction</h2>
            <p>Welcome to our event management platform. We value your privacy and are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, and safeguard your information when you use our services.</p>
        </section>

        <section>
            <h2>Information We Collect</h2>
            <p>We collect the following types of information:</p>
            <ul>
                <li><strong>Personal Information:</strong> Name, email address, phone number, etc.</li>
                <li><strong>Event Information:</strong> Details of events organized or attended, venue bookings, etc.</li>
                <li><strong>Payment Information:</strong> Billing details, transaction history, etc.</li>
                <li><strong>Technical Information:</strong> IP address, browser type, operating system, etc.</li>
            </ul>
        </section>

        <section>
            <h2>How We Use Your Information</h2>
            <p>We use the collected information to:</p>
            <ul>
                <li>Provide and maintain our services</li>
                <li>Process transactions and send related information</li>
                <li>Improve, personalize, and expand our services</li>
                <li>Communicate with you, including customer support</li>
                <li>Send marketing and promotional materials</li>
                <li>Monitor and analyze usage and trends</li>
            </ul>
        </section>

        <section>
            <h2>Sharing Your Information</h2>
            <p>We may share your information with:</p>
            <ul>
                <li>Third-party service providers who assist us in providing our services</li>
                <li>Venue owners, event organizers, and attendees as necessary for event management</li>
                <li>Compliance with legal obligations and protection of our rights</li>
            </ul>
        </section>

        <section>
            <h2>Security</h2>
            <p>We implement a variety of security measures to maintain the safety of your personal information. However, no method of transmission over the internet or method of electronic storage is 100% secure.</p>
        </section>

        <section>
            <h2>Your Rights</h2>
            <p>You have the right to:</p>
            <ul>
                <li>Access and update your personal information</li>
                <li>Request deletion of your personal information</li>
                <li>Opt-out of receiving marketing communications</li>
                <li>Restrict or object to the processing of your personal information</li>
            </ul>
        </section>

        <section>
            <h2>Changes to This Privacy Policy</h2>
            <p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on our website.</p>
        </section>

        <section>
            <h2>Contact Us</h2>
            <p>If you have any questions about this Privacy Policy, please contact us at <a href="mailto:info@youreventmanagement.com" style="color: #e60000;">info@youreventmanagement.com</a>.</p>
        </section>
    </div>

    <?php include_once('includes/footer.php'); ?>
</body>
</html>
