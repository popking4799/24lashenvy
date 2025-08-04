<?php 
include 'header.php'; 
include 'dbconnect.php';

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['con_name'];
    $email = $_POST['con_email'];
    $phone = $_POST['con_phone'];
    $subject = $_POST['con_subject'];
    $messageContent = $_POST['con_message'];

    // Prepare SQL insert statement
    $stmt = $pdo->prepare("
        INSERT INTO contact_messages (name, email, phone, subject, message) 
        VALUES (:name, :email, :phone, :subject, :message)
    ");

    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':subject' => $subject,
        ':message' => $messageContent
    ]);

    $message = "Your message has been sent successfully!";
}
?>

<section class="breadcrumbs-area ptb-100">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="breadcrumbs">
                    <h2 class="page-title">Contact Us</h2>
                    <ul>
                        <li><a class="active" href="index.php">Home</a></li>
                        <li>Contact Us</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="contact-area" class="contact-area gray-bg ptb-100 bg-img-3 bg-gray">
    <div class="container">
        <div class="row">
            <form id="contact-form" method="post" class="col-lg-9">
                <h3 class="contact-title">Send Us a Message:</h3>

                <?php if ($message): ?>
                    <br>
                    <div class="alert alert-success">
                        <?= $message ?>
                    </div>
                <?php endif; ?>

                <div class="row all-contact-text">
                    <div class="col-md-6">
                        <div class="contact-message">
                            <input name="con_name" style="border-radius: 10px;" class="form-control" type="text" required placeholder="Your Name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="contact-message">
                            <input name="con_email" style="border-radius: 10px;" class="form-control" type="email" required placeholder="Your Email">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="contact-message">
                            <input name="con_phone" style="border-radius: 10px;" class="form-control" type="tel" required placeholder="Phone Number">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="contact-message">
                            <input name="con_subject" style="border-radius: 10px;" class="form-control" type="text" required placeholder="Subject">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="contact-textarea">
                            <textarea name="con_message" class="form-control" style="border-radius: 10px; resize: none;" required placeholder="Your Message"></textarea>
                        </div>
                        <div class="submit mt-20">
                            <input class="submit" style="border-radius: 10px;" type="submit" value="Send Message">
                        </div>
                    </div>
                </div>
            </form>

            <div class="col-lg-3">
                <div class="single-footer-widget info-style">
                    <h3 class="contact-title">Contact Info:</h3>
                    <div class="hs-footer-address">
                        <div class="ft-single-address">
                            <div class="footer-icon">
                                <a href="#"><i class="zmdi zmdi-pin"></i></a>
                            </div>
                            <div class="footer-address">
                                <p>Address: Abacha road opposite paradise cruise</p>
                            </div>
                        </div>
                        <div class="ft-single-address">
                            <div class="footer-icon">
                                <a href="mailto:info@24LashEnvy.com"><i class="zmdi zmdi-email"></i></a>
                            </div>
                            <div class="footer-address">
                                <p><a href="mailto:info@24LashEnvy.com">info@24LashEnvy.com</a></p>
                                <p><a href="mailto:support@24LashEnvy.com">support@24LashEnvy.com</a></p>
                            </div>
                        </div>
                        <div class="ft-single-address">
                            <div class="footer-icon">
                                <a href="tel:+94123456789"><i class="zmdi zmdi-phone"></i></a>
                            </div>
                            <div class="footer-address">
                                <p><a href="tel:+2349048776251">+234 904 877 6251</a></p>
                                <p><a href="tel:+2348061120132">+234 806 112 0132</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <p class="form-messege"></p>
        </div>
    </div>
</div>

<div class="map-area">
    <div class="contact-map">
        <div id="hastech">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d960.1325614536797!2d7.584080841018863!3d9.02710239487421!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x104e09489350e2fd%3A0x6027cce5e75753ee!2sParadise%20Cruise!5e0!3m2!1sen!2sng!4v1752731764639!5m2!1sen!2sng" allowfullscreen></iframe>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
