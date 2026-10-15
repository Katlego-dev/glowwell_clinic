<?php
require_once 'db.php';

// 1. Fetch Overall Key Metrics (KPIs)
$totalBookingsStmt = $pdo->query("SELECT COUNT(*) AS total FROM appointments");
$totalBookings = $totalBookingsStmt->fetch()['total'];

$totalRevenueStmt = $pdo->query("
    SELECT SUM(s.price) AS total_revenue
    FROM appointments a
    JOIN services s ON a.service_id = s.service_id
    WHERE a.status != 'cancelled'
    ");
    $totalRevenue = $totalRevenueStmt->fetch()['total_revenue'] ?? 0;

    $pendingStmt = $pdo->query("SELECT COUNT(*) AS total FROM appointments WHERE status = 'pending'");
    $pendingCount = $pendingStmt->fetch()['total'];

// 2. Report: Revenue and Bookings by Service
$serviceReportSql = "
    SELECT
        s.service_name,
        s.price,
        COUNT(a.appointment_id) AS total_orders,
        SUM(s.price) AS total_generated
    FROM services s
    LEFT JOIN appointments a ON s.service_id = a.service_id AND a.status != 'cancelled'
    GROUP BY s.service_id
    ORDER BY total_generated DESC
    ";
    $serviceReport = $pdo->query($serviceReportSql)->fetchAll();

// 3. Report: Staff Performance Breakdown
$staffReportSql = "
SELECT
    u.full_name AS staff_name,
    st.specialization,
    COUNT(a.appointment_id) AS total_appointments
FROM staff st
JOIN users u ON st.user_id = u.user_id
LEFT JOIN appointments a ON st.staff_id = a.staff_id AND a.status != 'cancelled'
GROUP BY st.staff_id
ORDER BY total_appointments DESC
";
$staffReport = $pdo->query($staffReportSql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Reports - GlowWell Beauty Clinic</title>
    <link rel="stylesheet" href="css/style.css">
</head>


<body>
    <?php include 'navbar.php'; ?>

    <div class="container" style="max-width: 1000px;">
        <h1> Business Analytics & Reports</h1>

        <!-- Overall KPI Metrics Grid -->
         <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Bookings</h3>
                <div class="number"><?php echo $totalBookings; ?></div>
</div>
<div class="stat-card">
    <h3>Total Revenue</h3>
    <div class="number">R<?php echo number_format($totalRevenue, 2); ?></div>
</div>
<div class="stat-card">
    <h3>Pending Actions</h3>
    <div class="number"><?php echo $pendingCount; ?></div>
</div>
</div>

<!-- Service Revenue Breakdown -->
 <h2 class="section-title">Revenue by Treatment Service</h2>
 <div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Service Name</th>
                <th>Unit Price</th>
                <th>Total Bookings</th>
                <th>Total Revenue Generated</th>
</tr>
</thead>
<tbody>
    <?php foreach ($serviceReport as $row): ?>
        <tr>
            <td><strong><?php echo htmlspecialchars($row['service_name']); ?></strong></td>
            <td>R<?php echo number_format($row['price'], 2); ?></td>
            <td><?php echo $row['total-order']; ?></td>
            <td>R<?php echo number_format($row['total_generated'] ?? 0, 2); ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
    </div>

    <!-- Staff Specialist Performance -->
     <h2 class="section-title">Specialist Staff Workload</h2>
     <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Specialist Name</th>
                    <th>Specialization</th>
                    <th>Completed & Active Appointments</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($staffReport as $row): ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($row['staff_name']); ?></strong></td>
                <td><?php echo htmlspecialchars($row['specialization']); ?></td>
                <td><?php echo $row['total_appointments']; ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
        </table>
        </div>
        </div>

    
</body>
</html>