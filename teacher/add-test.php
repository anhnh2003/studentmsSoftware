<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

$_SESSION['sturecmstuid'] = $_SESSION['sturecmsstuid'];
if (strlen($_SESSION['sturecmstuid']) == 0) {
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
  }

  if (isset($_POST['submit'])) {
    $cid = $_POST['cid'];
    $tname = $_POST['tname'];
    $stime = $_POST['stime'];
    $etime = $_POST['etime'];

    $sql = "INSERT INTO tbltest(TestName, class_id, StartTime, EndTime) VALUES(:tname, :cid, :stime, :etime)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':tname', $tname, PDO::PARAM_STR);
    $query->bindParam(':cid', $cid, PDO::PARAM_STR);
    $query->bindParam(':stime', $stime, PDO::PARAM_STR);
    $query->bindParam(':etime', $etime, PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if ($lastInsertId > 0) {
      echo '<script>alert("Test has been added.")</script>';
      echo "<script>window.location.href ='manage-test.php'</script>";
    } else {
      echo '<script>alert("Something went wrong... Please try again")</script>';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <title>Student Management System || Add Test</title>
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
            <h3 class="page-title"> Add Test </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"> Add Test</li>
              </ol>
            </nav>
          </div>
          <div class="row">

            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title" style="text-align: center;">Add Test</h4>

                  <form class="forms-sample" method="post">

                    <div class="form-group">
                      <label for="exampleInputName1">Test Title</label>
                      <input type="text" name="tname" value="" class="form-control" required='true'>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail3">Class</label>
                      <select name="cid" class="form-control" required='true'>
                        <option value="">Assign a Class</option>
                        <?php
                        $sql2 = "SELECT ID, ClassName from tblclass where teacher_id = :uid";
                        $query2 = $dbh->prepare($sql2);
                        $query2->bindParam(':uid', $uid, PDO::PARAM_INT);
                        $query2->execute();
                        $result2 = $query2->fetchAll(PDO::FETCH_OBJ);

                        foreach ($result2 as $row1) { ?>
                          <option value="<?php echo htmlentities($row1->ID); ?>"><?php echo htmlentities($row1->ClassName); ?> </option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputName1">Start Time</label>
                      <input type="datetime-local" name="stime" value="" class="form-control" required='true'>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputName1">End Time</label>
                      <input type="datetime-local" name="etime" value="" class="form-control" required='true'>
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