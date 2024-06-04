<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
//require("../lib/SpeedSMSAPI_PHP/SpeedSMSAPI.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require("../lib/PHPMailer/src/PHPMailer.php");
require("../lib/PHPMailer/src/SMTP.php");
require("../lib/PHPMailer/src/Exception.php");

$btnSubmit = "";
$btnConfirm = "display: none;";
$hideOTP = "display: none;";
$readonlyEmail = "";
$valueEmail = "";
$readonlyNewPassword = "";
$valueNewPassword = "";
$readonlyConfirmPassword = "";
$valueConfirmPassword = "";

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $sql = "SELECT ContactNumber, Email, StudentName FROM tblstudent WHERE Email=:email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        //if ($results[0]->ContactNumber == null) {
        if (false) {
            echo "<script>alert('Contact Number not set! Please contact an Admin');</script>";
        } else {
            $newpassword = $_POST['newpassword'];
            $confirmpassword = $_POST['confirmpassword'];

            if ($newpassword != $confirmpassword) {
                echo "<script>alert('New Password and Confirm Password do not match');</script>";
            } else {
                // Update fields
                $valueEmail = $email;
                $readonlyEmail = "readonly";
                $valueNewPassword = $newpassword;
                $readonlyNewPassword = "readonly";
                $valueConfirmPassword = $confirmpassword;
                $readonlyConfirmPassword = "readonly";
                $btnSubmit = "display: none;";
                $btnConfirm = "";
                $hideOTP = "";

                // Save OTP
                $genotp = rand(100000, 999999);
                $_SESSION['otp'] = $genotp;
                $_SESSION['newpassword'] = $newpassword;
                $_SESSION['email'] = $email;

                //Send OTP
                //$smsAPI = new SpeedSMSAPI("uD16jYm9g6Y1xfR06asEGEESQp1w7seK");
                //$phones = [$results[0]->ContactNumber];
                //$content = "Your OTP code to Reset Password is: " . $genotp;
                //$type = 2;
                //$sender = "Verify";
                //$response = $smsAPI->sendSMS($phones, $content, $type, $sender);
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
                $mail->addAddress($email, 'Student'); 
                
                $mail->Subject = 'Reset Password OTP - Student Management System';
                $mail->Body = '<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2"> <div style="margin:50px auto;width:70%;padding:20px 0"> <div style="border-bottom:1px solid #eee"> <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">Student Management System</a> </div> <p style="font-size:1.1em">Hi, '. $results[0]->StudentName .'</p> <p>Use the following OTP to complete your Reset Password procedures.</p> <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">'. $genotp .'</h2> <hr style="border:none;border-top:1px solid #eee" /> <p style="font-size:0.9em;"><em>If this is not you, please do not share this OTP.</em></p> </div> </div>';
                if (!$mail->send()) {
                    echo "<script>alert('" . $mail->ErrorInfo ."');</script>";
                }
                $mail->smtpClose();
                //echo "<script>alert('" . implode(" ", $response) . $genotp . "');</script>";
            }
        }
    } else {
        echo "<script>alert('Email is invalid');</script>";
    }
}

if (isset($_POST['confirm'])) {
    $otp = $_POST['otp'];
    if ($otp == $_SESSION['otp'] && $otp != null) {
        $_SESSION['otp'] = null;
        $newpassword = password_hash($_SESSION['newpassword'], PASSWORD_DEFAULT);
        $con = "update tblstudent set Password=:newpassword where Email=:email";
        $chngpwd1 = $dbh->prepare($con);
        $chngpwd1->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
        $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        $chngpwd1->execute();
        echo "<script>alert('Your Password has been successfully changed');</script>";
        echo "<script>window.location.href ='../index.php'</script>";
    } else {
        $_SESSION['otp'] = null;
        echo "<script>alert('OTP is incorrect');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Student Management System || Forgot Password</title>
    <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript"></script>
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
                            <h4>RECOVER PASSWORD</h4>
                            <h6 class="font-weight-light">Enter your Email address to reset password!</h6>
                            <form class="pt-3" id="login" method="post" name="login">
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-lg" placeholder="Email Address" required="true" name="email" value="<?php echo $valueEmail; ?>" <?php echo $readonlyEmail; ?>>
                                </div>
                                <div class="form-group">
                                    <input class="form-control form-control-lg" type="password" name="newpassword" value="<?php echo $valueNewPassword; ?>" placeholder="New Password" required="true" <?php echo $readonlyNewPassword; ?> />
                                </div>
                                <div class="form-group">
                                    <input class="form-control form-control-lg" type="password" name="confirmpassword" value="<?php echo $valueConfirmPassword; ?>" placeholder="Confirm Password" required="true" <?php echo $readonlyConfirmPassword; ?> />
                                </div>
                                <div class="mt-3" style="<?php echo $btnSubmit; ?>">
                                    <button class="btn btn-success btn-block loginbtn" name="submit" type="submit">Reset</button>
                                </div>
                            </form>
                            <form class="pt-3" id="sendotp" method="post" name="sendotp">
                                <div class="form-group" style="<?php echo $hideOTP; ?>">
                                    <input class="form-control form-control-lg" type="text" name="otp" placeholder="Enter OTP sent to Email" maxlength='6' required='true' pattern="[0-9]+" />
                                </div>
                                <div class="mt-3" style="<?php echo $btnConfirm; ?>">
                                    <button class="btn btn-success btn-block loginbtn" name="confirm" type="submit">Confirm OTP</button>
                                </div>
                                <div class="mt-2">
                                    <a href="../index.php" class="btn btn-block btn-facebook auth-form-btn">
                                        <i class="icon-social-home mr-2"></i>Back </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="js/off-canvas.js"></script>
    <script src="js/misc.js"></script>
</body>

</html>
