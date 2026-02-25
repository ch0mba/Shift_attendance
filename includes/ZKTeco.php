<?php
require_once 'Database.php';

class ZKTeco {
    private $socket;
    private $ip;
    private $port;
    private $db;
    
    public function __construct($ip, $port) {
        $this->ip = $ip;
        $this->port = $port;
        $this->db = new Database();
    }
    
    public function connect() {
        $this->socket = @fsockopen($this->ip, $this->port, $errno, $errstr, 5);
        if (!$this->socket) {
            return false;
        }
        return true;
    }
    
    public function disconnect() {
        if ($this->socket) {
            fclose($this->socket);
        }
    }
    
    public function getAttendance() {
        if (!$this->connect()) {
            return ['success' => false, 'message' => 'Could not connect to device'];
        }
        
        // Command to get attendance logs (simplified - actual protocol is more complex)
        // This is a simplified version - in production, use a proper SDK
        $command = pack('H*', '5050'); // Example command
        
        fwrite($this->socket, $command);
        $response = fread($this->socket, 1024);
        
        // Parse response (simplified)
        $logs = $this->parseAttendanceData($response);
        
        $this->disconnect();
        
        return ['success' => true, 'data' => $logs];
    }
    
    private function parseAttendanceData($data) {
        // Simplified parsing - in production, implement proper protocol
        $logs = [];
        
        // Save to device_logs table
        $pdo = $this->db->getConnection();
        
        // Example: Insert sample data (replace with actual parsing)
        $sql = "INSERT INTO device_logs (uid, user_id, timestamp, type, state) 
                VALUES (?, ?, ?, ?, ?)";
        
        // This would come from actual device data
        $sampleData = [
            [1, '1001', date('Y-m-d H:i:s'), 1, 0],
            [2, '1002', date('Y-m-d H:i:s'), 1, 0]
        ];
        
        foreach ($sampleData as $log) {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($log);
            $logs[] = $log;
        }
        
        return $logs;
    }
    
    public function getUsers() {
        if (!$this->connect()) {
            return ['success' => false, 'message' => 'Could not connect to device'];
        }
        
        // Get users from device
        $command = pack('H*', '6060'); // Example command
        fwrite($this->socket, $command);
        $response = fread($this->socket, 4096);
        
        // Parse users and sync to database
        $users = $this->parseUserData($response);
        $this->syncUsersToDB($users);
        
        $this->disconnect();
        
        return ['success' => true, 'data' => $users];
    }
    
    private function parseUserData($data) {
        // Simplified parsing
        return [
            ['user_id' => '1001', 'name' => 'John Doe'],
            ['user_id' => '1002', 'name' => 'Jane Smith']
        ];
    }
    
    private function syncUsersToDB($users) {
        $pdo = $this->db->getConnection();
        
        foreach ($users as $user) {
            $sql = "INSERT INTO users (user_id, name) 
                    VALUES (:user_id, :name) 
                    ON DUPLICATE KEY UPDATE name = :name";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'user_id' => $user['user_id'],
                'name' => $user['name']
            ]);
        }
    }
}
?>