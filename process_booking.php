<?php 
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Sanitize and validate inputs
    $customer_id = filter_var($_POST['customer_id'] ?? null, FILTER_VALIDATE_INT);
    $service_id = filter_var($_POST['service_id'] ?? null, FILTER_VALIDATE_INT);
    $staff_id = filter_var($_POST['staff_id'] ?? null, FILTER_VALIDATE_INT);
    $date     = trim($_POST['appointment_date'] ?? '');
    $time     = trim($_POST['appointment_time'] ?? '');
    
    //Check for empty fields
    if (!$customer_id || !$service_id || !$staff_id || empty($date) || empty($time)) {
        header("Location: book.php?status=error&message=" . urlencode("All fields are required."));
        exit;
    }

    //Past date check on backend
    if (strtotime($date) < strtotime(date('Y-m-d'))) {
        header("Location: book.php?status=error&message=" . urlencode("You cannot select a date in the past."));
        exit;
    }

    try {
        //2. Check for double booking (Double booking Prevention)
        $checkSql = "SELECT appointment_id FROM appointments
                     WHERE staff_id = ? AND appointment_date = ? AND appointment_time = ? AND status != 'cancelled'";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$staff_id, $date, $time]);

        if ($checkStmt->rowCount() > 0) {
            header("Location: book.php?status=error&message=" . urlencode("This specialist is already booked for the selected date and time."));
            exit;
        }

        // 3. Insert new booking using PDO Prepared Statement
        $insertSql = "INSERT INTO appointments (customer_id, staff_id, service_id, appointment_date, appointment_time, status)
                      VALUES (?, ?, ?, ?, ?, 'pending')";
        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->execute([$customer_id, $staff_id, $service_id, $date, $time]);

        //Redirect with success flag
        header("Location: book.php?status=success");
        exit;
    } catch (PDOException $e) {
        header("Location: book.php?status=error&message=" . urlencode("Database error: " . $e->getMessage()));
        exit;
    }
} else {
    //Direct access redirect
    header("Location: book.php");
    exit;
}
?>