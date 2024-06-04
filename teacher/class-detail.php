<?php
session_start();
include('includes/dbconnection.php');
// Check if the user is logged in and the session variables are set
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

  }}
    // Token is valid, continue to the dashboard  

function getRandomStringShuffle($length = 43)
{
    $stringSpace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $stringLength = strlen($stringSpace);
    $string = str_repeat($stringSpace, ceil($length / $stringLength));
    $shuffledString = str_shuffle($string);
    $randomString = substr($shuffledString, 1, $length);
    return $randomString;
}

if ((strlen($_SESSION['sturecmsuid']) == 0) || (strlen($_COOKIE['uid']) == 0) || (strlen($_COOKIE['session_token']) == 0)){
  header('location:logout.php');
  exit();
} else {
  $uid = $_COOKIE['uid'] ?? '';
  $eid = $_GET['editid'];
  #check if the class belongs to the teacher in tblclass and check the teacher has a valid token in tbltoken
  $sql = "SELECT * FROM tblclass, tblteacher, tbltoken WHERE teacher_id=:uid AND tblteacher.ID=:uid AND tblclass.ID=:eid AND tbltoken.UserID=:uid AND tbltoken.UserToken=:sessionToken AND (tbltoken.CreationTime + INTERVAL 2 HOUR) >= NOW()";
  $query = $dbh->prepare($sql);
  $query->bindParam(':uid',$uid,PDO::PARAM_STR);
  $query->bindParam(':eid',$eid,PDO::PARAM_STR);
  $query->bindParam(':sessionToken', $sessionToken, PDO::PARAM_STR);
  $query->execute();
  $results = $query->fetchAll(PDO::FETCH_OBJ);
  if ($query->rowCount() == 0) {
    header('location:manage-class.php');
    exit();
  }
  
  if (isset($_POST['genqr'])) {
    $aid = $_POST['attendance_id'];

    // Generate QR code
    include_once('../phpqrcode/qrlib.php');
    $tempDir = 'temp/';

    $qrContent = getRandomStringShuffle();
    $qrImgName = "qrImg.png";
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $gentime = date('Y-m-d H:i:s');
    $sql = "UPDATE tblattendance SET Secret=:qrContent, LastGeneratedTime=:gentime WHERE ID=:aid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':aid', $aid, PDO::PARAM_STR);
    $query->bindParam(':qrContent', $qrContent, PDO::PARAM_STR);
    $query->bindParam(':gentime', $gentime, PDO::PARAM_STR);
    $query->execute();
    $pngAbsoluteFilePath = $tempDir.$qrImgName;
    QRcode::png($qrContent, $pngAbsoluteFilePath, QR_ECLEVEL_L, 10, 10);
    // echo "<div style='display: flex; justify-content: center; align-items: center; height: 100vh;'>";
    // echo "<img src='".$pngAbsoluteFilePath."'>";
    // echo "</div>";
    // echo "<div style='display: flex; justify-content: center; align-items: center; height: 100vh;'>";
    // echo "<br>Using shuffle(): " . getRandomStringShuffle();
    // echo "</div>";
    echo "<script>window.open('".$pngAbsoluteFilePath."');</script>";
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

  if (isset($_POST['new_attendance'])) {
    $eid = $_GET['editid'];
    $sql = "insert into tblattendance (class_id) values (:eid)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();
    echo '<script>alert("New attendance record has been created")</script>';
  }

  if (isset($_POST['delete_attendance'])) {
    $aid = $_POST['attendance_id'];
    $sql = "delete from tblattendance where ID=:aid";
    $sql2 = "delete from tblstudent_attendance where attendance_id=:aid";
    $query = $dbh->prepare($sql);
    $query2 = $dbh->prepare($sql2);
    $query->bindParam(':aid', $aid, PDO::PARAM_STR);
    $query2->bindParam(':aid', $aid, PDO::PARAM_STR);
    $query->execute();
    $query2->execute();
    echo '<script>alert("Attendance record has been deleted")</script>';
  }
}
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
              <li class="breadcrumb-item"><a href="dashboard.php">Manage Class</a></li>
              <li class="breadcrumb-item active" aria-current="page"> Class Details</li>
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
                  $sql = "SELECT * FROM tblclass, tblteacher WHERE teacher_id=:uid AND tblteacher.ID=:uid AND tblclass.ID=$eid";
                  $query = $dbh->prepare($sql);
                  $query->bindParam(':uid',$uid,PDO::PARAM_STR);
                  $query->execute();
                  $results = $query->fetchAll(PDO::FETCH_OBJ);
                  $cnt = 1; 
                  if ($query->rowCount() > 0) {
                    foreach ($results as $row) {
                      ?>
                <h4 class="card-title" style="text-align: center;"> <?php echo htmlentities($row->ClassName); ?> </h4>
                <form class="forms-sample" method="post">
                      <div class="form-group">
                        <label for="exampleInputName1">Room</label>
                        <input type="text" name="room"
                             value="<?php echo htmlentities($row->Room); ?>"
                             class="form-control" required='true', readonly>
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
                  
                  <button type="submit" class="btn btn-primary mr-2" name="regencode">Change Code
                  </button>
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
                    <h4 class="card-title mb-sm-0">Attendances</h4>
                    <a href="#" class="text-dark ml-auto mb-3 mb-sm-0"> </a>
                  </div>
                  <div class="table-responsive border rounded p-1">
                    <table class="table">
                      <thead>
                        <tr>
                          <th class="font-weight-bold">No.</th>
                          <th class="font-weight-bold">Time</th>
                          <th class="font-weight-bold">Student</th>
                          <th class="font-weight-bold"></th>
                          <th class="font-weight-bold">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        if (isset($_GET['pageno'])) {
                          $pageno = $_GET['pageno'];
                        } else {
                          $pageno = 1;
                        }
                        // Formula for pagination
                        $eid = $_GET['editid'];
                        $sql = "SELECT tblattendance.* from tblattendance where class_id=:eid ORDER BY CreationTime ASC";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':eid',$eid,PDO::PARAM_STR);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                        $cnt = 1;
                        if ($query->rowCount() > 0) {
                          foreach ($results as $row) {
                        ?>
                            <tr>
                              <td><?php echo htmlentities($cnt); ?></td>
                              <td><?php echo htmlentities($row->CreationTime); ?></td>
                              <td>
                                <?php
                                $aid = $row->ID;
                                $sql1 = "SELECT * from tblstudent_attendance where attendance_id=:aid";
                                $query1 = $dbh->prepare($sql1);
                                $query1->bindParam(':aid', $aid, PDO::PARAM_STR);
                                $query1->execute();
                                $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
                                $totalstudent = $query1->rowCount();
                                echo htmlentities($totalstudent);
                                ?>
                              </td>
                              <td>
                                <td >
                                  <form class="forms-sample" method="post">
                                    <input type="hidden" name="attendance_id" value="<?php echo htmlentities($aid); ?>">
                                    <button type="submit" class="btn btn-sm btn-primary mr-2" style="background-color: gray; width: 100pt;" name="genqr">Gen QR</button>
                                  </form>
                                  <form class="forms-sample" method="post">
                                    <input type="hidden" name="attendance_id" value="<?php echo htmlentities($aid); ?>">
                                    <button type="submit" class="btn btn-sm btn-primary mr-2" name="delete_attendance" style="background-color: red; border-color: red; width: 100pt;">Delete</button>
                                  </form>
                                </td>
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
                  <button type="submit" class="btn btn-primary mr-2" name="new_attendance">New Attendance record</button>
                  </form>
                    

                    
                </div>
              </div>
            </div>
          </div>
      </div>
        <div class="row" style="margin-left: 20px; ">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
          <div class="card-body">
            <div class="d-sm-flex align-items-center mb-4">
              <h4 class="card-title mb-sm-0">Tests</h4>
              <a href="#" class="text-dark ml-auto mb-3 mb-sm-0"> </a>
            </div>
            <div class="table-responsive border rounded p-1">
              <table class="table">
                <thead>
            <tr>
              <th class="font-weight-bold">No.</th>
              <th class="font-weight-bold">Title</th>
              <th class="font-weight-bold">Start Time</th>
              <th class="font-weight-bold">End Time</th>
              <th class="font-weight-bold">Submitted</th>
              <th class="font-weight-bold">Average Score</th>
            </tr>
                </thead>
                <tbody>
            <?php

            $sql = "SELECT tbltest.* from tbltest where class_id=:eid ORDER BY StartTime DESC";
            $query = $dbh->prepare($sql);
            $query->bindParam(':eid',$eid,PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);

            $cnt = 1;
            if ($query->rowCount() > 0) {
              foreach ($results as $row) {
            ?>
                <tr>
                  <td><?php echo htmlentities($cnt); ?></td>
                  <td><a href="test-detail.php?editid=<?php echo htmlentities($row->ID); ?>"><?php echo htmlentities($row->TestName); ?></a></td>
                  <td><?php echo htmlentities($row->StartTime); ?></td>
                  <td><?php echo htmlentities($row->EndTime); ?></td>
                  <td>
              <?php
              $tid = $row->ID;
              $sql1 = "SELECT * from tblstudent_test where test_id=:tid and SubmitTime is not Null";
              $query1 = $dbh->prepare($sql1);
              $query1->bindParam(':tid', $tid, PDO::PARAM_STR);
              $query1->execute();
              $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
              $totalstudent = $query1->rowCount();
              echo htmlentities($totalstudent);
              ?>
                  </td>
                  <td>
                  <?php
                  $tid = $row->ID;
                  $sql1 = "SELECT ROUND(AVG(TotalPoint),2) as average from tblstudent_test where test_id=:tid and SubmitTime is not Null";
                  $query1 = $dbh->prepare($sql1);
                  $query1->bindParam(':tid', $tid, PDO::PARAM_STR);
                  $query1->execute();
                  $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
                  if ($results1[0]->average != Null) {
                    echo htmlentities($results1[0]->average);
                  } else {
                    echo "N/A";
                  }
              ?>
                  </td>
                </tr>
            <?php $cnt = $cnt + 1;
              }
            } ?>
                </tbody>
              </table>
            </div>

              
          </div>
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