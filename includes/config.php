<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'zkteco_attendance');
define('DB_USER', 'root');
define('DB_PASS', '');

// ZKTeco device configuration
define('ZK_IP', '192.168.1.201'); // Change to your device IP
define('ZK_PORT', 4370);

// Shift times
define('MORNING_SHIFT_START', '06:00:00');
define('MORNING_SHIFT_END', '18:00:00');
define('NIGHT_SHIFT_START', '18:00:00');
define('NIGHT_SHIFT_END', '06:00:00');

// Auto logout settings (in seconds)
define('AUTO_LOGOUT_TIME', 12 * 3600); // 12 hours (shift duration)
?>