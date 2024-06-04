<?php
session_start();
include('includes/dbconnection.php');

$_SESSION['sturecmstuid'] = $_SESSION['sturecmsstuid'];

if (strlen($_SESSION['sturecmstuid']) == 0) {
  header('location:logout.php');
  exit();
} else {
  $uid = $_COOKIE['uid'] ?? '';
  $sessionToken = $_COOKIE['session_token'] ?? '';

  $sql = "SELECT UserToken, role_id FROM tbltoken WHERE UserID = :uid AND UserToken = :sessionToken AND (CreationTime + INTERVAL 2 HOUR) >= NOW()";
  $query = $dbh->prepare($sql);
  $query->bindParam(':uid', $uid, PDO::PARAM_INT);
  $query->bindParam(':sessionToken', $sessionToken, PDO::PARAM_STR);
  $query->execute();
  $role_id = $query->fetch(PDO::FETCH_OBJ)->role_id;

  if (($query->rowCount() == 0) || ($role_id != 2)) {
    header('location:logout.php');
    exit();
  }

  if ((strlen($_SESSION['sturecmsuid']) == 0) || (strlen($_COOKIE['uid']) == 0) || (strlen($_COOKIE['session_token']) == 0)) {
    header('location:logout.php');
    exit();
  } else {
    $uid = $_COOKIE['uid'] ?? '';
    $eid = $_GET['editid'];

    $sql = "SELECT * FROM tbltest, tblclass, tblteacher, tbltoken WHERE tbltest.ID=:eid and teacher_id=:uid AND tblteacher.ID=:uid AND tblclass.ID=tbltest.class_id AND tbltoken.UserID=:uid AND tbltoken.UserToken=:sessionToken AND (tbltoken.CreationTime + INTERVAL 2 HOUR) >= NOW()";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uid', $uid, PDO::PARAM_STR);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->bindParam(':sessionToken', $sessionToken, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() == 0) {
      header('location:manage-test.php');
      exit();
    }
  }

  if (isset($_POST['edit'])) {
    $eid = $_GET['editid'];
    $tname = $_POST['tname'];
    $stime = $_POST['stime'];
    $etime = $_POST['etime'];

    $sql = "update tbltest set TestName=:tname, StartTime=:stime, EndTime=:etime where ID=:eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':tname', $tname, PDO::PARAM_STR);
    $query->bindParam(':stime', $stime, PDO::PARAM_STR);
    $query->bindParam(':etime', $etime, PDO::PARAM_STR);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();
    echo '<script>alert("Test details have been updated")</script>';
  }

  if (isset($_POST['new_ques'])) {
    $eid = $_GET['editid'];
    $sql = "INSERT INTO tbltest_question(test_id, Question, AnsA, CorrectAns, Point) VALUES(:eid, 'Untitled Question', 'Untitled', 'A', 0)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Student Management System || Manage Test</title>
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
          <h3 class="page-title"> Manage Test </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="manage-test.php">Manage Tests</a></li>
              <li class="breadcrumb-item active" aria-current="page"> Test Details</li>
            </ol>
          </nav>
        </div>
        <div class="row">
          <div class="col-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <?php
                $uid = $_SESSION['sturecmsuid'];
                $eid = $_GET['editid'];
                $sql = "SELECT tbltest.*, ClassName, Room FROM tblclass, tbltest WHERE class_id=tblclass.ID AND tbltest.ID=:eid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;
                if ($query->rowCount() > 0) {
                  foreach ($results as $row) {
                    ?>
                    <h4 class="card-title" style="text-align: center;"> <?php echo htmlentities($row->TestName); ?> </h4>
                    <form class="forms-sample" method="post">
                      <div class="form-group">
                        <label for="exampleInputName1">Title</label>
                        <input type="text" name="tname"
                             value="<?php echo htmlentities($row->TestName); ?>"
                             class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Class</label>
                        <input type="text" name="cname"
                             value="<?php echo htmlentities($row->ClassName); ?>"
                             class="form-control" required='true' readonly>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Room</label>
                        <input type="text" name="room"
                             value="<?php echo htmlentities($row->Room); ?>"
                             class="form-control" required='true' readonly>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Start Time</label>
                        <input type="datetime-local" name="stime"
                             value="<?php echo htmlentities($row->StartTime); ?>"
                             class="form-control" required='true'>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">End Time</label>
                        <input type="datetime-local" name="etime"
                             value="<?php echo htmlentities($row->EndTime); ?>"
                             class="form-control" required='true'>
                      </div>
                      <?php $cnt = $cnt + 1;
                  }
                } ?>

                <button type="submit" class="btn btn-primary mr-2" name="edit">Edit Details
                </button>
                <a href="test-result.php?editid=<?php echo $eid; ?>" class="btn btn-primary">View Results</a>
              </form>
            </div>
          </div>

        </div>
      </div>
      <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <div class="d-sm-flex align-items-center mb-4">
                <h4 class="card-title mb-sm-0">Questions</h4>
              </div>
              <div class="table-responsive border rounded p-1">
                <table class="table">
                  <thead>
                  <tr>
                    <th class="font-weight-bold">No.</th>
                    <th class="font-weight-bold">Point</th>
                    <th class="font-weight-bold">Question</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  $sql = "SELECT * FROM tbltest_question WHERE test_id=:eid ORDER BY ID DESC";
                  $query = $dbh->prepare($sql);
                  $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                  $query->execute();
                  $results = $query->fetchAll(PDO::FETCH_OBJ);
                  $cnt = 1;
                  if ($query->rowCount() > 0) {
                    foreach ($results as $row) {
                      ?>
                      <tr>
                        <td><?php echo htmlentities($cnt); ?></td>
                        <td><?php echo htmlentities($row->Point); ?></td>
                        <td>
                          <a href="question-detail.php?editid=<?php echo htmlentities($row->ID); ?>">
                            <i class="icon-pencil"></i>
                            <?php echo htmlentities($row->Question); ?>
                          </a>
                        </td>
                      </tr>
                      <?php $cnt = $cnt + 1;
                    }
                  } ?>
                  </tbody>
                </table>
              </div>

              <div class="mt-4"></div>
              <form class="forms-sample" method="post">
                <button type="submit" class="btn btn-primary mr-2" name="new_ques">Add Question</button>
              </form>

            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
      <!-- partial:partials/_footer.html -->

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