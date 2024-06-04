<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sturecmsaid']) == 0) {
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
      header('location:logout.php');
      exit();

  } else {
    // Token is valid, continue
  if (isset($_POST['submit'])) {
    $teaid = $_POST['teaid'];
    $cname = $_POST['cname'];
    $room = $_POST['room'];
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $joincode = '';
    for ($i = 0; $i < 6; $i++) {
      $index = rand(0, strlen($characters) - 1);
      $joincode .= $characters[$index];
    }

    $sql = "INSERT INTO tblclass(ClassName, Room, teacher_id, JoinCode) VALUES(:cname, :room, :teaid, :joincode)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':cname', $cname, PDO::PARAM_STR);
    $query->bindParam(':room', $room, PDO::PARAM_STR);
    $query->bindParam(':teaid', $teaid, PDO::PARAM_STR);
    $query->bindParam(':joincode', $joincode, PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if ($lastInsertId > 0) {
      echo '<script>alert("Class has been added.")</script>';
      echo "<script>window.location.href ='add-class.php'</script>";
    } else {
      echo '<script>alert("Something went wrong... Please try again")</script>';
    }
  }
}}
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <title>Student Management System || Add Class</title>
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
    <?php include_once('includes/header.php'); ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <?php include_once('includes/sidebar.php'); ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title"> Add Class </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"> Add Class</li>
              </ol>
            </nav>
          </div>
          <div class="row">

            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title" style="text-align: center;">Add Class</h4>

                  <form class="forms-sample" method="post">

                    <div class="form-group">
                      <label for="exampleInputName1">Class Name</label>
                      <input type="text" name="cname" value="" class="form-control" required='true'>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputName1">Room</label>
                      <input type="text" name="room" value="" class="form-control" required='true'>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail3">Teacher</label>
                      <select name="teaid" class="form-control" required='true'>
                        <option value="">Assign a Teacher</option>
                        <?php
                        $sql2 = "SELECT ID, TeacherName from tblteacher";
                        $query2 = $dbh->prepare($sql2);
                        $query2->execute();
                        $result2 = $query2->fetchAll(PDO::FETCH_OBJ);

                        foreach ($result2 as $row1) { ?>
                          <option value="<?php echo htmlentities($row1->ID); ?>"><?php echo htmlentities($row1->TeacherName); ?> </option>
                        <?php } ?>
                      </select>
                    </div>
                    <button type="submit" class="btn btn-primary mr-2" name="submit">Add</button>

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