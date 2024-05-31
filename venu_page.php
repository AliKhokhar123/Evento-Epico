
<?php

session_start();
error_reporting(0);
include('includes/config.php');

// Fetch all active categories
$sql = "SELECT id, CategoryName FROM tblcategory WHERE IsActive = '1'";
$query = $dbh->prepare($sql);
$query->execute();
$categories = $query->fetchAll(PDO::FETCH_OBJ);

// Fetch all active cities
$sql = "SELECT id, city_name FROM city";
$query = $dbh->prepare($sql);
$query->execute();
$cities = $query->fetchAll(PDO::FETCH_OBJ);

// Fetch all approved venues
$filterCategory = isset($_POST['category']) ? $_POST['category'] : '';
$filterCity = isset($_POST['city']) ? $_POST['city'] : '';

$sql = "SELECT id, venuname, image1, perheadpriceformenu1, perheadpriceformenu2, booking_fees, category, city FROM tblcreate_venu WHERE approved = 1";
if ($filterCategory) {
    $sql .= " AND category = :category";
}
if ($filterCity) {
    $sql .= " AND city = :city";
}
$query = $dbh->prepare($sql);
if ($filterCategory) {
    $query->bindParam(':category', $filterCategory, PDO::PARAM_STR);
}
if ($filterCity) {
    $query->bindParam(':city', $filterCity, PDO::PARAM_STR);
}
$query->execute();
$venues = $query->fetchAll(PDO::FETCH_OBJ);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venue Listings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
         #header{
            background-color:#2f3138;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        .card-img-top {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }

        .container3 {
            max-width: 1200px;
            margin: auto;
            margin-top:100px;
            padding: 0 1rem;
        }

        .search-filter {
            padding: 2rem 1rem;
            background-color: #2f3138;
            margin-bottom: 2rem;
            color: #fff;
        }

        .premium-venues, .venues-list {
            padding: 2rem 1rem;
        }

        .card-body {
            background-color: #2f3138;
            color: #fff;
        }

        .btn-primary {
            background-color: #ff0000;
            border: none;
        }

        .btn-primary:hover {
            background-color: #cc0000;
        }
        .filters {
            display: flex;
            justify-content: space-around;
            margin: 2rem 0;
        }

        .filters select {
            padding: 0.5rem;
            border: 1px solid #444;
            border-radius: 4px;
            background-color: #1a1a1a;
            color: #fff;
        }

        .filters button {
            padding: 0.5rem 1rem;
            background-color: #e60000;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .filters button:hover {
            background-color: #cc0000;
        }

        .featured-section {
            margin: 4rem 0;
            text-align: center;
        }

        .featured-section h2 {
            font-size: 2.5rem;
            color: #e60000;
            margin-bottom: 2rem;
        }
        .venue-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: center;
        }

        .venue-card {
            background-color: #222;
            border-radius: 8px;
            overflow: hidden;
            width: 300px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .venue-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .venue-card .content {
            padding: 1rem;
        }

        .venue-card h3 {
            font-size: 1.5rem;
            color: #e60000;
            margin-bottom: 0.5rem;
        }

        .venue-card p {
            font-size: 1rem;
            color: #b3b3b3;
        }

        .venue-card .price {
            font-size: 1.2rem;
            color: #fff;
            margin-top: 1rem;
        }
        .checked{
            color:red;
        }
       
    </style>
</head>
<body style="background-color: #1a1a1a; color: #fff;">
    <div id="home" class="header-slider-area">
        <?php include_once "includes/header.php"; ?>
    </div>

    <div class="container3">
        <!-- Search and Filter Section -->
        <form method="post" class="filters">
            <select name="category" id="category-filter">
                <option value="">Filter by Category</option>
                <?php foreach($categories as $category): ?>
                    <option value="<?php echo htmlentities($category->CategoryName); ?>" <?php echo ($filterCategory == $category->CategoryName) ? 'selected' : ''; ?>><?php echo htmlentities($category->CategoryName); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="city" id="city-filter">
                <option value="">Filter by City</option>
                <?php foreach($cities as $city): ?>
                    <option value="<?php echo htmlentities($city->city_name); ?>" <?php echo ($filterCity == $city->city_name) ? 'selected' : ''; ?>><?php echo htmlentities($city->city_name); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Submit</button>
        </form>

        <!-- Premium Venues Section -->
        <section class="featured-section">
    <h2>Featured Venues</h2>
    <div class="venue-grid">
        <?php
        // Fetch featured venues with a rating greater than or equal to 4
        $sql = "SELECT id, venuname, image1, perheadpriceformenu1, perheadpriceformenu2, booking_fees, category, city, rating FROM tblcreate_venu WHERE approved = 1 AND rating >= 4";
        $query = $dbh->prepare($sql);
        $query->execute();
        $featuredVenues = $query->fetchAll(PDO::FETCH_OBJ);

        // Display featured venues
        foreach ($featuredVenues as $venue) {
            $rating = $venue->rating;
            ?>
            <div class="venue-card">
                <img src="venu/upload/<?php echo $venue->image1; ?>" alt="Venue Image">
                <div class="content">
                    <h3><?php echo htmlentities($venue->venuname); ?></h3>
                    <p>
                        <?php if($venue->category == 'Conference' || $venue->category == 'Musical Party'): ?>
                            Booking Fees: <?php echo htmlentities($venue->booking_fees); ?> Rs
                        <?php else: ?>
                            Per head price: <?php echo htmlentities($venue->perheadpriceformenu1); ?> - <?php echo htmlentities($venue->perheadpriceformenu2); ?> Rs
                        <?php endif; ?>
                    </p>
                    <div class="rating">
                        <?php
                        // Display filled stars based on rating
                        for ($i = 1; $i <= $rating; $i++) {
                            echo '<span class="fa fa-star checked"></span>';
                        }

                        // Display empty stars
                        for ($i = $rating + 1; $i <= 5; $i++) {
                            echo '<span class="fa fa-star"></span>';
                        }
                        ?>
                    </div>
                    <a href="venue_details.php?id=<?php echo $venue->id; ?>" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

        <!-- All Venues Section -->
        <section class="venues-list">
            <h2 style='color:red;text-align:center;'>All Venues</h2>
            <div class="venue-grid" id="venue-grid">
                <?php foreach($venues as $venue): ?>
                    <div class="venue-card" data-category="<?php echo htmlentities($venue->category); ?>" data-city="<?php echo htmlentities($venue->city); ?>">
                        <img src="venu/upload/<?php echo $venue->image1; ?>" alt="Venue Image">
                        <div class="content">
                            <h3><?php echo htmlentities($venue->venuname); ?></h3>
                            <p>
                                <?php if($venue->category == 'Conference' || $venue->category == 'Musical Party'): ?>
                                    Booking Fees: <?php echo htmlentities($venue->booking_fees); ?> Rs
                                <?php else: ?>
                                    Per head price: <?php echo htmlentities($venue->perheadpriceformenu1); ?> - <?php echo htmlentities($venue->perheadpriceformenu2); ?> Rs
                                <?php endif; ?>
                            </p>
                            <a href="venue_details.php?id=<?php echo $venue->id; ?>" class="btn btn-primary">Book Now</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
    <?php include_once('includes/footer.php');?>
</body>
</html>
