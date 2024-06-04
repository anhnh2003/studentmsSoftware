<?php
// Set the error display level
ini_set('display_errors', '0');
// report all errors
error_reporting(E_ALL);

// Set secure session cookie flags
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');

// Use strict mode
ini_set('session.use_strict_mode', '1');
// start session
session_start();
// Set the desired time zone, e.g., 'UTC'
// Regenerate session ID upon login
if (!isset($_SESSION['initialized'])) {
    session_regenerate_id();
    $_SESSION['initialized'] = true;
}

// Set session expiration time
ini_set('session.gc_maxlifetime', 3600); // 1 hour
// Set session cookie lifetime
ini_set('session.cookie_lifetime', 3600); // 1 hour
//set cookie to same site = strict
ini_set('session.cookie_samesite', 'Strict');
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['login'])) 
  {
    $username=$_POST['username'];
    $password=$_POST['password'];
    $captcha = $_POST['g-recaptcha-response'];
    if (!$captcha){
      $_SESSION['error'] = "Please check the captcha form.";
      header('Location: login.php');
      exit();
    }
    else {
      $secret = '6LctYtwpAAAAAEP0w5UdNiqxoKbvdQo8WfQI-QtG';
      $verify_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $captcha);
      $response_data = json_decode($verify_response);
      if (!$response_data->success) {
        $_SESSION['error'] = "Invalid Captcha. Please try again.";
        header('Location: login.php');
        exit();
      }
    }
    $sql ="SELECT ID, Password FROM tbladmin WHERE UserName=:username";
    $query=$dbh->prepare($sql);
    $query-> bindParam(':username', $username, PDO::PARAM_STR);
    $query-> execute();
    $result=$query->fetch(PDO::FETCH_OBJ);
    if($query->rowCount()>0)
{
if(password_verify($password, $result->Password)){
$_SESSION['sturecmsaid']=$result->ID;

    // Generate a random session token
    $token = bin2hex(random_bytes(32));
 
    // Store the token in the database
    $insertTokenSQL = "INSERT INTO tbltoken (UserToken, UserID, role_id) VALUES (:token, :userid, 1)";
    $tokenQuery = $dbh->prepare($insertTokenSQL);
    $tokenQuery->bindParam(':token', $token, PDO::PARAM_STR);
    $tokenQuery->bindParam(':userid', $result->ID, PDO::PARAM_INT);
    $tokenQuery->execute();

    // Send the token to the client to save it
    setcookie("session_token", $token, time() + 7200,"/","",0,1); // 7200 seconds = 2 hours


  if(!empty($_POST["remember"])) {
//COOKIES for username
setcookie ("uid",$result->ID,time()+3600,"","",0,1);
} else {

setcookie ("uid",$result->ID,time()+7200,"","",0,1);

     
}
$_SESSION['login']=$_POST['username'];
echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
} else { 
  $_SESSION['error'] = "Wrong username or password.";
  header('Location: login.php');
  exit();
} }else{
  $_SESSION['error'] = "Wrong username or password.";
  header('Location: login.php');
  exit();
}
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
  
    <title>Student  Management System|| Login Page</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="css/style.css">
    <script src="https://www.google.com/recaptcha/api.js"></script>
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
          <div class="row flex-grow">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left p-5">
                <div class="brand-logo">
                  <img src="images/logo.svg">
                </div>
                <h4>Hello! Let's get started</h4>
                <h6 class="font-weight-light">Sign in to continue.</h6>
                
                <form class="pt-3" id="login" method="post" name="login">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" placeholder="Enter username" required="true" name="username" value="<?php if(isset($_COOKIE["user_login"])) { echo $_COOKIE["user_login"]; } ?>" >
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" placeholder="Enter password" name="password" required="true" value="<?php if(isset($_COOKIE["userpassword"])) { echo $_COOKIE["userpassword"]; } ?>">
                  </div>
                  <div class="g-recaptcha" data-sitekey="6LctYtwpAAAAAGqtbFtdwU1jq_hcUDl0rgjxmYSU"></div>
                  <?php
              if (isset($_SESSION['error'])) {
                  echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
                  unset($_SESSION['error']);
              }
              ?>
                  <div class="mt-3">
                    <button class="btn btn-success btn-block loginbtn" name="login" type="submit">Login</button>
                  </div>
                  <div class="my-2 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" id="remember" class="form-check-input" name="remember" <?php if(isset($_COOKIE["user_login"])) { ?> checked <?php } ?> /> Keep me signed in </label>
                    </div>
                    <a href="forgot-password.php" class="auth-link text-black">Forgot password?</a>
                  </div>
                  <div class="mb-2">
                    <a href="../index.php" class="btn btn-block btn-facebook auth-form-btn">
                      <i class="icon-social-home mr-2"></i>Back Home </a>
                  </div>
                  
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
    <!-- endinject -->
  </body>
</html>
