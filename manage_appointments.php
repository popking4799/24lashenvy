<?php
session_start();
require 'dbconnect.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Start session and check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch the role of the logged-in user
$stmt_role = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
$stmt_role->execute(['user_id' => $user_id]);
$user = $stmt_role->fetch(PDO::FETCH_ASSOC);
$role = $user ? $user['role'] : null;

// Function to send cancellation email for appointments
function sendCancellationEmail($email, $appointmentDetails) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'];
        $mail->Password = $_ENV['SMTP_PASSWORD'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $_ENV['SMTP_PORT'];
        $mail->setFrom($_ENV['SMTP_USER'], '24LashEnvy');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = '24LashEnvy: Appointment Cancellation Confirmation';
        $mail->Body = $appointmentDetails;
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Handle approve appointment action (admin only)
if (isset($_GET['approve_appointment']) && $role === 'admin') {
    $appointment_id = $_GET['approve_appointment'];
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'Accepted' WHERE appointment_id = :appointment_id");
    $stmt->execute(['appointment_id' => $appointment_id]);
    $message = 'Appointment approved successfully.';
    header('Location: manage_appointments.php?message=' . urlencode($message));
    exit;
}

// Handle cancel appointment action (user or admin)
if (isset($_GET['cancel_appointment'])) {
    $appointment_id = $_GET['cancel_appointment'];
    // Only allow user to cancel their own, or admin to cancel any
    if ($role === 'admin') {
        $stmt_appointment = $pdo->prepare("
            SELECT a.appointment_id, a.appointment_date, a.appointment_time, s.name AS service_name, 
                   CONCAT(u_staff.first_name, ' ', u_staff.last_name) AS staff_name, u.email, u.first_name, u.last_name
            FROM appointments a
            JOIN services s ON a.service_id = s.service_id
            LEFT JOIN users u_staff ON a.staff_id = u_staff.user_id AND u_staff.role = 'staff'
            JOIN users u ON a.user_id = u.user_id
            WHERE a.appointment_id = :appointment_id
        ");
        $stmt_appointment->execute(['appointment_id' => $appointment_id]);
        $appointment = $stmt_appointment->fetch(PDO::FETCH_ASSOC);
        $stmt = $pdo->prepare("DELETE FROM appointments WHERE appointment_id = :appointment_id");
        $stmt->execute(['appointment_id' => $appointment_id]);
    } else {
        $stmt_appointment = $pdo->prepare("
            SELECT a.appointment_id, a.appointment_date, a.appointment_time, s.name AS service_name, 
                   CONCAT(u_staff.first_name, ' ', u_staff.last_name) AS staff_name, u.email, u.first_name, u.last_name
            FROM appointments a
            JOIN services s ON a.service_id = s.service_id
            LEFT JOIN users u_staff ON a.staff_id = u_staff.user_id AND u_staff.role = 'staff'
            JOIN users u ON a.user_id = u.user_id
            WHERE a.appointment_id = :appointment_id AND a.user_id = :user_id
        ");
        $stmt_appointment->execute([
            'appointment_id' => $appointment_id,
            'user_id' => $user_id
        ]);
        $appointment = $stmt_appointment->fetch(PDO::FETCH_ASSOC);
        $stmt = $pdo->prepare("DELETE FROM appointments WHERE appointment_id = :appointment_id AND user_id = :user_id");
        $stmt->execute([
            'appointment_id' => $appointment_id,
            'user_id' => $user_id
        ]);
    }
    $message = 'Appointment successfully canceled.';
    // Prepare the cancellation email content
    if ($appointment) {
        $appointmentDetails = "<h2>Appointment Cancellation</h2>";
        $appointmentDetails .= "<p>Dear {$appointment['first_name']} {$appointment['last_name']},</p>";
        $appointmentDetails .= "<p>Your appointment for the service <strong>{$appointment['service_name']}</strong> with staff <strong>{$appointment['staff_name']}</strong> at 24LashEnvy has been canceled.</p>";
        $appointmentDetails .= "<p>Date: " . date('F d, Y', strtotime($appointment['appointment_date'])) . "<br>Time: " . date('h:i A', strtotime($appointment['appointment_time'])) . "</p>";
        sendCancellationEmail($appointment['email'], $appointmentDetails);
    }
    header('Location: manage_appointments.php?message=' . urlencode($message));
    exit;
}

// Show message from redirect if present
if (isset($_GET['message'])) {
    $message = $_GET['message'];
}

// Fetch appointments for display
if ($role === 'admin') {
    // Admin: fetch all appointments
    $stmt = $pdo->prepare("
        SELECT a.appointment_id, a.appointment_date, a.appointment_time, a.status, s.name AS service_name, 
               CONCAT(u_staff.first_name, ' ', u_staff.last_name) AS staff_name,
               CONCAT(u.first_name, ' ', u.last_name) AS client_name
        FROM appointments a
        JOIN services s ON a.service_id = s.service_id
        LEFT JOIN users u_staff ON a.staff_id = u_staff.user_id AND u_staff.role = 'staff'
        JOIN users u ON a.user_id = u.user_id
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
    ");
    $stmt->execute();
} else {
    // Regular user: only their own appointments
    $stmt = $pdo->prepare("
        SELECT a.appointment_id, a.appointment_date, a.appointment_time, a.status, s.name AS service_name, 
               CONCAT(u_staff.first_name, ' ', u_staff.last_name) AS staff_name
        FROM appointments a
        JOIN services s ON a.service_id = s.service_id
        LEFT JOIN users u_staff ON a.staff_id = u_staff.user_id AND u_staff.role = 'staff'
        WHERE a.user_id = :user_id
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
    ");
    $stmt->execute(['user_id' => $user_id]);
}
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<style>
.manage-appointments-container {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
    padding: 40px 0;
}

.appointments-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    padding: 30px;
    margin-bottom: 30px;
}

.appointments-header {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f8f9fa;
}

.appointments-header h3 {
    color: #333;
    font-weight: 600;
    margin-bottom: 10px;
}

.appointments-header p {
    color: #666;
    font-size: 16px;
    margin: 0;
}

.appointments-table {
    overflow-x: auto;
}

.appointments-table table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.appointments-table thead th {
    background: #B23372;
    color: white;
    padding: 15px 12px;
    font-weight: 600;
    text-align: left;
    border: none;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.appointments-table tbody td {
    padding: 15px 12px;
    border-bottom: 1px solid #f1f3f4;
    vertical-align: middle;
    font-size: 14px;
}

.appointments-table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    transition: all 0.3s ease;
}

.appointments-table tbody tr:last-child td {
    border-bottom: none;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.status-pending {
    background-color: #fff3cd;
    color: #856404;
}
.status-confirmed {
    background-color: #d4edda;
    color: #155724;
}
.status-cancelled {
    background-color: #f8d7da;
    color: #721c24;
}
.status-completed {
    background-color: #cce5ff;
    color: #004085;
}
.status-inprogress {
    background-color: #ffeeba;
    color: #856404;
}
.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.btn-custom {
    padding: 8px 16px;
    border-radius: 5px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-block;
    text-align: center;
}
.btn-primary-custom {
    background: #764ba2;
    color: white;
}
.btn-primary-custom:hover, .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    color: white !important;
}
.btn-danger-custom {
    background: #ee5a52;
    color: white;
}
.btn-danger-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
    color: white !important;
}
.btn-success-custom {
    background:rgb(32, 104, 42);
    color: white;
}
.btn-success-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(107, 255, 181, 0.4);
    color: white !important;
}
.btn-disabled {
    background: #e9ecef;
    color: #6c757d;
    cursor: not-allowed;
}
.btn-disabled:hover {
    transform: none;
    box-shadow: none;
}
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}
.empty-state i {
    font-size: 48px;
    margin-bottom: 20px;
    color: #dee2e6;
}
.empty-state h4 {
    margin-bottom: 10px;
    color: #495057;
}
.alert-custom {
    border-radius: 10px;
    border: none;
    padding: 15px 20px;
    margin-bottom: 25px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}
.alert-success-custom {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    border-left: 4px solid #28a745;
}
@media (max-width: 768px) {
    .appointments-card {
        padding: 20px;
        margin: 10px;
    }
    .container {
        width: 100% !important;
    }
    .action-buttons {
        flex-direction: column;
    }
    .btn-custom {
        width: 100%;
        margin-bottom: 5px;
    }
}
</style>

<section class="breadcrumbs-area ptb-100 bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="breadcrumbs">
                    <h2 class="page-title">Manage Appointments</h2>
                    <ul>
                        <li>
                            <a class="active" href="index.php">Home</a>
                        </li>
                        <li>Manage Appointments</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="manage-appointments-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12">
                <div class="appointments-card">
                    <div class="appointments-header">
                        <h3><i class="zmdi zmdi-calendar"></i> My Appointments</h3>
                        <p>View and manage all your appointments</p>
                    </div>
                    <?php if (isset($message)): ?>
                        <div class="alert alert-success-custom alert-custom">
                            <i class="zmdi zmdi-check-circle"></i> <?= $message ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($appointments)): ?>
                        <div class="appointments-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Staff</th>
                                        <th>Appointment Date</th>
                                        <th>Appointment Time</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($appointments as $appointment): ?>
                                        <tr>
                                            <td><?= $appointment['service_name'] ?></td>
                                            <td><?= $appointment['staff_name'] ?? 'N/A' ?></td>
                                            <td><?= date('F d, Y', strtotime($appointment['appointment_date'])) ?></td>
                                            <td><?= date('h:i A', strtotime($appointment['appointment_time'])) ?></td>
                                            <td>
                                                <span class="status-badge status-<?= strtolower(str_replace(' ', '', $appointment['status'])) ?>">
                                                    <?= ucfirst($appointment['status']) ?>
                                                </span>
                                            </td>
