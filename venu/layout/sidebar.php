
<aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="../index.php" class="brand-link">
                <img src="<?=url('logo.png')?>" alt="AdminLTE Logo" class="brand-image elevation-3"
                    style="opacity: .8" style="background-color:white">
                <span class="brand-text font-weight-light">Bck to website</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="<?=url('dist/img/user2-160x160.jpg')?>" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                <?php
                // Check if the username is set in the session
                if(isset($_SESSION['username'])) {
                    $username = $_SESSION['username'];
                    echo "<a href='#' class='d-block'>$username</a>";
                } else {
                    echo "<a href='#' class='d-block'>Guest</a>";
                }
                ?>
            </div>
                </div>

           

              <!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
        <li class="nav-item">
            <a href="#" class="nav-link venu-toggle">
                <i class="nav-icon fas fa-table"></i>
                <p>
                    Venu
                    <i class="fas fa-angle-down right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview venu-items" style="display: none;">
                <li class="nav-item">
                    <a href="index.php" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>create venu</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="all_venu.php" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>All venu</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="payments.php" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Manage Payments</p>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
            </div>
            <!-- /.sidebar -->
        </aside>




        <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the Venu toggle link, the Venu items, and the arrow icon
        var venuToggle = document.querySelector('.venu-toggle');
        var venuItems = document.querySelector('.venu-items');
        var arrowIcon = document.querySelector('.venu-toggle i.fa-angle-down');

        // Add event listener to the Venu toggle link
        venuToggle.addEventListener('click', function(event) {
            // Toggle the visibility of Venu items
            if (venuItems.style.display === 'block') {
                venuItems.style.display = 'none';
                // Change arrow icon to face downward when submenu is closed
                arrowIcon.classList.replace('fa-angle-up', 'fa-angle-down');
            } else {
                venuItems.style.display = 'block';
                // Change arrow icon to face upward when submenu is open
                arrowIcon.classList.replace('fa-angle-down', 'fa-angle-up');
            }
        });
    });
</script>