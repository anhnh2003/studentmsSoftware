<?php
session_start();
session_unset();
session_destroy();
// clear the row in the db 
include('includes/dbconnection.php');
$uid=$_COOKIE['uid'];
$sessToken=$_COOKIE['session_token'];
$sql = "DELETE FROM tbltoken WHERE UserID = :uid and UserToken= :sessToken";
$query = $dbh->prepare($sql);
$query->bindParam(':uid', $uid, PDO::PARAM_INT);
$query->bindParam(':sessToken', $sessToken, PDO::PARAM_STR);
$query->execute();
// Check if there are any cookies set
if (isset($_COOKIE)) {
    // Iterate over each cookie
    foreach ($_COOKIE as $name => $value) {
        // Set the cookie to expire in the past
        setcookie($name, '', time() - 9999999999, '/');
    }
}
header('location:login.php');

?>