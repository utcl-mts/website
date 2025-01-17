<?php
/**
 * Logs an action into the audit_log table, including the user's IP address in the action description.
 *
 * @param int $staff_id The ID of the user performing the action.
 * @param string $action The description of the action being performed.
 * @param string $ip_address The IP address of the user.
 * @return void
 */
function logAction($conn, $staff_id, $action) {
    try {
        $ip_address = $_SERVER['REMOTE_ADDR']; // Capture the IP address
        $action_with_ip = "$action, IP: $ip_address"; // Append IP address to the action
        $log_sql = "INSERT INTO audit_logs (staff_id, act, date_time) VALUES (:staff_id, :act, :date_time)";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->execute([
            'staff_id' => $staff_id,
            'act' => $action_with_ip,
            'date_time' => time()
        ]);
    } catch (PDOException $e) {
        error_log("Failed to log action: " . $e->getMessage());
    }
}

// Example Usage
$staff_id = 1; // ID of the user performing the action
$action = "Logged in"; // Description of the action
$ip_address = $_SERVER['REMOTE_ADDR']; // User's IP address

logAction($staff_id, $action, $ip_address);
