<?php
session_start();
$ret = "Select SiteName from tblgenralsettings ";
$querys = $dbh->prepare($ret);
$querys->execute();
$resultss = $querys->fetchAll(PDO::FETCH_OBJ);
$cnt = 1;
$current_page = basename($_SERVER['PHP_SELF']);

if ($querys->rowCount() > 0) {
    foreach ($resultss as $rows) { ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Responsive Header with Hamburger Icon</title>
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800" rel="stylesheet">
            <link href="theme/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
            <link href="theme/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
            <link href="theme/css/style.css" rel="stylesheet">
            <style>
                /* Mobile view CSS */
                @media (max-width: 768px) {
                    .nav-menu.active {
                        background-color: #060c22;
                        display: flex;
                    }
                    #nav-menu-container {
                        width: 100%;
                    }
                    .nav-menu li.buy-tickets a {
                        width: 96% !important;
                    }
                    .nav-menu {
                        display: none;
                        flex-direction: column;
                        align-items: center;
                        width: 100%;
                        text-align: center;
                        background-color: #060c22;
                    }

                    .nav-menu.active {
                        display: flex;
                    }

                    .nav-menu li {
                        width: 100%;
                        margin: 10px 0;
                    }

                    .nav-menu li a {
                        display: block;
                        padding: 10px 0;
                        width: 100%;
                    }

                    .hamburger {
                        color: white;
                        display: block;
                        cursor: pointer;
                        position: absolute;
                        top: 20px;
                        right: 20px;
                        font-size: 30px;
                        margin-top: -73px;
                    }
                }
            </style>
        </head>
        <body>
        <header id="header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div id="logo" class="pull-left">
                            <a href="#intro" class="scrollto"><img src="theme/img/logo.png"></a>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="hamburger">
                            <i class="fa fa-bars"></i>
                        </div>

                        <nav id="nav-menu-container">
                            <ul class="nav-menu">
                                <li class="<?php echo $current_page == 'index.php' ? 'menu-active' : ''; ?>"><a href="index.php">Home</a></li>
                                <li class="<?php echo $current_page == 'venu_page.php' ? 'menu-active' : ''; ?>"><a href="venu_page.php">All venues</a></li>
                                <li class="<?php echo $current_page == 'about-us.php' ? 'menu-active' : ''; ?>"><a href="about-us.php">About us</a></li>
                                <li class="<?php echo $current_page == 'all-events.php' ? 'menu-active' : ''; ?>"><a href="all-events.php">Events</a></li>
                               
                                <?php if ($_SESSION['role'] === 'Venue') { ?>
                                    <li class="<?php echo $current_page == 'venu/index.php' ? 'menu-active' : ''; ?>"><a class="smooth-scroll" href="venu/index.php">My dashboard</a></li>
                                <?php } ?>
                                <?php if (strlen($_SESSION['usrid'] == 0)) { ?>
                                    <li class="<?php echo $current_page == 'signup.php' ? 'menu-active' : ''; ?>"><a class="smooth-scroll" href="signup.php">Signup</a></li>
                                    <li class="buy-tickets <?php echo $current_page == 'signin.php' ? 'menu-active' : ''; ?>"><a href="signin.php">Login</a></li>
                                <?php } else { ?>
                                    <li class="<?php echo $current_page == 'support.php' ? 'menu-active' : ''; ?>"><a class="smooth-scroll" href="support.php">Contact Support</a></li>
                                    <li class="<?php echo $current_page == 'profile.php' ? 'menu-active' : ''; ?>"><a class="smooth-scroll" href="profile.php">My Account</a></li>
                                <?php } ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        <script>
            // JavaScript for toggling navigation menu
            document.addEventListener("DOMContentLoaded", function() {
                const hamburger = document.querySelector(".hamburger");
                const navMenu = document.querySelector(".nav-menu");

                hamburger.addEventListener("click", function() {
                    navMenu.classList.toggle("active");
                });
            });

            document.addEventListener("DOMContentLoaded", function() {
                const header = document.getElementById("header");

                window.addEventListener("scroll", function() {
                    if (window.scrollY > 300) { // Adjust the value as needed
                        header.classList.add("scrolled");
                    } else {
                        header.classList.remove("scrolled");
                    }
                });
            });
        </script>
        </body>
        </html>
    <?php }
}
?>
