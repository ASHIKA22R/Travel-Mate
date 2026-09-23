<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? "TravelMate") ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="navbar">
    <div class="nav-inner">
        <a class="logo" href="index.php">✈ TravelMate</a>
        <nav>
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="destinations.php">Destinations</a>
            <a href="contact.php">Contact</a>
            <a class="nav-book" href="booking.php">Book Now</a>
        </nav>
        <div class="social">
            <span>f</span><span>◎</span><span>𝕏</span>
        </div>
    </div>
</header>
<main>
