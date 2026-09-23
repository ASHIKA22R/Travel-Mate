<?php
$pageTitle = "TravelMate - Home";
require "config.php";
require "includes/header.php";

$result = $conn->query("SELECT * FROM destinations ORDER BY id DESC LIMIT 3");
?>
<section class="hero">
    <div class="hero-inner">
        <div class="hero-copy">
            <div class="eyebrow">✦ SMART TRAVEL PLANNING</div>
            <h1>Discover places worth travelling for.</h1>
            <p>Explore hand-picked destinations, compare trip details and send your booking request through a clean PHP & MySQL travel platform.</p>
            <div class="hero-actions">
                <a class="btn primary-light" href="destinations.php">Explore Destinations</a>
                <a class="btn secondary" href="booking.php">Book a Trip</a>
            </div>
        </div>
        <div class="hero-img">
            <img src="<?= htmlspecialchars(destination_image_url('assets/india.svg')) ?>" alt="Meenakshi Amman Temple, Madurai, India" loading="eager">
        </div>
    </div>
</section>

<section class="section">
    <div class="country-box">
        <h3>Explore by country</h3>
        <div class="country-options">
            <a href="destinations.php?country=India">India</a>
            <a href="destinations.php?country=France">France</a>
            <a href="destinations.php?country=Indonesia">Indonesia</a>
            <a href="destinations.php?country=UAE">UAE</a>
            <a href="destinations.php?country=Singapore">Singapore</a>
            <a href="destinations.php?country=South Korea">South Korea</a>
        </div>
    </div>
</section>

<section class="section" style="padding-top:10px">
    <h2>Popular destinations</h2>
    <p class="section-intro">Real destination photography, useful travel details and simple booking in one place.</p>
    <div class="cards">
    <?php while ($row = $result->fetch_assoc()): ?>
        <article class="card">
            <div class="card-media">
                <img src="<?= htmlspecialchars(destination_image_url($row['image'])) ?>" alt="<?= htmlspecialchars($row['name']) ?>" loading="lazy">
                <span class="card-badge"><?= htmlspecialchars($row['country']) ?></span>
            </div>
            <div class="card-body">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p><?= htmlspecialchars($row['description']) ?></p>
                <p class="price">From ₹<?= number_format($row['price']) ?></p>
                <a class="btn" href="destination.php?id=<?= $row['id'] ?>">View Details</a>
            </div>
        </article>
    <?php endwhile; ?>
    </div>
</section>
<?php require "includes/footer.php"; ?>
