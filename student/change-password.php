<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
error_reporting(0);
if (strlen($_SESSION['sturecmsstuid']) == 0) {

  header('location:logout.php');
  exit();
} else {
  // Retrieve the 'uid' and 'session_token' cookies
  $uid = $_COOKIE['uid'] ?? '';
  $sessionToken = $_COOKIE['session_token'] ?? '';
  // Prepare the SQL statement to select the token from the database
  $sql = "SELECT UserToken, role_id FROM tbltoken WHERE UserID = :uid AND UserToken = :sessionToken AND (CreationTime + INTERVAL 2 HOUR) >= NOW()";
  $query = $dbh->prepare($sql);
  $query->bindParam(':uid', $uid, PDO::PARAM_INT);
  $query->bindParam(':sessionToken', $sessionToken, PDO::PARAM_STR);
  $query->execute();
  $role_id = $query->fetch(PDO::FETCH_OBJ)->role_id;
  // Check if the token exists and is not expired
  if (($query->rowCount() == 0) || ($role_id != 3)) {
      // Token is invalid or expired, redirect to logout
      header('location:logout.php');
      exit();

  } else {
    // Token is valid, continue
if(isset($_POST['submit']))
{
$uid=$_COOKIE['uid'] ?? '';
$cpassword=($_POST['currentpassword']);
$newpassword=($_POST['newpassword']);
// Password policy enforcement
if(!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W]).{8,}$/', $newpassword)) {
  $_SESSION['error'] = "Password must be at least 8 characters long, contain at least one uppercase letter, one number, and one symbol.";
  header('Location: change-password.php');
  exit();
}
$sql ="SELECT ID, Password FROM tblstudent WHERE ID=:uid";
$query= $dbh -> prepare($sql);
$query-> bindParam(':uid', $uid, PDO::PARAM_STR);
$query-> execute();
$result = $query -> fetch(PDO::FETCH_OBJ);
if(($query -> rowCount() > 0) && (password_verify($cpassword, $result->Password)))
{
//insert value in the Password col, the value is the hashed password of the Password col
$password = password_hash($newpassword, PASSWORD_DEFAULT);
$chngpwd2 = $dbh->prepare("UPDATE tblstudent SET Password=:password where ID=:uid");
$chngpwd2-> bindParam(':uid', $uid, PDO::PARAM_STR);
$chngpwd2-> bindParam(':password', $password, PDO::PARAM_STR);
$chngpwd2->execute();

//echo the alert message that show the uid
echo '<script>alert("Your password successfully changed.")</script>';


//echo '<script>alert("Your password successully changed")</script>';
} else {
echo '<script>alert("Your current password is wrong")</script>';

}
}
  ?>
<!DOCTYPE html>
<html lang="en">
  <head>
   
    <title>Student  Management System|| Student Change Password</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="vendors/select2/select2.min.css">
    <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="css/style.css" />
    <script type="text/javascript">
function checkpass()
{
if(document.changepassword.newpassword.value!=document.changepassword.confirmpassword.value)
{
alert('New Password and Confirm Password field does not match');
document.changepassword.confirmpassword.focus();
return false;
}
return true;
}   

</script>
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
     <?php include_once('includes/header.php');?>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
      <?php include_once('includes/sidebar.php');?>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title"> Change Password </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Change Password</li>
                </ol>
              </nav>
            </div>
            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title" style="text-align: center;">Change Password</h4>
                   
                    <form class="forms-sample" name="changepassword" method="post" onsubmit="return checkpass();">
                      
                      <div class="form-group">
                        <label for="exampleInputName1">Current Password</label>
                        <input type="password" name="currentpassword" id="currentpassword" class="form-control" required="true">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail3">New Password</label>
                        <input type="password" name="newpassword" id="newpassword" class="form-control" required="true">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword4">Confirm Password</label>
                        <input type="password" name="confirmpassword" id="confirmpassword" value=""  class="form-control" required="true">
                      </div>
                      <?php
                      if (isset($_SESSION['error'])) {
                      echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
                      unset($_SESSION['error']);}
                      ?>
                      <button type="submit" class="btn btn-primary mr-2" name="submit">Change</button>
                     
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
         <?php include_once('includes/footer.php');?>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="vendors/select2/select2.min.js"></script>
    <script src="vendors/typeahead.js/typeahead.bundle.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="js/typeahead.js"></script>
    <script src="js/select2.js"></script>
    <!-- End custom js for this page -->
  </body>
</html><?php }  }?>