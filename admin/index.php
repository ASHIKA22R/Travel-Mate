<?php
$pageTitle = "Admin - Bookings";
require "../config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?></title>
<link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<header class="navbar"><div class="nav-inner">
<a class="logo" href="../index.php">✈ TravelMate</a>
<nav><a href="../index.php">Home</a><a href="../destinations.php">Destinations</a><a href="../booking.php">Booking Form</a></nav>
</div></header>
<main>
<section class="section">
<h2>Admin - Booking Requests</h2>
<p class="section-intro">Simple student-project admin page for viewing records stored in MySQL.</p>
<div class="table-wrap">
<table>
<tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Destination</th><th>Date</th><th>Persons</th><th>Created</th></tr>
<?php $bookings=$conn->query("SELECT * FROM bookings ORDER BY created_at DESC"); while($row=$bookings->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td><td><?= htmlspecialchars($row['name']) ?></td><td><?= htmlspecialchars($row['email']) ?></td>
<td><?= htmlspecialchars($row['phone']) ?></td><td><?= htmlspecialchars($row['destination']) ?></td>
<td><?= htmlspecialchars($row['travel_date']) ?></td><td><?= $row['persons'] ?></td><td><?= htmlspecialchars($row['created_at']) ?></td>
</tr>
<?php endwhile; ?>
</table></div>

<h2 style="margin-top:40px">Contact Messages</h2>
<div class="table-wrap"><table>
<tr><th>ID</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Created</th></tr>
<?php $messages=$conn->query("SELECT * FROM messages ORDER BY created_at DESC"); while($row=$messages->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td><td><?= htmlspecialchars($row['name']) ?></td><td><?= htmlspecialchars($row['email']) ?></td>
<td><?= htmlspecialchars($row['subject']) ?></td><td><?= htmlspecialchars($row['message']) ?></td><td><?= htmlspecialchars($row['created_at']) ?></td>
</tr>
<?php endwhile; ?>
</table></div>
</section>
</main>
<footer class="footer"><p>TravelMate student project</p></footer>
</body>
</html>
