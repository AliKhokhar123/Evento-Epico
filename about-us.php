<?php
session_start();
//datbase connection file
include('includes/config.php');
error_reporting(0);
?>
<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Event Management System | About us  </title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

        <style>
      * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    line-height: 1.6;
    background-color: #1a1a1a;
    color: #fff;
}

.hero-section {
    background: url('https://img.freepik.com/free-vector/shiny-focus-light-empty-background-design_1017-52418.jpg?w=1060&t=st=1716892904~exp=1716893504~hmac=26a41aab5a3f7e77b20c403007be18003aee6ff51f2c7b01347f10c7a2fa1161') no-repeat center center/cover;
    height: 60vh;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    color: #fff;
}

.hero-section .container {
    max-width: 900px;
}

.hero-section h1 {
    color:red;
    font-size: 3rem;
    margin-bottom: 1rem;
}

.hero-section p {
    font-size: 1.2rem;
}

.about-section, .team-section, .contact-section {
    padding: 4rem 1rem;
    text-align: center;
}

.about-section .container, .team-section .container, .contact-section .container {
    max-width: 900px;
    margin: auto;
}

h2 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color:red;
}

p {
    font-size: 1rem;
    margin-bottom: 1rem;
    color: #b3b3b3;
}

.team-section .team-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    justify-content: center;
}

.team-section .team-member {
    background-color: #333;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    text-align: center;
    width: 250px;
    padding: 1rem;
    transition: transform 0.3s ease;
}

.team-section .team-member:hover {
    transform: translateY(-10px);
}

.team-section .team-member img {
    border-radius: 50%;
    width: 150px;
    height: 150px;
    object-fit: cover;
    margin-bottom: 1rem;
    transition: transform 0.3s ease;
}

.team-section .team-member:hover img {
    transform: scale(1.1);
}

.team-section .team-member h3 {
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
    color:red;
}

.team-section .team-member p {
    font-size: 1rem;
    color: #b3b3b3;
}

.contact-section .form-group {
    margin-bottom: 1.5rem;
}

.contact-section label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #e60000;
}

.contact-section input, .contact-section textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #333;
    color: #fff;
}

.contact-section input:focus, .contact-section textarea:focus {
    outline: none;
    border-color: #e60000;
}

.contact-section button {
    background-color: #e60000;
    color: #fff;
    border: none;
    padding: 0.75rem 1.5rem;
    cursor: pointer;
    border-radius: 4px;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

.contact-section button:hover {
    background-color: #ff1a1a;
}

.footer {
    background-color: #333;
    color: #fff;
    padding: 1rem;
    text-align: center;
}

.footer p {
    margin: 0;
}
    </style>
    </head>
   
    <body style="   line-height: 1.6;
    background-color: #1a1a1a;
    ">
      
        
                <!--header start-->
        <?php include_once('includes/header.php');?>
                <!-- header End-->
         
            <header class="hero-section">
        <div class="container">
            <h1>About Us</h1>
            <p>We are passionate about delivering the best services to our customers.</p>
        </div>
    </header>

    <section class="about-section">
        <div class="container">
            <h2 style="   color:red;">Our Story</h2>
            <p>At EventoEpico, we specialize in creating unforgettable experiences through meticulously planned events. With a dedicated team of professionals, we curate and manage venues, work closely with organizers, and bring visions to life. Whether it's a corporate affair, a community celebration, or a private gathering, we strive to exceed expectations and deliver seamless events that leave a lasting impression. From concept to execution, we're here to make your event exceptional.</p>
        </div>
    </section>

    <section class="team-section">
        <div class="container">
            <h2  style="color:red;">Meet Our Team</h2>
            <div class="team-grid">
                <div class="team-member">
                    <img src="https://via.placeholder.com/150" alt="Team Member 1">
                    <h3>Ali Ahmed</h3>
                    <p>Founder</p>
                </div>
                <div class="team-member">
                    <img src="https://via.placeholder.com/150" alt="Team Member 2">
                    <h3>Muhammad Bilal</h3>
                    <p>Founder</p>
                </div>
                <div class="team-member">
                    <img src="https://via.placeholder.com/150" alt="Team Member 3">
                    <h3>Muzammil Hussain</h3>
                    <p>Founder</p>
                </div>
            </div>
        </div>
    </section>

            <!--footer area are start-->
<?php include_once('includes/footer.php');?>
            <!--footer area are start-->
         </div>   
        <!--body-wraper-are-end-->
		
		<!--==== all js here====-->
		<!-- jquery latest version -->
    
    </body>
</html>
