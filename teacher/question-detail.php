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

    $sql = "SELECT * FROM tbltest_question, tbltest, tblclass, tblteacher, tbltoken WHERE tbltest.ID=test_id and tbltest_question.ID=:eid and teacher_id=:uid AND tblteacher.ID=:uid AND tblclass.ID=tbltest.class_id AND tbltoken.UserID=:uid AND tbltoken.UserToken=:sessionToken AND (tbltoken.CreationTime + INTERVAL 2 HOUR) >= NOW()";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uid', $uid, PDO::PARAM_STR);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->bindParam(':sessionToken', $sessionToken, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    $tid = $results[0]->test_id;

    if ($query->rowCount() == 0) {
      header('location:manage-test.php');
      exit();
    }
  }

  if (isset($_POST['edit'])) {
    $eid = $_GET['editid'];
    $qname = $_POST['qname'];
    $point = $_POST['point'];
    $step1Des = $_POST['Step1Des'];
    if ($step1Des== '') $step1Des = "Untitled";
    $step2Des = $_POST['Step2Des'];
    if ($step2Des == '') $step2Des = null;
    $step3Des = $_POST['Step3Des'];
    if ($step3Des == '') $step3Des = null;
    $step4Des = $_POST['Step4Des'];
    if ($step4Des == '') $step4Des = null;
    $step5Des = $_POST['Step5Des'];
    if ($step5Des == '') $step5Des = null;
    $step1Sol = $_POST['Step1Sol'];
    if ($step1Sol == '') $step1Sol = null;
    $step2Sol = $_POST['Step2Sol'];
    if ($step2Sol == '') $step2Sol = null;
    $step3Sol = $_POST['Step3Sol'];
    if ($step3Sol == '') $step3Sol = null;
    $step4Sol = $_POST['Step4Sol'];
    if ($step4Sol == '') $step4Sol = null;
    $step5Sol = $_POST['Step5Sol'];
    if ($step5Sol == '') $step5Sol = null;


    $sql = "UPDATE tbltest_question SET Question=:qname, Point=:point   , Step1Des=:Step1Des, Step2Des=:Step2Des, Step3Des=:Step3Des, Step4Des=:Step4Des, Step5Des=:Step5Des,Step1Sol=:Step1Sol, Step2Sol=:Step2Sol, Step3Sol=:Step3Sol, Step4Sol=:Step4Sol, Step5Sol=:Step5Sol WHERE ID=:eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':qname', $qname, PDO::PARAM_STR);
    $query->bindParam(':point', $point, PDO::PARAM_STR);
    $query->bindParam(':Step1Des', $step1Des, PDO::PARAM_STR);
    $query->bindParam(':Step2Des', $step2Des, PDO::PARAM_STR);
    $query->bindParam(':Step3Des', $step3Des, PDO::PARAM_STR);
    $query->bindParam(':Step4Des', $step4Des, PDO::PARAM_STR);
    $query->bindParam(':Step5Des', $step5Des, PDO::PARAM_STR);
    $query->bindParam(':Step1Sol', $step1Sol, PDO::PARAM_STR);
    $query->bindParam(':Step2Sol', $step2Sol, PDO::PARAM_STR);
    $query->bindParam(':Step3Sol', $step3Sol, PDO::PARAM_STR);
    $query->bindParam(':Step4Sol', $step4Sol, PDO::PARAM_STR);
    $query->bindParam(':Step5Sol', $step5Sol, PDO::PARAM_STR);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();

    echo '<script>alert("Question has been updated")</script>';
    echo "<script>window.location.href ='test-detail.php?editid=$tid'</script>";
  }

  if (isset($_POST['delete'])) {
    $eid = $_GET['editid'];

    $sql = "DELETE FROM tbltest_question WHERE ID=:eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();

    echo '<script>alert("Question has been deleted")</script>';
    echo "<script>window.location.href ='test-detail.php?editid=$tid'</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Student Management System || Edit Question</title>
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
          <h3 class="page-title"> Edit Question </h3>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="test-detail.php?editid=<?php echo htmlentities($tid); ?>">Test Details</a></li>
              <li class="breadcrumb-item active" aria-current="page"> Edit Test Question</li>
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
                $sql = "SELECT tq.*, TestName FROM tbltest_question tq, tbltest t WHERE test_id=t.ID AND tq.ID=:eid";
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
                          <label for="exampleInputName1">Question</label>
                          <textarea name="qname" class="form-control" rows="10" required='true'><?php echo htmlentities($row->Question); ?></textarea>
                        </div>
                        <div class="form-group">
                        <label for="exampleInputName1">Point</label>
                        <input type="text" name="point"
                             value="<?php echo htmlentities($row->Point); ?>"
                             class="form-control" required='true' pattern="[0-9]+">
                      </div>
                    
                        <div class="form-group">
                        <label for="exampleInputName1">Step 1 Description</label>
                        <textarea name="Step1Des" rows=8 class="form-control" oninput="checkInputLength(this)" required='true' placeholder="Add a detail of what the students are expected to do in this step"><?php echo htmlentities($row->Step1Des); ?></textarea>
                        </div>
                        <div class="form-group">
                        <label for="exampleInputName1">Step 1 Solution</label>
                        <textarea name="Step1Sol" rows=8 class="form-control" required='true' oninput="checkInputLength(this)"><?php echo htmlentities($row->Step1Sol); ?></textarea>
                        </div>
                        <div class="form-group">
                        <label for="exampleInputName1">Step 2 Description</label>
                        <textarea name="Step2Des" rows=8 class="form-control" placeholder="Add a detail of what the students are expected to do in this step" oninput="checkInputLength(this)" ><?php echo htmlentities($row->Step2Des); ?></textarea>
                        </div>
                        <div class="form-group">
                        <label for="exampleInputName1">Step 2 Solution</label>
                        <textarea name="Step2Sol" rows=8 class="form-control" oninput="checkInputLength(this)"><?php echo htmlentities($row->Step2Sol); ?></textarea>
                        </div>
                        <div class="form-group">
                        <label for="exampleInputName1">Step 3 Description</label>
                        <textarea name="Step3Des" rows=8 class="form-control" placeholder="Add a detail of what the students are expected to do in this step" oninput="checkInputLength(this)"><?php echo htmlentities($row->Step3Des); ?></textarea>
                        </div>
                        <div class="form-group">
                        <label for="exampleInputName1">Step 3 Solution</label>
                        <textarea name="Step3Sol" rows=8 class="form-control" oninput="checkInputLength(this)"><?php echo htmlentities($row->Step3Sol); ?></textarea>
                        </div>
                        <div class="form-group">
                        <label for="exampleInputName1">Step 4 Description</label>
                        <textarea name="Step4Des" rows=8 class="form-control" placeholder="Add a detail of what the students are expected to do in this step" oninput="checkInputLength(this)"><?php echo htmlentities($row->Step4Des); ?></textarea>
                        </div>
                        <div class="form-group">
                        <label for="exampleInputName1">Step 4 Solution</label>
                        <textarea name="Step4Sol" rows=8 class="form-control"  placeholder="Add a detail of what the students are expected to do in this step" oninput="checkInputLength(this)"><?php echo htmlentities($row->Step4Sol); ?></textarea>
                        </div>
                        <div class="form-group">
                        <label for="exampleInputName1">Step 5 Description</label>
                        <textarea name="Step5Des" rows=8 class="form-control" placeholder="Add a detail of what the students are expected to do in this step" oninput="checkInputLength(this)"><?php echo htmlentities($row->Step5Des); ?></textarea>
                        </div>
                        <div class="form-group">
                        <label for="exampleInputName1">Step 5 Solution</label>
                        <textarea name="Step5Sol" rows=8 class="form-control"  placeholder="Add a detail of what the students are expected to do in this step" oninput="checkInputLength(this)"><?php echo htmlentities($row->Step5Sol); ?></textarea>
                        </div>
                        <script>
                        function checkInputLength(input) {
                            if (input.value.length > 255) {
                                input.setCustomValidity('Input must be less than 255 characters');
                            } else {
                                input.setCustomValidity('');
                            }
                        }
                        </script>
                      
                      <?php $cnt = $cnt + 1; 
                  }
                } ?>

                <button type="submit" class="btn btn-primary mr-2" name="edit">Edit Details
                </button>
                <button type="submit" class="btn btn-primary mr-2" name="delete" style="background-color: red; border-color: red; color: white;">Delete</button>
                </button>
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