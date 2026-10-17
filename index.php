<?php
require_once 'db.php';

// Fetch active services from the database
$stmt = $pdo->query("SELECT * FROM services ORDER BY service_name ASC");
$services = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlowWell Beauty Clinic - Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

       <?php include 'navbar.php'; ?>

       <div class="container" style="max-width: 1000px;">

       <!-- Hero Banner Section -->
        <div class="hero-section">
            <h1>Welcome to GlowWell Beauty Clinic</h1>
            <p>Experience premium wellness, body massage, facial care, and beauty treatments.</p>
            <a href="book.php" class="btn-hero">Book Your Appointment Now</a>

</div>

<!-- Featured Services Section -->
 <h2 class="section-title">Our Featured Treatments</h2>
 <div class="services-grid">
    <?php foreach ($services as $service): ?>
        <div class="service-card">
            <div>
                <h3><?php echo htmlspecialchars($service['service_name']); ?></h3>
                <p><?php echo htmlspecialchars($service['description']); ?></p>
                <p><small>Duration: <?php echo $service['duration_minutes']; ?> minutes</small></p>
    </div>
    <div>
        <div class="price">R<?php echo number_format($service['price'], 2); ?></div>
        <a href="book.php" class="btn-secondary">Book Treatment</a>
    </div>
    </div>
    <?php endforeach; ?>
    </div>
    </div>
    
</body>
</html>