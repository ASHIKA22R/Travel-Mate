<?php
$pageTitle = "Destination Details";
require "config.php";
require "includes/header.php";

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM destinations WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$destination = $stmt->get_result()->fetch_assoc();

if (!$destination) {
    echo '<section class="section"><div class="form-box"><h2>Destination not found</h2><a class="btn" href="destinations.php">Back</a></div></section>';
    require "includes/footer.php";
    exit;
}
?>
<section class="section">
    <div class="detail">
        <div>
            <img src="<?= htmlspecialchars(destination_image_url($destination['image'])) ?>" alt="<?= htmlspecialchars($destination['name']) ?>" loading="lazy">
            <p class="photo-credit">Destination photograph sourced from Wikimedia Commons.</p>
        </div>
        <div class="detail-box">
            <h2><?= htmlspecialchars($destination['name']) ?></h2>
            <p><?= htmlspecialchars($destination['description']) ?></p>
            <ul class="info-list">
                <li><b>Country:</b> <?= htmlspecialchars($destination['country']) ?></li>
                <li><b>Duration:</b> <?= (int)$destination['days'] ?> days</li>
                <li><b>Starting price:</b> ₹<?= number_format($destination['price']) ?></li>
                <li><b>Best time:</b> <?= htmlspecialchars($destination['best_time']) ?></li>
            </ul>
            <a class="btn" href="booking.php?destination=<?= urlencode($destination['name']) ?>">Book this destination</a>
            <a class="btn secondary" href="destinations.php">Back to destinations</a>
        </div>
    </div>
</section>
<?php require "includes/footer.php"; ?>
