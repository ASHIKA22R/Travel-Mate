<?php
$pageTitle = "Destinations";
require "config.php";
require "includes/header.php";

$country = trim($_GET['country'] ?? "");

if ($country !== "") {
    $stmt = $conn->prepare("SELECT * FROM destinations WHERE country = ? ORDER BY name");
    $stmt->bind_param("s", $country);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM destinations ORDER BY name");
}
?>
<section class="section">
    <h2>Destinations</h2>
    <p class="section-intro">Choose a country and view available destinations.</p>

    <div class="country-box">
        <div class="country-options">
            <a href="destinations.php">All</a>
            <a href="destinations.php?country=India">India</a>
            <a href="destinations.php?country=France">France</a>
            <a href="destinations.php?country=Indonesia">Indonesia</a>
            <a href="destinations.php?country=UAE">UAE</a>
            <a href="destinations.php?country=Singapore">Singapore</a>
            <a href="destinations.php?country=South Korea">South Korea</a>
        </div>
    </div>

    <div class="cards">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
        <article class="card">
            <div class="card-media"><img src="<?= htmlspecialchars(destination_image_url($row['image'])) ?>" alt="<?= htmlspecialchars($row['name']) ?>" loading="lazy"><span class="card-badge"><?= htmlspecialchars($row['country']) ?></span></div>
            <div class="card-body">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p><?= htmlspecialchars($row['description']) ?></p>
                <p><b><?= htmlspecialchars($row['country']) ?></b> · <?= (int)$row['days'] ?> days</p>
                <p class="price">₹<?= number_format($row['price']) ?> per person</p>
                <a class="btn" href="destination.php?id=<?= $row['id'] ?>">View Details</a>
            </div>
        </article>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No destinations found for this country.</p>
    <?php endif; ?>
    </div>
</section>
<?php require "includes/footer.php"; ?>
