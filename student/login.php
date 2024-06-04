<?php
// Set the error display level
ini_set('display_errors', '0');
// report all errors
error_reporting(E_ALL);

// Set secure session cookie flags
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');
//set cookie to same site = strict
ini_set('session.cookie_samesite', 'Strict');
// Use strict mode
ini_set('session.use_strict_mode', '1');
// start session
session_start();
// Regenerate session ID upon login
if (!isset($_SESSION['initialized'])) {
    session_regenerate_id();
    $_SESSION['initialized'] = true;
}

// Set session expiration time
ini_set('session.gc_maxlifetime', 3600); // 1 hour
// Implement HTTPS enforcement in .htaccess or web server configuration

// Validate session ID (example pattern)
if (isset($_SESSION['user_id']) && !preg_match('/^[a-zA-Z0-9,-]{26,40}$/', session_id())) {
    // Invalid session ID, handle accordingly
}
error_reporting(0);
include('includes/dbconnection.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require("../lib/PHPMailer/src/PHPMailer.php");
require("../lib/PHPMailer/src/SMTP.php");
require("../lib/PHPMailer/src/Exception.php");
$hideOTP = "display: none;";
$hideLogin = "";

function generateSessionToken($dbh, $userid, $uid) {
  $_SESSION['sturecmsstuid']=$userid;
  $_SESSION['sturecmsuid']=$uid;
  // Generate a random session token
  $token = bin2hex(random_bytes(32));
  // Store the token in the database
  $insertTokenSQL = "INSERT INTO tbltoken (UserToken, UserID, role_id) VALUES (:token, :userid, 3)";
  $tokenQuery = $dbh->prepare($insertTokenSQL);
  $tokenQuery->bindParam(':token', $token, PDO::PARAM_STR);
  $tokenQuery->bindParam(':userid', $uid, PDO::PARAM_INT);
  $tokenQuery->execute();

  // Send the token to the client to save it
  setcookie("session_token", $token, time() + 7200,"","",false,true); // 7200 seconds = 2 hours

  if(!empty($_POST["remember"])) {
    //COOKIES for username
    setcookie ("uid",$uid,time()+3600,"","",false,true);
  } else {
    setcookie ("uid",$uid,time()+7200,"","",false,true);
  }

  $_SESSION['login']=$_POST['username'];

  echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
}

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
        $_SESSION['error'] = "Please check the captcha form.";
        header('Location: login.php');
        exit();
      }
    }
    $sql ="SELECT StudentName uname, Email, StuID, ID, Password, is2FA FROM tblstudent WHERE UserName=:username";
    $query=$dbh->prepare($sql);
    $query-> bindParam(':username', $username, PDO::PARAM_STR);
    $query-> execute();
    $result=$query->fetch(PDO::FETCH_OBJ);
    if($query->rowCount()>0) {
if(password_verify($password, $result->Password)){
  if ($result->is2FA == 1) {
    $hideOTP = "";
    $hideLogin = "display: none;";
    $genotp = rand(100000, 999999);
    $_SESSION['otp'] = $genotp;
    $_SESSION['stuid'] = $result->StuID;
    $_SESSION['uid'] = $result->ID;

    // Send Email OTP
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = 'smtp.gmail.com';
    $mail->Post = 587;
    $mail->isHTML(true);
    $mail->Username = 'nguyenquochuy712@gmail.com';
    $mail->Password = 'cthcwberksoutoss';
    
    $mail->setFrom('nguyenquochuy712@gmail.com');
    $mail->addAddress($result->Email, 'Student'); 
    
    $mail->Subject = '2FA Login OTP - Student Management System';
    $mail->Body = '<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2"> <div style="margin:50px auto;width:70%;padding:20px 0"> <div style="border-bottom:1px solid #eee"> <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">Student Management System</a> </div> <p style="font-size:1.1em">Hi, '. $results->uname .'</p> <p>Use the following OTP to complete your 2FA Login procedures.</p> <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">'. $genotp .'</h2> <hr style="border:none;border-top:1px solid #eee" /> <p style="font-size:0.9em;"><em>If this is not you, please do not share this OTP.</em></p> </div> </div>';
    if (!$mail->send()) {
        echo "<script>alert('" . $mail->ErrorInfo ."');</script>";
    }
    $mail->smtpClose();
  } else {
    generateSessionToken($dbh, $result->StuID, $result->ID);
  }
} else {
  $_SESSION['error'] = "Wrong username or password.";
  header('Location: login.php');
  exit();
} } else{
  $_SESSION['error'] = "Wrong username or password.";
  header('Location: login.php');
  exit();
}
}

if (isset($_POST['confirm'])) {
  $otp = $_POST['otp'];
  if ($otp == $_SESSION['otp']) {
    $_SESSION['otp'] = 0;
    generateSessionToken($dbh, $_SESSION['stuid'], $_SESSION['uid']);
    $_SESSION['stuid'] = 0;
    $_SESSION['uid'] = 0;
  } else {
    $_SESSION['error'] = "Invalid OTP.";
    $_SESSION['otp'] = 0;
    $_SESSION['stuid'] = 0;
    $_SESSION['uid'] = 0;
    header('Location: login.php');
    exit();
  }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
  
    <title>Student Management System || Student Login</title>
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
                <h4 style="<?php echo $hideLogin; ?>">Hello! Let's get started</h4>
                <h6 class="font-weight-light" style="<?php echo $hideLogin; ?>">Sign in to continue. </h6>
                <form class="pt-3" id="user_login" method="post" name="user_login" style="<?php echo $hideLogin; ?>">
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
                <form class="pt-3" id="user_otp" method="post" name="user_otp" style="<?php echo $hideOTP; ?>">
                <h4>Two-Factor Authentication</h4>
                <div class="form-group">
                    <input type="text" class="form-control form-control-lg" placeholder="Enter OTP sent to Email" required="true" name="otp" value="" maxlength="6" pattern="[0-9]+">
                  </div>
                  <div class="mt-3">
                    <button class="btn btn-success btn-block loginbtn" name="confirm" type="submit">Confirm</button>
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