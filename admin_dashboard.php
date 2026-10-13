<?php
require_once 'db.php';

//Fetch all appointments with full details using SQL JOINs
$sql = "
        SELECT
            a.appointment_id,
            a.appointment_date,
            a.appointment_time,
            a.status,
            c. full_name AS customer_name,
            c.phone AS customer_phone,
            s.service_name,
            s.price,
            u_staff.full_name AS staff_name
        FROM appointments a
        JOIN users c ON a.customer_id = c.user_id
        JOIN services s ON a.service_id = s.service_id
        JOIN staff st ON a.staff_id = st.staff_id
        JOIN users u_staff ON st.user_id = u_staff.user_id
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
        ";

        $stmt = $pdo->query($sql);
        $appointments = $stmt->fetchAll();
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Dashboard - GlowWell Beauty Clinic</title>
            <link rel="stylesheet" href="css/style.css">
        </head>


        <body>
            <?php include 'navbar.php'; ?>

            <div class="container" style="max-width: 1000px;">
                <h1>Admin Dashboard - Manage Appointments</h1>

                <?php if (isset($_GET['status']) && $_GET['status'] === 'updated'): ?>
                    <div class="alert alert-success">Appointment updated successfully!</div>
                    <?php elseif (isset($_GET['status']) && $_GET['status'] === 'deleted'): ?>
                        <div class="alert alert-success">Appointment record removed successfully!</div>
                        <?php elseif (isset($_GET['status']) && $_GET['status'] === 'error'): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['message'] ?? 'Error processing request.'); ?></div>
                            <?php endif; ?>

                            <div class="table-container">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Customer</th>
                                            <th>Service</th>
                                            <th>Specialist</th>
                                            <th>Date & Time</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php if (count($appointments) > 0): ?>
                                            <?php foreach ($appointments as $app): ?>
                                                <tr>
                                                    <td>#<?php echo $app['appointment_id']; ?></td>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($app['customer_name']); ?></strong><br>
                                                        <small><?php echo htmlspecialchars($app['customer_phone']); ?></small>
                                            </td>
                                            <td><?php echo htmlspecialchars($app['service_name']); ?></td>
                                            <td><?php echo htmlspecialchars($app['staff_name']); ?></td>
                                            <td><?php echo $app['appointment_date'] . ' at ' . substr($app['appointment_time'], 0, 5); ?></td>
                                            <td>R<?php echo number_format($app['price'], 2); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo strtolower($app['status']); ?>">
                                                    <?php echo htmlspecialchars($app['status']); ?>
                                            </span>
                                            </td>
                                            <td>
                                                <!-- Status Update Form -->
                                                 <form action="update_status.php" method="POST" class="action-form">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $app['appointment_id']; ?>">

                                                    <select name="status" class="select-sm">
                                                        <option value="pending" <?php if($app['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                                        <option value="confirmed" <?php if($app['status'] == 'confirmed') echo 'selected'; ?>>Cornfirmed</option>
                                                        <option value="completed" <?php if($app['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                                                        <option value="cancelled" <?php if($app['status'] == 'cancelled') echo 'selected'; ?>>Cancelled</option>
                                            </select>

                                            <button type="submit" name="action" value="update" class="btn-sm">Save</button>
                                            <button type="submit" name="action" value="delete" class="btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this record?');">Delete</button>
                                            </form>
                                            </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="8" style="text-align: center;">No appointments found.</td>
                                            </tr>
                                            <?php endif; ?>
                                            </tbody>
                                            </table>
                                            </div>
                                            </div>
        
        
        
        </body>
        </html>