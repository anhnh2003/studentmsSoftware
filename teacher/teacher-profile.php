<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
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
  if (($query->rowCount() == 0) || ($role_id != 2)) {
      // Token is invalid or expired, redirect to logout
      header('location:logout.php');
      exit();

  } else {
    // Token is valid, continue
    if(isset($_POST['submit']))
  {
    $uid=$_SESSION['sturecmsuid'];
    $UName=$_POST['name'];
  $connum=$_POST['connum'];
  $email=$_POST['email'];
  $is2FA=$_POST['is2FA'];
  $sql="update tblteacher set TeacherName=:name,ContactNumber=:connum,Email=:email,is2FA=:is2FA where ID=:uid";
     $query = $dbh->prepare($sql);
     $query->bindParam(':name',$UName,PDO::PARAM_STR);
     $query->bindParam(':email',$email,PDO::PARAM_STR);
     $query->bindParam(':connum',$connum,PDO::PARAM_STR);
      $query->bindParam(':is2FA',$is2FA,PDO::PARAM_STR);
     $query->bindParam(':uid',$uid,PDO::PARAM_STR);
$query->execute();

    echo '<script>alert("Your profile has been updated")</script>';
    echo "<script>window.location.href ='teacher-profile.php'</script>";

  }
  ?>
<!DOCTYPE html>
<html lang="en">
  <head>
   
    <title>Student Management System || Teacher Profile</title>
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
              <h3 class="page-title"> Teacher Profile </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Teacher Profile</li>
                </ol>
              </nav>
            </div>
            <div class="row">
          
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title" style="text-align: center;">Teacher Profile</h4>
                   
                    <form class="forms-sample" method="post">
                      <?php

$sql="SELECT * from  tblteacher";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
             ?>
                      <div class="form-group">
                        <label for="exampleInputName1">Teacher Name</label>
                        <input type="text" name="name" value="<?php  echo $row->TeacherName;?>" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail3">User Name</label>
                        <input type="text" name="username" value="<?php  echo $row->UserName;?>" class="form-control" readonly="">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword4">Contact Number</label>
                        <input type="text" name="connum" value="<?php  echo $row->ContactNumber;?>"  class="form-control" maxlength='15' required='true' pattern="[0-9]+">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputCity1">Email</label>
                         <input type="email" name="email" value="<?php  echo $row->Email;?>" class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputCity1">Creation Date</label>
                         <input type="text" name="" value="<?php  echo $row->CreationTime;?>" readonly="" class="form-control">
                         </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Two Factor Authentication</label>
                        <select name="is2FA" value="" class="form-control" required='true'>
                          <option value="<?php  echo $row->is2FA;?>">
                          <?php if($row->is2FA==1)
                          {
                            echo "Enabled";
                          } else {
                            echo "Disabled";
                          }
                          ?></option>
                          <option value="
                          <?php if($row->is2FA==1) {
                            echo "0";
                          } else {
                            echo "1";
                          } ?>"> <?php if($row->is2FA==1) {
                            echo "Disabled";
                          } else {
                            echo "Enabled";
                          } ?></option>
                        </select>
                      </div>
                      <?php $cnt=$cnt+1;} ?> 
                      <button type="submit" class="btn btn-primary mr-2" name="submit">Update</button>
                     
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
</html><?php } } ?>