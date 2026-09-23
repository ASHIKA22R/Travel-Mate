<?php
$pageTitle = "Book a Trip";
require "config.php";
require "includes/header.php";

$message = "";
$error = "";
$selected = $_GET['destination'] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $phone = trim($_POST['phone'] ?? "");
    $destination = trim($_POST['destination'] ?? "");
    $travel_date = $_POST['travel_date'] ?? "";
    $persons = (int)($_POST['persons'] ?? 1);

    if ($name === "" || $email === "" || $phone === "" || $destination === "" || $travel_date === "" || $persons < 1) {
        $error = "Please fill all fields correctly.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $stmt = $conn->prepare("INSERT INTO bookings (name, email, phone, destination, travel_date, persons) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $name, $email, $phone, $destination, $travel_date, $persons);
        if ($stmt->execute()) {
            $message = "Booking request submitted successfully. Booking ID: " . $stmt->insert_id;
            $selected = "";
        } else {
            $error = "Unable to save the booking.";
        }
    }
}
?>
<section class="section">
    <h2>Travel Booking Form</h2>
    <p class="section-intro">Enter your details and submit a booking request.</p>
    <div class="form-box">
        <?php if ($message): ?><div class="alert"><?= htmlspecialchars($message) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form method="post">
            <div class="form-row">
                <div><label>Name</label><input name="name" required></div>
                <div><label>Email</label><input type="email" name="email" required></div>
            </div>
            <div class="form-row">
                <div><label>Phone</label><input name="phone" required></div>
                <div>
                    <label>Destination</label>
                    <select name="destination" required>
                        <option value="">Select destination</option>
                        <?php
                        $destinations = $conn->query("SELECT name FROM destinations ORDER BY name");
                        while ($d = $destinations->fetch_assoc()):
                        ?>
                            <option value="<?= htmlspecialchars($d['name']) ?>" <?= $selected === $d['name'] ? "selected" : "" ?>>
                                <?= htmlspecialchars($d['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div><label>Travel Date</label><input type="date" name="travel_date" required></div>
                <div><label>Number of Persons</label><input type="number" name="persons" min="1" value="1" required></div>
            </div>
            <br>
            <button class="btn" type="submit">Submit Booking</button>
        </form>
    </div>
</section>
<?php require "includes/footer.php"; ?>
