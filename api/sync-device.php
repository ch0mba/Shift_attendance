<?php
header('Content-Type: application/json');
require_once '../includes/ZKTeco.php';
require_once '../includes/ShiftManager.php';

$zk = new ZKTeco(ZK_IP, ZK_PORT);
$shiftManager = new ShiftManager();

// First, sync users from device
$users = $zk->getUsers();

// Get attendance logs
$attendance = $zk->getAttendance();

if (!$attendance['success']) {
    echo json_encode(['success' => false, 'message' => 'Failed to get attendance']);
    exit;
}

$processed = [];
$pdo = (new Database())->getConnection();

// Process each attendance log
foreach ($attendance['data'] as $log) {
    // Get user details
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$log['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $result = $shiftManager->processAttendance(
            $user['user_id'],
            $user['name'],
            $log['timestamp']
        );
        $processed[] = $result;
    }
}

// Process auto logout
$shiftManager->processAutoLogout();

// Get current active shifts
$activeShifts = $shiftManager->getCurrentActiveShifts();

echo json_encode([
    'success' => true,
    'processed' => $processed,
    'active_shifts' => $activeShifts,
    'timestamp' => date('Y-m-d H:i:s')
]);
?>