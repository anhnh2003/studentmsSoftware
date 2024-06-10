<?php
session_start();
//error_reporting(0);
include('includes/dbconnection.php');
// Check if the user is logged in and the session variables are set
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
  if (($query->rowCount() == 0) || ($role_id != 3)) {
      // Token is invalid or expired, redirect to logout
      header('location:logout.php');
      exit();
    }
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>MathEx || My Test</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="./vendors/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="./vendors/chartist/chartist.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <!-- endinject -->
  <!-- Layout styles -->
  <link rel="stylesheet" href="./css/style.css">
  <!-- End layout styles -->
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
            <h3 class="page-title"> My Test </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page"> View Tests</li>
              </ol>
            </nav>
          </div>
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-sm-flex align-items-center mb-4">
                    <h4 class="card-title mb-sm-0">My Test</h4>
                    <a href="#" class="text-dark ml-auto mb-3 mb-sm-0"> View all Tests</a>
                  </div>
                  <div class="table-responsive border rounded p-1">
                    <table class="table">
                      <thead>
                        <tr>
                          <th class="font-weight-bold">No.</th>
                          <th class="font-weight-bold">Title</th>
                          <th class="font-weight-bold">Class</th>
                          <th class="font-weight-bold">Start Time</th>
                          <th class="font-weight-bold">Submit Time</th>
                          <th class="font-weight-bold">Score</th>
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
                        if ((strlen($_SESSION['sturecmsuid']) == 0) || (strlen($_COOKIE['uid']) == 0) || (strlen($_COOKIE['session_token']) == 0)){
                            header('location:logout.php');
                            exit();
                          } else {
                            $uid = $_COOKIE['uid'] ?? '';
                            $sessionToken = $_COOKIE['session_token'] ?? '';
                        $no_of_records_per_page = 15;
                        $offset = ($pageno - 1) * $no_of_records_per_page;
                        $ret = "SELECT * from tbltest t, tblclass c, tblstudent_class sc where sc.student_id=:uid and t.class_id=c.ID and c.ID=sc.class_id";
                        $query1 = $dbh->prepare($ret);
                        $query1->bindParam(':uid',$uid,PDO::PARAM_STR);
                        $query1->execute();
                        $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
                        $total_rows = $query1->rowCount();
                        $total_pages = ceil($total_rows / $no_of_records_per_page);
                        #$sql = "SELECT tblclass.* from tblclass where teacher_id=:uid ORDER BY tblclass.CreationTime DESC LIMIT $offset, $no_of_records_per_page";
                        #query all classes belongs to the teacher in tblclass and check the teacher has a valid token in tbltoken
                        $sql = "SELECT t.*, c.ClassName from tbltest t, tblclass c, tblstudent_class sc, tbltoken where sc.student_id=:uid and t.class_id=c.ID and c.ID=sc.class_id AND tbltoken.UserToken = :sessionToken AND (tbltoken.CreationTime + INTERVAL 2 HOUR) >= NOW() ORDER BY t.StartTime DESC LIMIT $offset, $no_of_records_per_page";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':uid',$uid,PDO::PARAM_STR);
                        $query->bindParam(':sessionToken',$sessionToken,PDO::PARAM_STR);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        $cnt = 1;
                        if ($query->rowCount() > 0) {
                          foreach ($results as $row) {
                        ?>
                            <tr>
                              <td><?php echo htmlentities($cnt); ?></td>
                              <td><?php echo htmlentities($row->TestName); ?></td>
                              <td><a href="class-detail.php?editid=<?php echo htmlentities($row->class_id); ?>"><?php echo htmlentities($row->ClassName); ?></a></td>
                              <td><?php echo htmlentities($row->StartTime); ?></td>
                              <td><?php
                                $tid = $row->ID;
                                $sql1 = "SELECT * from tblstudent_test where test_id=:tid and student_id=:uid";
                                $query1 = $dbh->prepare($sql1);
                                $query1->bindParam(':tid', $tid, PDO::PARAM_STR);
                                $query1->bindParam(':uid', $uid, PDO::PARAM_STR);
                                $query1->execute();
                                $results1 = $query1->fetchAll(PDO::FETCH_OBJ);
                                if ($query1->rowCount() == 0) {
                                  echo "Not Started";
                                } else {
                                  if ($results1[0]->SubmitTime != Null) {
                                    echo htmlentities($results1[0]->SubmitTime);
                                  } else {
                                    echo "On Going";
                                  }
                                }
                                
                            ?>
                              </td>
                              <td>
                                <?php
                                if ($query1->rowCount() == 0) {
                                  echo "N/A";
                                } else {
                                  if ($results1[0]->TotalPoint == Null) {
                                    echo "N/A";
                                  } else {
                                    echo htmlentities($results1[0]->TotalPoint);
                                  }
                                }
                                ?>
                              </td>
                              <td>
                                <div><a href="test-detail.php?editid=<?php echo htmlentities($row->ID); ?>"><i class="icon-eye"></i></a></div>
                              </td>
                            </tr>
                        <?php $cnt = $cnt + 1;
                          } 
                        }} ?>
                      </tbody>
                    </table>
                  </div>
                  <div align="left">
                    <ul class="pagination">
                      <li><a href="?pageno=1"><strong>First></strong></a></li>
                      <li class="<?php if ($pageno <= 1) {
                              echo 'disabled';
                            } ?>">
                        <a href="<?php if ($pageno <= 1) {
                                echo '#';
                              } else {
                                echo "?pageno=" . ($pageno - 1);
                              } ?>"><strong style="padding-left: 10px">Prev></strong></a>
                      </li>
                      <li class="<?php if ($pageno >= $total_pages) {
                              echo 'disabled';
                            } ?>">
                        <a href="<?php if ($pageno >= $total_pages) {
                                echo '#';
                              } else {
                                echo "?pageno=" . ($pageno + 1);
                              } ?>"><strong style="padding-left: 10px">Next></strong></a>
                      </li>
                      <li><a href="?pageno=<?php echo $total_pages; ?>"><strong style="padding-left: 10px">Last</strong></a></li>
                    </ul>
                  </div>
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
  <script src="./vendors/chart.js/Chart.min.js"></script>
  <script src="./vendors/moment/moment.min.js"></script>
  <script src="./vendors/daterangepicker/daterangepicker.js"></script>
  <script src="./vendors/chartist/chartist.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/misc.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page -->
  <script src="./js/dashboard.js"></script>
  <!-- End custom js for this page -->
</body>
</html>