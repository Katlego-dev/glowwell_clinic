<?php
require_once 'db.php';

//Fetch available services from database
$servicesStmt = $pdo->query("SELECT * FROM services ORDER BY service_name ASC");
$services = $servicesStmt->fetchAll();

//Fetch staff members with user details from database
$staffStmt = $pdo->query("
SELECT s.staff_id, u.full_name, s.specialization
FROM staff s
JOIN users u ON s.user_id = u.user_id
");
$staffMembers = $staffStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - GlowWell Beauty Clinic</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1>Book an Appointment</h1>

        <!-- Feedback Alert Messages -->
         <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div class="alert alert-success">Appointment booked successfully!</div>
            <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($_GET['message'] ?? 'An error occurred.'); ?>
            </div>
            <?php endif; ?>

            <form action="process_booking.php" method="POST">
                <!-- Simulated logged in customer ID (Alice Smith from seed data) -->
                 <input type="hidden" name="customer_id" value="3">

                 <div class="form-group">
                    <label for="service_id">Select Service:</label>
                    <select name="service_id" id="service_id" required>
                        <option value="">-- Choose a Service --</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?php echo $service['service_id']; ?>">
                                <?php echo htmlspecialchars($service['service_name']); ?> (R<?php echo number_format($service['price'], 2); ?>)
                        </option>
                        <?php endforeach; ?>
                        </select>
                        </div>

                        <div class="form-group">
                            <label for="staff_id">Select Specialist:</label>
                            <select name="staff_id" id="staff_id" required>
                                <option value="">-- Choose a Specialist --</option>
                                <?php foreach ($staffMembers as $staff): ?>
                                    <option value="<?php echo $staff['staff_id']; ?>">
                                        <?php echo htmlspecialchars($staff['full_name']); ?> - <?php echo htmlspecialchars($staff['specialization']); ?>
                                </option>
                                <?php endforeach; ?>
                                </select>
                                </div>

                                <div class="form-group">
                                    <label for="appointment_date">Date:</label>
                                    <input type="date" name="appointment_date" id="appointment_date" required>
                                </div>

                                <div class="form-group">
                                    <label for="appointment_time">Time:</label>
                                    <input type="time" name="appointment_time" id="appointment_time" required>
                                </div>

                                <button type="submit" class="btn-submit">Submit Booking</button>
                                </form>
                                </div>
</body>
</html>