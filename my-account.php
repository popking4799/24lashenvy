<?php 
include 'header.php';
include 'dbconnect.php';

// Start session and check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch the logged-in user's data
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Update personal information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update personal information
    if (isset($_POST['update_info'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $fax = $_POST['fax'];

        $stmt = $pdo->prepare("
            UPDATE users SET 
            first_name = :first_name, 
            last_name = :last_name, 
            email = :email, 
            telephone = :telephone, 
            fax = :fax 
            WHERE user_id = :user_id
        ");
        $stmt->execute([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'telephone' => $telephone,
            'fax' => $fax,
            'user_id' => $user_id
        ]);
        $message = 'Account information updated successfully.';
    }

    // Update password
    if (isset($_POST['change_password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE user_id = :user_id");
        $stmt->execute(['password' => $password, 'user_id' => $user_id]);
        $message = 'Password updated successfully.';
    }

    // Update address
    if (isset($_POST['update_address'])) {
        $address = $_POST['address'];
        $city = $_POST['city'];
        $country = $_POST['country'];
        $postal_code = $_POST['postal_code'];

        $stmt = $pdo->prepare("
            UPDATE users SET 
            address = :address, 
            city = :city, 
            country = :country, 
            postal_code = :postal_code 
            WHERE user_id = :user_id
        ");
        $stmt->execute([
            'address' => $address,
            'city' => $city,
            'country' => $country,
            'postal_code' => $postal_code,
            'user_id' => $user_id
        ]);
        $message = 'Address updated successfully.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .enhanced-account-section {
            background: linear-gradient(135deg, #fff 80%, #f8e1ff 100%);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(178,51,114,0.1);
            padding: 40px;
            margin-bottom: 30px;
        }
        .enhanced-panel {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            border: 1px solid #f0f0f0;
            overflow: hidden;
        }
        .enhanced-panel-heading {
            background: linear-gradient(90deg, #B23372 0%,rgba(178, 51, 115, 0.69) 100%);
            color: #fff;
            padding: 20px 25px;
            border: none;
            border-radius: 15px 15px 0 0;
        }
        .enhanced-panel-heading a {
            color: #fff !important;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .enhanced-panel-body {
            padding: 30px;
            background: #fff;
        }
        .enhanced-form-group {
            margin-bottom: 25px;
            position: relative;
        }
        .enhanced-form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }
        .enhanced-input-wrapper {
            position: relative;
        }
        .enhanced-input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #B23372;
            font-size: 1.2rem;
            z-index: 2;
        }
        .enhanced-form-control {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
        }
        .enhanced-form-control:focus {
            border-color: #B23372;
            box-shadow: 0 0 0 3px rgba(178,51,114,0.1);
            outline: none;
        }
        .enhanced-btn {
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .enhanced-btn-primary {
            background: linear-gradient(90deg, #B23372 0%,rgba(178, 51, 115, 0.77) 100%);
            color: #fff;
        }
        .enhanced-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(178,51,114,0.3);
            color: #fff;
        }
        .enhanced-btn-secondary {
            background: #f8f9fa;
            color: #6c757d;
            border: 2px solid #e9ecef;
        }
        .enhanced-btn-secondary:hover {
            background: #e9ecef;
            color: #495057;
        }
        .enhanced-alert {
            background: linear-gradient(90deg, #d4edda 0%, #c3e6cb 100%);
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            font-weight: 500;
        }
        .enhanced-section-title {
            color: #B23372;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center;
        }
        .enhanced-section-subtitle {
            color: #6b7280;
            text-align: center;
            margin-bottom: 40px;
            font-size: 1.1rem;
        }
        .enhanced-wishlist-link {
            background: linear-gradient(90deg, #B23372 0%,rgba(178, 51, 115, 0.73) 100%);
            color: #fff;
            padding: 15px 25px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .enhanced-wishlist-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(178,51,114,0.3);
            color: #fff;
        }
    </style>
</head>
<body>

<section class="breadcrumbs-area ptb-100 bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="breadcrumbs">
                    <h2 class="page-title">My Account</h2>
                    <ul>
                        <li><a class="active" href="#">Home</a></li>
                        <li>My Account</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="collapse_area coll2 ptb-90">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="enhanced-account-section">
                    
                <?php if (isset($message)): ?>
                        <div class="enhanced-alert">
                            <i class="zmdi zmdi-check-circle" style="margin-right: 8px;"></i><?= $message ?>
                        </div>
                <?php endif; ?>
                    
                <div class="faq-accordion">
                    <div class="panel-group pas7" id="accordion" role="tablist" aria-multiselectable="true">

                        <!-- Account Information -->
                            <div class="enhanced-panel">
                                <div class="enhanced-panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                        <a class="collapsed method" role="button" data-bs-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <span><i class="zmdi zmdi-account" style="margin-right: 10px;"></i>Edit your account information</span>
                                            <i class="zmdi zmdi-caret-down"></i>
                                        </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse show" role="tabpanel" aria-labelledby="headingOne" data-bs-parent="#accordion">
                                    <div class="enhanced-panel-body">
                                        <h3 style="color: #B23372; margin-bottom: 25px; font-weight: 600;">Personal Information</h3>
                                        <form class="form-horizontal" action="" method="POST">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="enhanced-form-group">
                                                        <label>First Name</label>
                                                        <div class="enhanced-input-wrapper">
                                                            <i class="zmdi zmdi-account"></i>
                                                            <input class="enhanced-form-control" type="text" name="first_name" value="<?= $user['first_name'] ?>" placeholder="First Name" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="enhanced-form-group">
                                                        <label>Last Name</label>
                                                        <div class="enhanced-input-wrapper">
                                                            <i class="zmdi zmdi-account"></i>
                                                            <input class="enhanced-form-control" type="text" name="last_name" value="<?= $user['last_name'] ?>" placeholder="Last Name" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                    </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="enhanced-form-group">
                                                        <label>Email Address</label>
                                                        <div class="enhanced-input-wrapper">
                                                            <i class="zmdi zmdi-email"></i>
                                                            <input class="enhanced-form-control" type="email" name="email" value="<?= $user['email'] ?>" placeholder="Email Address" required>
                                                </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="enhanced-form-group">
                                                        <label>Phone Number</label>
                                                        <div class="enhanced-input-wrapper">
                                                            <i class="zmdi zmdi-phone"></i>
                                                            <input class="enhanced-form-control" type="tel" name="telephone" value="<?= $user['telephone'] ?>" placeholder="Phone Number" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                            <div class="enhanced-form-group">
                                                <label>Fax (Optional)</label>
                                                <div class="enhanced-input-wrapper">
                                                    <i class="zmdi zmdi-print"></i>
                                                    <input class="enhanced-form-control" type="text" name="fax" value="<?= $user['fax'] ?>" placeholder="Fax Number">
                                                </div>
                                            </div>
                                            <div class="text-center mt-4">
                                                <button class="enhanced-btn enhanced-btn-primary" type="submit" name="update_info">
                                                    <i class="zmdi zmdi-save" style="margin-right: 8px;"></i>Update Information
                                                </button>
                                            </div>
                                        </form>
                                </div>
                            </div>
                        </div>

                        <!-- Change Password -->
                            <div class="enhanced-panel">
                                <div class="enhanced-panel-heading" role="tab" id="headingTwo">
                                <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-bs-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            <span><i class="zmdi zmdi-lock" style="margin-right: 10px;"></i>Change your password</span>
                                            <i class="zmdi zmdi-caret-down"></i>
                                        </a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo" data-bs-parent="#accordion">
                                    <div class="enhanced-panel-body">
                                        <h3 style="color: #B23372; margin-bottom: 25px; font-weight: 600;">Security Settings</h3>
                                        <form class="form-horizontal" action="" method="POST">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="enhanced-form-group">
                                                        <label>New Password</label>
                                                        <div class="enhanced-input-wrapper">
                                                            <i class="zmdi zmdi-lock"></i>
                                                            <input class="enhanced-form-control" type="password" name="password" placeholder="New Password" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="enhanced-form-group">
                                                        <label>Confirm Password</label>
                                                        <div class="enhanced-input-wrapper">
                                                            <i class="zmdi zmdi-lock"></i>
                                                            <input class="enhanced-form-control" type="password" placeholder="Confirm Password" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                            <div class="text-center mt-4">
                                                <button class="enhanced-btn enhanced-btn-primary" type="submit" name="change_password">
                                                    <i class="zmdi zmdi-key" style="margin-right: 8px;"></i>Change Password
                                                </button>
                                            </div>
                                        </form>
                                </div>
                            </div>
                        </div>

                        <!-- Address Book Entries -->
                            <div class="enhanced-panel">
                                <div class="enhanced-panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-bs-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            <span><i class="zmdi zmdi-home" style="margin-right: 10px;"></i>Modify your address book entries</span>
                                            <i class="zmdi zmdi-caret-down"></i>
                                        </a>
                                </h4>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree" data-bs-parent="#accordion">
                                    <div class="enhanced-panel-body">
                                        <h3 style="color: #B23372; margin-bottom: 25px; font-weight: 600;">Address Information</h3>
                                    <form action="" method="POST">
                                            <div class="enhanced-form-group">
                                                <label>Street Address</label>
                                                <div class="enhanced-input-wrapper">
                                                    <i class="zmdi zmdi-home"></i>
                                                    <input class="enhanced-form-control" type="text" name="address" value="<?= $user['address'] ?>" placeholder="Street Address" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="enhanced-form-group">
                                                        <label>City</label>
                                                        <div class="enhanced-input-wrapper">
                                                            <i class="zmdi zmdi-city"></i>
                                                            <input class="enhanced-form-control" type="text" name="city" value="<?= $user['city'] ?>" placeholder="City" required>
                                        </div>
                                            </div>
                                        </div>
                                                <div class="col-md-6">
                                                    <div class="enhanced-form-group">
                                                        <label>Country</label>
                                                        <div class="enhanced-input-wrapper">
                                                            <i class="zmdi zmdi-globe"></i>
                                                            <input class="enhanced-form-control" type="text" name="country" value="<?= $user['country'] ?>" placeholder="Country" required>
                                                        </div>
                                            </div>
                                        </div>
                                            </div>
                                            <div class="enhanced-form-group">
                                                <label>Postal Code</label>
                                                <div class="enhanced-input-wrapper">
                                                    <i class="zmdi zmdi-mail-send"></i>
                                                    <input class="enhanced-form-control" type="text" name="postal_code" value="<?= $user['postal_code'] ?>" placeholder="Postal Code" required>
                                        </div>
                                            </div>
                                            <div class="text-center mt-4">
                                                <button class="enhanced-btn enhanced-btn-primary" type="submit" name="update_address">
                                                    <i class="zmdi zmdi-edit" style="margin-right: 8px;"></i>Update Address
                                                </button>
                                            </div>
                                        </form>
                                        </div>
                                </div>
                            </div>

                        </div>
                        <div class="text-center">
                            <a class="enhanced-wishlist-link" href="wishlist.php">
                                <i class="zmdi zmdi-favorite" style="margin-right: 8px;"></i>View My Wishlist
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<!-- ✅ Bootstrap 5 JS Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
