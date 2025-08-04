<?php
include 'header.php';
include 'dbconnect.php';
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Function to send OTP using MailerSend API
function sendOTP($email, $otp) {
    try {
        $mailersend = new MailerSend(['api_key' => $_ENV['MAILERSEND_API_KEY']]);

        $recipients = [new Recipient($email, 'Customer')];

        $emailParams = (new EmailParams())
            ->setFrom($_ENV['MAILERSEND_SENDER_EMAIL'])
            ->setFromName($_ENV['MAILERSEND_SENDER_NAME'])
            ->setRecipients($recipients)
            ->setSubject('Your OTP for 24LashEnvy Account Verification')
            ->setHtml("Dear Customer,<br><br>Your OTP is: <b>$otp</b>.<br><br>This OTP will expire in 10 minutes.<br><br>Thanks,<br>24LashEnvy Team");

        $mailersend->email->send($emailParams);
        return true;
    } catch (Exception $e) {
        error_log('MailerSend Error: ' . $e->getMessage());
        return false;
    }
}

// Handle registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telephone = $_POST['telephone'];

    // Check if email already exists
    $stmt_users = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt_users->execute(['email' => $email]);
    $existing_user = $stmt_users->fetch(PDO::FETCH_ASSOC);

    if ($existing_user) {
        $message = "An account with this email already exists.";
        $message_type = 'danger';
    } else {
        $otp_code = rand(100000, 999999);
        $otp_expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        $stmt_check_otp = $pdo->prepare("SELECT * FROM user_otp WHERE email = :email");
        $stmt_check_otp->execute(['email' => $email]);
        $existing_otp_entry = $stmt_check_otp->fetch(PDO::FETCH_ASSOC);

        if ($existing_otp_entry) {
            $stmt_update_otp = $pdo->prepare("
                UPDATE user_otp 
                SET first_name = :first_name, last_name = :last_name, password = :password, telephone = :telephone, otp_code = :otp_code, otp_expiry = :otp_expiry 
                WHERE email = :email
            ");
            $stmt_update_otp->execute([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => $password,
                'telephone' => $telephone,
                'otp_code' => $otp_code,
                'otp_expiry' => $otp_expiry
            ]);
        } else {
            $stmt_otp = $pdo->prepare("
                INSERT INTO user_otp (first_name, last_name, email, password, telephone, otp_code, otp_expiry) 
                VALUES (:first_name, :last_name, :email, :password, :telephone, :otp_code, :otp_expiry)
            ");
            $stmt_otp->execute([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'password' => $password,
                'telephone' => $telephone,
                'otp_code' => $otp_code,
                'otp_expiry' => $otp_expiry
            ]);
        }

        // Send OTP
        if (sendOTP($email, $otp_code)) {
            echo "<script type='text/javascript'>
                    alert('OTP sent to your email. Please check your inbox.');
                    window.location.href = 'verify.php?email=$email';
                  </script>";
        } else {
            $message = "Failed to send OTP. Please try again later.";
            $message_type = 'danger';
        }
    }
}
?>

<section class="breadcrumbs-area ptb-100 bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="breadcrumbs">
                    <h2 class="page-title">Register</h2>
                    <ul>
                        <li><a class="active" href="index.php">Home</a></li>
                        <li>Register</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="register-area ptb-90">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="register-content">

                    <?php if (isset($message)): ?>
                        <div class="mt-20 alert alert-<?= $message_type ?>"><?= $message ?></div>
                    <?php endif; ?>

                    <form action="register.php" method="POST">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="form-group">
                            <label>Telephone</label>
                            <input type="tel" class="form-control" name="telephone" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="register" class="btn btn-primary ce5 btn-large mb-10">Register</button>
                        </div>
                        <div class="text-center">
                            <p>If you already have an account, <a href="login.php" style="color: #B23372;">login here</a>.</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.form-control {
    padding: 12px;
    font-size: 16px;
    border: 1px solid #000000;
    border-radius: 4px;
    margin-bottom: 20px;
}
.btn-primary {
    border: none;
    padding: 12px;
    font-size: 20px;
    width: 100%;
    height: 45px;
}
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}
</style>

<?php include 'footer.php'; ?>
