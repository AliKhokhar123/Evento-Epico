
<?php
$ret = "Select  PhoneNumber,address,EmailId,footercontent from tblgenralsettings ";
$querys = $dbh -> prepare($ret);
$querys->execute();
$resultss=$querys->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($querys->rowCount() > 0)
{
foreach($resultss as $rows)
{ ?>


<footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6 footer-info">
            <img src="theme/img/logo.png" alt="TheEvenet">
            <p>EventoEpico is Pakistan's premier event management platform, connecting venue owners, organizers, and attendees. We make event planning effortless by offering an easy-to-use interface for booking and managing venues.</p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="fa fa-angle-right"></i> <a href="#">Home</a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#">About us</a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#">Venues</a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#">Events</a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#">Privacy policy</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><i class="fa fa-angle-right"></i> <a href="home.php">Home</a></li>
              <li><i class="fa fa-angle-right"></i> <a href="about-us.php">About us</a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#">Venues</a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#">Events</a></li>
              <li><i class="fa fa-angle-right"></i> <a href="privacypolicy.php">Privacy policy</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-contact">
            <h4>Contact Us</h4>
            <p>
             Uet, Taxila<br>
              <!-- New York, NY 535022<br>
              United States <br> -->
              <strong>Phone:</strong> +9231561090798<br>
              <strong>Email:</strong> info@eventoepico.ecom<br>
            </p>

            <div class="social-links">
              <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
              <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
              <a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
              <a href="#" class="google-plus"><i class="fa fa-google-plus"></i></a>
              <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>
            </div>

          </div>

        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong>EventoEpico</strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!--
          All the links in the footer should remain intact.
          You can delete the links only if you purchased the pro version.
          Licensing information: https://bootstrapmade.com/license/
          Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=TheEvent
        -->
    
      </div>
    </div>
  </footer>
            <?php }} ?>