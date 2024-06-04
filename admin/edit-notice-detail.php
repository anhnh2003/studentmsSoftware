<?php
session_start();
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
} else {
  if (isset($_POST['submit'])) {
    $nottitle = $_POST['nottitle'];
    $classid = $_POST['classid'];
    $notmsg = $_POST['notmsg'];
    $eid = $_GET['editid'];

    $sql = "UPDATE tblnotice SET NoticeTitle=:nottitle, ClassId=:classid, NoticeMsg=:notmsg WHERE ID=:eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':nottitle', $nottitle, PDO::PARAM_STR);
    $query->bindParam(':classid', $classid, PDO::PARAM_STR);
    $query->bindParam(':notmsg', $notmsg, PDO::PARAM_STR);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();

    echo '<script>alert("Notice has been updated")</script>';
  }
}}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Student Management System || Update Notice</title>
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="vendors/select2/select2.min.css">
  <link rel="stylesheet" href="vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div class="container-scroller">
    <?php include_once('includes/header.php'); ?>
    <div class="container-fluid page-body-wrapper">
      <?php include_once('includes/sidebar.php'); ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">Update Notice</h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update Notice</li>
              </ol>
            </nav>
          </div>
          <div class="row">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title" style="text-align: center;">Update Notice</h4>
                  <form class="forms-sample" method="post" enctype="multipart/form-data">
                    <?php
                    $eid = $_GET['editid'];
                    $sql = "SELECT tblclass.ID, tblclass.ClassName, tblnotice.NoticeTitle, tblnotice.CreationTime, tblnotice.ClassId, tblnotice.NoticeMsg, tblnotice.ID as nid FROM tblnotice JOIN tblclass ON tblclass.ID=tblnotice.ClassId WHERE tblnotice.ID=:eid";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    $cnt = 1;
                    if ($query->rowCount() > 0) {
                      foreach ($results as $row) {
                    ?>
                        <div class="form-group">
                          <label for="exampleInputName1">Notice Title</label>
                          <input type="text" name="nottitle" value="<?php echo htmlentities($row->NoticeTitle); ?>" class="form-control" required='true'>
                        </div>
                        <div class="form-group">
                          <label for="exampleInputEmail3">Notice For</label>
                          <select name="classid" class="form-control">
                            <option value="<?php echo htmlentities($row->ClassId); ?>"><?php echo htmlentities($row->ClassName); ?></option>
                            <?php
                            $sql2 = "SELECT * FROM tblclass WHERE ID !=" . $row->ClassId;
                            $query2 = $dbh->prepare($sql2);
                            $query2->execute();
                            $result2 = $query2->fetchAll(PDO::FETCH_OBJ);

                            foreach ($result2 as $row1) {
                            ?>
                              <option value="<?php echo htmlentities($row1->ID); ?>"><?php echo htmlentities($row1->ClassName); ?></option>
                            <?php } ?>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="exampleInputName1">Notice Message</label>
                          <textarea name="notmsg" value="" class="form-control" required='true'><?php echo htmlentities($row->NoticeMsg); ?></textarea>
                        </div>
                    <?php $cnt = $cnt + 1;
                      }
                    } ?>
                    <button type="submit" class="btn btn-primary mr-2" name="submit">Update</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php include_once('includes/footer.php'); ?>
      </div>
    </div>
  </div>
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="vendors/select2/select2.min.js"></script>
  <script src="vendors/typeahead.js/typeahead.bundle.min.js"></script>
  <script src="js/off-canvas.js"></script>
  <script src="js/misc.js"></script>
  <script src="js/typeahead.js"></script>
  <script src="js/select2.js"></script>
</body>
</html>
