<?php 
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = filter_var($_POST['appointment_id'] ?? null, FILTER_VALIDATE_INT);
    $action         = trim($_POST['action'] ?? '');
    $status         = trim($_POST['status'] ?? '');

    if (!$appointment_id) {
        header("Location: admin_dashboard.php?status=error&message=" . urlencode("Invalid appointment ID."));
        exit;
    }

    try {
        if ($action === 'update') {
            //Validate allowed status values
            $allowedStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];
            if (!in_array($status, $allowedStatuses)){
                header("Location: admin_dashboard.php?status=error&message=" . urlencode("Invalid status value."));
                exit;
            }

            //Update status statement
            $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE appointment_id = ?");
            $stmt->execute([$status, $appointment_id]);

            header("Location: admin_dashboard.php?status=updated");
            exit;
        
        }else if ($action === 'delete') {
            //Delete appointment statement
            $stmt = $pdo->prepare("DELETE FROM appointments WHERE appointment_id = ?");
            $stmt->execute([$appointment_id]);

            header("Location: admin_dashboard.php?status=deleted");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: admin_dashboard.php?status=error&message=" . urlencode("Database error: " . $e->getMessage()));
        exit;
    }
} else {
    header("Location: admin_dashboard.php");
    exit;
}

?>

