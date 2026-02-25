<?php
header('Content-Type: application/json');
require_once '../includes/ShiftManager.php';

$shiftManager = new ShiftManager();
$date = $_GET['date'] ?? date('Y-m-d');

$pdo = (new Database())->getConnection();

$sql = "SELECT 
            a.*,
            s.shift_name,
            TIMEDIFF(a.clock_out_time, a.clock_in_time) as duration,
            CASE 
                WHEN a.status = 'active' THEN 'Active'
                WHEN a.status = 'completed' THEN 'Completed'
                ELSE 'Auto Logout'
            END as status_text
        FROM attendance a
        JOIN shifts s ON a.shift_id = s.id
        WHERE DATE(a.clock_in_time) = :date
        ORDER BY a.clock_in_time DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['date' => $date]);
$attendance = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'success' => true,
    'date' => $date,
    'data' => $attendance
]);
?>