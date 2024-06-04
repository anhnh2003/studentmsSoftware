<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
  echo '<script>alert("Please login again.")</script>';
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
  if (($query->rowCount() == 0) || ($role_id != 1)) {
      // Token is invalid or expired, redirect to logout
      echo '<script>alert("Please login again.")</script>';
      header('location:logout.php');
      exit();

  } else {
    // Token is valid, continue
  if (isset($_POST['submit'])) {
    $teaid = $_POST['teaid'];
    $cname = $_POST['cname'];
    $room = $_POST['room'];
    $eid = $_GET['editid'];

    $sql = "UPDATE tblclass SET ClassName=:cname, Room=:room, teacher_id=:teaid WHERE ID=:eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':cname', $cname, PDO::PARAM_STR);
    $query->bindParam(':room', $room, PDO::PARAM_STR);
    $query->bindParam(':teaid', $teaid, PDO::PARAM_STR);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();
    echo '<script>alert("Class has been updated")</script>';
  }

  if (isset($_POST['regencode'])) {
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $joincode = '';
    for ($i = 0; $i < 6; $i++) {
      $index = rand(0, strlen($characters) - 1);
      $joincode .= $characters[$index];
    }
    $eid = $_GET['editid'];
    $sql = "UPDATE tblclass SET JoinCode=:joincode WHERE ID=:eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':joincode', $joincode, PDO::PARAM_STR);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();
    echo '<script>alert("Join Code has been changed")</script>';
  }
}}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Student Management System || Manage Class</title>
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
  <link rel="stylesheet" href="css/style.css"/>
</head>
<body>
<div class="container-scroller">
  <!-- partial:partials/_navbar.html -->
  <?php include_once('includes/header.php'); ?>
  <!-- partial -->
  <div class="container-fluid page-body-wrapper">
    <!-- partial:partials/_sidebar.html -->
    <?php include_once('includes/sidebar.php'); ?>
    <!-- partial -->
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="page-header">
          <h3 class="page-title"> Manage Class </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page"> Manage Class</li>
            </ol>
          </nav>
        </div>
        <div class="row">
          <div class="col-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title" style="text-align: center;">Manage Class</h4>
                <form class="forms-sample" method="post">
                  <?php
                  $eid = $_GET['editid'];
                  $sql = "SELECT * FROM tblclass, tblteacher WHERE teacher_id=tblteacher.ID AND tblclass.ID=$eid";
                  $query = $dbh->prepare($sql);
                  $query->execute();
                  $results = $query->fetchAll(PDO::FETCH_OBJ);
                  $cnt = 1;
                  if ($query->rowCount() > 0) {
                    foreach ($results as $row) {
                      ?>
                      <div class="form-group">
                        <label for="exampleInputName1">Class Name</label>
                        <input type="text" name="cname"
                             value="<?php echo htmlentities($row->ClassName); ?>"
                             class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Room</label>
                        <input type="text" name="room"
                             value="<?php echo htmlentities($row->Room); ?>"
                             class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail3">Teacher</label>
                        <select name="teaid" class="form-control" required='true'>
                          <option
                              value="<?php echo htmlentities($row->teacher_id); ?>"><?php echo htmlentities($row->TeacherName); ?></option>
                          <?php
                          $sql2 = "SELECT ID, TeacherName FROM tblteacher WHERE ID != " . $row->teacher_id;
                          $query2 = $dbh->prepare($sql2);
                          $query2->execute();
                          $result2 = $query2->fetchAll(PDO::FETCH_OBJ);

                          foreach ($result2 as $row1) {
                            ?>
                            <option
                                value="<?php echo htmlentities($row1->ID); ?>"><?php echo htmlentities($row1->TeacherName); ?> </option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Join Code</label>
                        <input type="text" name="joincode"
                             value="<?php echo htmlentities($row->JoinCode); ?>"
                             class="form-control" required='true' readonly="">
                      </div>
                      <?php $cnt = $cnt + 1;
                    }
                  } ?>
                  <button type="submit" class="btn btn-primary mr-2" name="submit">Update</button>
                  <button type="submit" class="btn btn-primary mr-2" name="regencode">Change Code
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
      <!-- partial:partials/_footer.html -->
      <?php include_once('includes/footer.php'); ?>
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
</html>