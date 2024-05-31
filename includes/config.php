<?php 
// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','ems');
define('STRIPE_SECRET_KEY', 'sk_test_51PHWXC04qeG2jPHmkhUyefu0PovJaAtt4JWBazWhbI9ERMrYb95sXK4lKO4eXh1aOkLEHtbO1dyYbl6Avo9HVtN000JnzxyZ5e');
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51PHWXC04qeG2jPHmFLaX6i9D27zmE3MdtCc3k9RymZKARxnpIC3jNPrSW4w9qjWO4lkNrFYfSBZO3ve0UyePcPPF00ZGqrQBio');
// Establish database connection.
try
{
$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}
?>