<td>
    <div class="action-buttons">
        <?php 
            $appointment_datetime = strtotime($appointment['appointment_date'] . ' ' . $appointment['appointment_time']);
            $current_datetime = time();
            $status = strtolower(trim($appointment['status']));
            $status_class = strtolower(str_replace(' ', '', $appointment['status'])); // For badge class

            // Admin: Approve button for 'in progress', Cancel button always
            if ($role === 'admin') {
                if ($status === 'in progress') {
                    echo '<a href="?approve_appointment=' . $appointment['appointment_id'] . '" 
                            onclick="return confirm(\'Approve this appointment?\')" 
                            class="btn-custom btn-success-custom">
                            <i class="zmdi zmdi-check"></i> Approve
                          </a>';
                }
                echo '<a href="?cancel_appointment=' . $appointment['appointment_id'] . '" 
                        onclick="return confirm(\'Are you sure you want to cancel this appointment?\')" 
                        class="btn-custom btn-danger-custom">
                        <i class="zmdi zmdi-close"></i> Cancel
                      </a>';
            } else {
                // Regular user: Cancel button only if more than 24h before appointment
                if ($current_datetime < ($appointment_datetime - 86400)) {
                    echo '<a href="?cancel_appointment=' . $appointment['appointment_id'] . '" 
                            onclick="return confirm(\'Are you sure you want to cancel this appointment?\')" 
                            class="btn-custom btn-danger-custom">
                            <i class="zmdi zmdi-close"></i> Cancel
                          </a>';
                } else {
                    echo '<button class="btn-custom btn-disabled" disabled>
                            <i class="zmdi zmdi-block"></i> Cancel Unavailable
                          </button>';
                }
            }
        ?>
    </div>
</td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="zmdi zmdi-calendar"></i>
                            <h4>No Appointments Yet</h4>
                            <p>You have no upcoming appointments. Book a service to see your appointments here!</p>
                            <a href="services.php" class="btn-custom btn-primary-custom">
                                <i class="zmdi zmdi-plus-circle"></i> Book a Service
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>