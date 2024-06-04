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
    $correct_ans = $_POST['correct_ans'];
    $ansA = $_POST['ansA'];
    if ($ansA == '') $ansA = "Untitled";
    $ansB = $_POST['ansB'];
    if ($ansB == '') $ansB = null;
    $ansC = $_POST['ansC'];
    if ($ansC == '') $ansC = null;
    $ansD = $_POST['ansD'];
    if ($ansD == '') $ansD = null;

    $sql = "UPDATE tbltest_question SET Question=:qname, Point=:point, CorrectAns=:correct_ans, AnsA=:ansA, AnsB=:ansB, AnsC=:ansC, AnsD=:ansD WHERE ID=:eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':qname', $qname, PDO::PARAM_STR);
    $query->bindParam(':point', $point, PDO::PARAM_STR);
    $query->bindParam(':correct_ans', $correct_ans, PDO::PARAM_STR);
    $query->bindParam(':ansA', $ansA, PDO::PARAM_STR);
    $query->bindParam(':ansB', $ansB, PDO::PARAM_STR);
    $query->bindParam(':ansC', $ansC, PDO::PARAM_STR);
    $query->bindParam(':ansD', $ansD, PDO::PARAM_STR);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();

    echo '<script>alert("Question has been updated")</script>';
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
                        <label for="exampleInputName1">Correct Answer</label>
                        <select name="correct_ans" class="form-control" required='true'>
                          <option value="A" <?php if ($row->CorrectAns == 'A') {
                            echo 'selected';
                          } ?>>A
                          </option>
                          <option value="B" <?php if ($row->CorrectAns == 'B') {
                            echo 'selected';
                          } ?>>B
                          </option>
                          <option value="C" <?php if ($row->CorrectAns == 'C') {
                            echo 'selected';
                          } ?>>C
                          </option>
                          <option value="D" <?php if ($row->CorrectAns == 'D') {
                            echo 'selected';
                          } ?>>D
                          </option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Answer A</label>
                        <input type="text" name="ansA"
                             value="<?php echo htmlentities($row->AnsA); ?>"
                             class="form-control" required='true' placeholder="Add answer">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Answer B</label>
                        <input type="text" name="ansB"
                             value="<?php echo htmlentities($row->AnsB); ?>"
                             class="form-control" placeholder="Add answer">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Answer C</label>
                        <input type="text" name="ansC"
                             value="<?php echo htmlentities($row->AnsC); ?>"
                             class="form-control" placeholder="Add answer">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputName1">Answer D</label>
                        <input type="text" name="ansD"
                             value="<?php echo htmlentities($row->AnsD); ?>"
                             class="form-control" placeholder="Add answer">
                      </div>
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