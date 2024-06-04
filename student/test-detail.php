<?php
session_start();
include('includes/dbconnection.php');

$_SESSION['sturecmstuid'] = $_SESSION['sturecmsstuid'];

function updateTestPoint($dbh, $uid, $tid) {
  $sql = "SELECT ID, CorrectAns, Point, ChooseAns FROM tbltest_question q LEFT JOIN (SELECT * FROM tblstudent_question WHERE student_id=:uid) sq ON q.ID = sq.question_id WHERE test_id=:tid";
  $query = $dbh->prepare($sql);
  $query->bindParam(':uid', $uid, PDO::PARAM_STR);
  $query->bindParam(':tid', $tid, PDO::PARAM_STR);
  $query->execute();
  $results = $query->fetchAll(PDO::FETCH_OBJ);

  $point = 0;
  foreach ($results as $row) {
    if ($row->CorrectAns == $row->ChooseAns) {
      $point += $row->Point;
    }
  }

  $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
  $submittime = $currentDateTime->format('Y-m-d H:i:s');

  $sql = "UPDATE tblstudent_test SET SubmitTime=:submittime, TotalPoint=:point WHERE student_id=:uid AND test_id=:tid";
  $query = $dbh->prepare($sql);
  $query->bindParam(':submittime', $submittime, PDO::PARAM_STR);
  $query->bindParam(':point', $point, PDO::PARAM_INT);
  $query->bindParam(':uid', $uid, PDO::PARAM_STR);
  $query->bindParam(':tid', $tid, PDO::PARAM_STR);
  $query->execute();
}

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

  if (($query->rowCount() == 0) || ($role_id != 3)) {
    header('location:logout.php');
    exit();
  }

  if ((strlen($_SESSION['sturecmsuid']) == 0) || (strlen($_COOKIE['uid']) == 0) || (strlen($_COOKIE['session_token']) == 0)) {
    header('location:logout.php');
    exit();
  } else {
    $uid = $_COOKIE['uid'] ?? '';
    $eid = $_GET['editid'];

    $sql = "SELECT * from tbltest t, tblclass c, tblstudent_class sc, tbltoken where t.ID=:eid and sc.student_id=:uid and t.class_id=c.ID and c.ID=sc.class_id AND tbltoken.UserID=:uid AND tbltoken.UserToken=:sessionToken AND (tbltoken.CreationTime + INTERVAL 2 HOUR) >= NOW()";
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

  if (isset($_POST['start'])) {
    $uid = $_COOKIE['uid'] ?? '';
    $eid = $_GET['editid'];

    $sql = "INSERT INTO tblstudent_test (student_id, test_id, IP) VALUES (:uid, :eid, :ip)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uid', $uid, PDO::PARAM_STR);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->bindParam(':ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
    $query->execute();

    echo '<script>alert("Test started successfully.")</script>';
    echo "<script>window.location.href ='test.php?testid=$eid'</script>";
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
          <h3 class="page-title"> View Test </h3>
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
                $sql = "SELECT TestName, ClassName, CreationTime, tinfo.StartTime, tinfo.EndTime, Room, TotalPoint, st.StartTime StudentStartTime, st.SubmitTime, IP FROM (SELECT t.*, ClassName, Room FROM tblclass c, tbltest t WHERE t.class_id=c.ID AND t.ID=:eid) tinfo LEFT JOIN tblstudent_test st ON tinfo.ID=st.test_id AND st.student_id=:uid;";
                $query = $dbh->prepare($sql);
                $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                $query->bindParam(':uid', $uid, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;

                $hideSubmitTime = 'display: none;';
                $hideScore = 'display: none;';
                $btnStart = "Start Test";
                $stylebtnStart = '';
                $disablebtnStart = '';
                $stylebtnContinue = 'display: none; background-color: yellow; border-color: yellow; color: black;';

                $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
                $currentDateTime = $currentDateTime->format('Y-m-d H:i:s');
                $startTime = $results[0]->StartTime;
                $endTime = $results[0]->EndTime;

                if ($currentDateTime < $startTime || $currentDateTime > $endTime) {
                  $stylebtnStart = 'background-color: gray; border-color: gray';
                  $disablebtnStart = 'disabled';
                  if ($currentDateTime < $startTime) {
                    $btnStart = "Test Not Started";
                  } else {
                    $btnStart = "Test Ended";
                    if ($results[0]->StudentStartTime!=Null) {
                      if ($results[0]->SubmitTime==Null) {
                        updateTestPoint($dbh, $uid, $eid);
                      }
                    }
                  }
                } else {
                  if ($results[0]->StudentStartTime!=Null) {
                    if ($results[0]->SubmitTime!=Null) {
                      $stylebtnStart = 'background-color: gray; border-color: gray';
                      $disablebtnStart = 'disabled';
                      $btnStart = "Test Submitted";
                    } else {
                      $stylebtnStart = 'display: none;';
                      $disablebtnStart = 'disabled';
                      $stylebtnContinue = 'background-color: yellow; border-color: yellow; color: black;';
                    }
                  }
                }

                if ($results[0]->StudentStartTime!=Null) {
                  if ($results[0]->SubmitTime!=Null) {
                    $hideSubmitTime = '';
                    $hideScore = '';
                  } 
                }

                if ($query->rowCount() > 0) {
                  foreach ($results as $row) {
                    ?>
                    <h4 class="card-title" style="text-align: center;"> <?php echo htmlentities($row->TestName); ?> </h4>
                    <form class="forms-sample" method="post">
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
                             class="form-control" required='true' readonly>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">End Time</label>
                        <input type="datetime-local" name="etime"
                             value="<?php echo htmlentities($row->EndTime); ?>"
                             class="form-control" required='true' readonly>
                      </div>
                      <div class="form-group" style="<?php echo $hideSubmitTime ?>">
                        <label for="exampleInputName1">Submit Time</label>
                        <input type="datetime-local" name="subtime" 
                               value="<?php echo htmlentities($row->SubmitTime); ?>"
                               class="form-control" required='true' readonly>
                      </div>
                      <div class="form-group" style="<?php echo $hideScore ?>">
                        <label for="exampleInputName1">Score</label>
                        <input type="text" name="point" 
                               value="<?php echo htmlentities($row->TotalPoint); ?>"
                               class="form-control" required='true' readonly>
                      </div>
                      <?php $cnt = $cnt + 1;
                  }
                } ?>
                <div class="text-center">
                      <button type="submit" class="btn btn-primary mr-2" name="start" style="<?php echo $stylebtnStart; ?>" <?php echo $disablebtnStart; ?>><?php echo $btnStart; ?></button>
                </div>
                <div class="text-center">
                      <a href="<?php echo ('test.php?testid=' . $eid); ?>" class="btn btn-primary mr-2" style="<?php echo $stylebtnContinue ?>">Continue Test</a>
                </div>
                
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