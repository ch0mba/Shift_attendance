<?php
header('Content-Type: application/json');
require_once '../includes/ShiftManager.php';

$shiftManager = new ShiftManager();
$activeShifts = $shiftManager->getCurrentActiveShifts();
$todayAttendance = $shiftManager->getTodayAttendance();

// Determine current shift based on time
$currentTime = date('H:i:s');
$currentShift = ($currentTime >= MORNING_SHIFT_START && $currentTime < MORNING_SHIFT_END) 
    ? 'Morning Shift (6AM - 6PM)' 
    : 'Night Shift (6PM - 6AM)';

echo json_encode([
    'success' => true,
    'current_time' => date('Y-m-d H:i:s'),
    'current_shift' => $currentShift,
    'active_shifts' => $activeShifts,
    'today_attendance' => $todayAttendance
]);
?>