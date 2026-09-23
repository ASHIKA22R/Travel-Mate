<?php
$pageTitle = "Contact";
require "config.php";
require "includes/header.php";

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $subject = trim($_POST['subject'] ?? "");
    $messageText = trim($_POST['message'] ?? "");

    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $subject && $messageText) {
        $stmt = $conn->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $messageText);
        $stmt->execute();
        $message = "Your message has been saved. Thank you!";
    } else {
        $message = "Please enter all details correctly.";
    }
}
?>
<section class="section">
    <h2>Contact Us</h2>
    <p class="section-intro">Send a message to the travel support team.</p>
    <div class="form-box">
        <?php if ($message): ?><div class="alert"><?= htmlspecialchars($message) ?></div><?php endif; ?>
        <form method="post">
            <label>Name</label><input name="name" required>
            <label>Email</label><input type="email" name="email" required>
            <label>Subject</label><input name="subject" required>
            <label>Message</label><textarea name="message" required></textarea>
            <br><br><button class="btn" type="submit">Send Message</button>
        </form>
    </div>
</section>
<?php require "includes/footer.php"; ?>
