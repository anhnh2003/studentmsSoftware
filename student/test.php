<?php
session_start();
$_SESSION['sturecmstuid'] = $_SESSION['sturecmsstuid'];
include('includes/dbconnection.php');


function updateTestPoint($dbh, $uid, $tid) {
  $sql = "SELECT ID,Step1Sol,Step2Sol,Step3Sol,Step4Sol,Step5Sol,Step1, Step2, Step3, Step4, Step5, Point FROM tbltest_question q LEFT JOIN (SELECT * FROM tblstudent_question WHERE student_id=:uid) sq ON q.ID = sq.question_id WHERE test_id=:tid";
  $query = $dbh->prepare($sql);
  $query->bindParam(':uid', $uid, PDO::PARAM_STR);
  $query->bindParam(':tid', $tid, PDO::PARAM_STR);
  $query->execute();
  $results = $query->fetchAll(PDO::FETCH_OBJ);

  $point = 0;
  foreach ($results as $row) {
    if ($row->Step1 == $row->Step1Sol && $row->Step2 == $row->Step2Sol && $row->Step3 == $row->Step3Sol && $row->Step4 == $row->Step4Sol && $row->Step5 == $row->Step5Sol) {
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
    $tid = $_GET['testid'];

    $sql = "SELECT * from tbltest t, tblclass c, tblstudent_class sc, tbltoken where t.ID=:tid and sc.student_id=:uid and t.class_id=c.ID and c.ID=sc.class_id AND tbltoken.UserID=:uid AND tbltoken.UserToken=:sessionToken AND (tbltoken.CreationTime + INTERVAL 2 HOUR) >= NOW()";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uid', $uid, PDO::PARAM_STR);
    $query->bindParam(':tid', $tid, PDO::PARAM_STR);
    $query->bindParam(':sessionToken', $sessionToken, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() == 0) {
      header('location:manage-test.php');
      exit();
    }
    //check if student has already started the test


    $sql = "SELECT * from tblstudent_test where student_id=:uid and test_id=:tid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uid', $uid, PDO::PARAM_STR);
    $query->bindParam(':tid', $tid, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() == 0) {
      header('location:test-detail.php?editid=' . $tid);
      exit();
    } else {
      if ($results[0]->SubmitTime != Null || $results[0]->TotalPoint != Null) {
        echo '<script>alert("Test has been submitted.")</script>';
        echo '<script>window.location.replace("test-detail.php?editid=' . $tid. '")</script>';
        exit();
      } else {
        $sql = "SELECT StartTime, EndTime FROM tbltest WHERE ID=:tid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':tid', $tid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        $startTime = $results[0]->StartTime;
        $endTime = $results[0]->EndTime;
        $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
        $currentDateTime = $currentDateTime->format('Y-m-d H:i:s');

        if ($currentDateTime < $startTime || $currentDateTime > $endTime) {
          if ($currentDateTime > $endTime) {
            $uid = $_COOKIE['uid'] ?? '';
            $tid = $_GET['testid'];
            //updateTestPoint($dbh, $uid, $tid);
          }
          echo '<script>alert("Test has not started or ended.")</script>';
          echo '<script>window.location.replace("test-detail.php?editid=' . $tid. '")</script>';
          exit();
        }
      }
    }

    if (isset($_POST['submit'])) {
      $uid = $_COOKIE['uid'] ?? '';
      $tid = $_GET['testid'];
      updateTestPoint($dbh, $uid, $tid);

      echo '<script>alert("Test has been submitted.")</script>';
      echo '<script>window.location.replace("test-detail.php?editid=' . $tid. '")</script>';
      exit();
    }
  

    if (isset($_POST['choose'])) {
      $uid = $_COOKIE['uid'] ?? '';
      $tid = $_GET['testid'];

      if ($results[0]->TotalPoint != Null) {
        echo '<script>alert("Test has been submitted.")</script>';
        echo '<script>window.location.replace("test-detail.php?editid=' . $tid. '")</script>';
        exit();
      }

      $sql = "SELECT EndTime FROM tbltest WHERE ID=:tid";
      $query = $dbh->prepare($sql);
      $query->bindParam(':tid', $tid, PDO::PARAM_STR);
      $query->execute();
      $results = $query->fetchAll(PDO::FETCH_OBJ);

      $endTime = $results[0]->EndTime;
      $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
      $currentDateTime = $currentDateTime->format('Y-m-d H:i:s');
      if ($currentDateTime > $endTime) {
        updateTestPoint($dbh, $uid, $tid);
        echo '<script>alert("Test has ended and auto-submitted!")</script>';
        echo '<script>window.location.replace("test-detail.php?editid=' . $tid. '")</script>';
        exit();
      } else {
        $qid = $_POST['qid'];
        $step1 = $_POST['Step1Sol'];
        $step2 = $_POST['Step2Sol'];
        if($step2==""){
          $step2 = null;
        }
        $step3 = $_POST['Step3Sol'];
        $step4 = $_POST['Step4Sol'];
        $step5 = $_POST['Step5Sol'];
        if($step5==""){
          $step5 = null;
        }
        if($step4==""){
          $step4 = null;
        }
        if($step3==""){
          $step3 = null;
        }
        if($step1==""){
          $step1 = null;
        }


        $sql = "SELECT * from tblstudent_question where student_id=:uid and question_id=:qid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':uid', $uid, PDO::PARAM_STR);
        $query->bindParam(':qid', $qid, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() == 0) {
          $sql = "INSERT INTO tblstudent_question (student_id, question_id, Step1, Step2, Step3, Step4, Step5) VALUES (:uid, :qid, :step1, :step2, :step3, :step4, :step5)";
          $query = $dbh->prepare($sql);
          $query->bindParam(':uid', $uid, PDO::PARAM_STR);
          $query->bindParam(':qid', $qid, PDO::PARAM_STR);
          $query->bindParam(':step1', $step1, PDO::PARAM_STR);
          $query->bindParam(':step2', $step2, PDO::PARAM_STR);
          $query->bindParam(':step3', $step3, PDO::PARAM_STR);
          $query->bindParam(':step4', $step4, PDO::PARAM_STR);
          $query->bindParam(':step5', $step5, PDO::PARAM_STR);
          $query->execute();
        } else {
          $sql = "UPDATE tblstudent_question SET Step1=:step1, Step2=:step2, Step3=:step3,Step4=:step4,Step5=:step5  WHERE student_id=:uid AND question_id=:qid";
          $query = $dbh->prepare($sql);
          $query->bindParam(':step1', $step1, PDO::PARAM_STR);
          $query->bindParam(':step2', $step2, PDO::PARAM_STR);
          $query->bindParam(':step3', $step3, PDO::PARAM_STR);
          $query->bindParam(':step4', $step4, PDO::PARAM_STR);
          $query->bindParam(':step5', $step5, PDO::PARAM_STR);
          $query->bindParam(':uid', $uid, PDO::PARAM_STR);
          $query->bindParam(':qid', $qid, PDO::PARAM_STR);
          $query->execute();
        }
        header('location:test.php?testid=' . $tid);
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>MathEx || Testing</title>
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
    <?php
      $uid = $_SESSION['sturecmsuid'];
      $tid = $_GET['testid'];
      $sql = "SELECT TestName, ClassName, CreationTime, tinfo.StartTime, tinfo.EndTime, Room, TotalPoint, st.StartTime StudentStartTime, st.SubmitTime, IP FROM (SELECT t.*, ClassName, Room FROM tblclass c, tbltest t WHERE t.class_id=c.ID AND t.ID=:tid) tinfo LEFT JOIN tblstudent_test st ON tinfo.ID=st.test_id AND st.student_id=:uid;";
      $query = $dbh->prepare($sql);
      $query->bindParam(':tid', $tid, PDO::PARAM_STR);
      $query->bindParam(':uid', $uid, PDO::PARAM_STR);
      $query->execute();
      $results = $query->fetchAll(PDO::FETCH_OBJ);
    ?>
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="page-header">
          <h3 class="page-title"> <?php echo htmlentities($results[0]->TestName);?> </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">End Time: <b><?php echo htmlentities($results[0]->EndTime);?> </b></a></li>
            </ol>
          </nav>
        </div>
                <?php
                $sql = "SELECT ID, Question, Step1Des, Step2Des, Step3Des,Step4Des,Step5Des, Point, Step1, Step2, Step3, Step4, Step5 FROM tbltest_question q LEFT JOIN (SELECT * FROM tblstudent_question WHERE student_id=:uid) sq ON q.ID = sq.question_id WHERE test_id=:tid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':uid', $uid, PDO::PARAM_STR);
                $query->bindParam(':tid', $tid, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                
                $cnt = 1;
                if ($query->rowCount() > 0) {
                  foreach ($results as $row) {
    
                    ?>
                    <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                    <div class="card-body">
                    <h4 class="card-title" style="text-align: left;"> Question <?php echo htmlentities($cnt); ?>: </h4>
                    <form class="forms-sample" method="post">
                      <input type="hidden" name="qid" value="<?php echo htmlentities($row->ID); ?>">
                        <div class="form-group">
                        <textarea name="qname" rows="<?php echo ceil(strlen($row->Question) / 50); ?>" class="form-control" readonly style="background-color: white;"><?php echo htmlentities($row->Question); ?></textarea>
                        </div>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
    <?php if (!is_null($row->{"Step{$i}Des"}) && $row->{"Step{$i}Des"} != ''): ?>
        <div class="form-group">
            <label for="exampleInputName1">Step <?php echo $i; ?> Description</label>
            <textarea name="step<?php echo $i; ?>des" rows="<?php echo ceil(strlen($row->{"Step{$i}Des"}) / 50); ?>" class="form-control" readonly style="background-color: white;"><?php echo htmlentities($row->{"Step{$i}Des"}); ?></textarea>
        </div>
    <?php endif; ?>
    <?php if (!is_null($row->{"Step{$i}Des"}) && $row->{"Step{$i}Des"} != ''): ?>
        <div class="form-group">
            <label for="exampleInputName1">Step <?php echo $i; ?> Solution</label>
            <textarea name="Step<?php echo $i; ?>Sol" rows=8 class="form-control"><?php echo htmlentities($row->{"Step{$i}"}); ?></textarea>
        </div>
    <?php endif; ?>
<?php endfor; ?>
                      <div class="text-center">
                      <button type="submit" class="btn btn-primary mr-2" name="choose">Save</button>
                      </div>
                    </form>
                    </div>
                    </div>
                    </div>
                    </div>
                      
                      <?php $cnt = $cnt + 1;
                  }
                } ?>
                  <form class="forms-sample" method="post">
                    <div class="text-center">
                      <button type="submit" class="btn btn-primary mr-2" style="background-color:red; border-color:red;" name="submit">Submit</button>
                      </div>
                  </form>
        
      <!-- content-wrapper ends -->
      <!-- partial:partials/_footer.html -->

      <!-- partial -->
    </div>
    <!-- main-panel ends -->
  </div>
  <!-- page-body-wrapper ends -->
</div>
<script src="../js/inputValidation.js"></script>
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