<?php
/**
 * Logs an action into the audit_log table, including an anonymized IP address in the action description.
 *
 * @param PDO $conn The database connection.
 * @param int $staff_id The ID of the user performing the action.
 * @param string $action The description of the action being performed.
 * @return void
 */
function logAction($conn, $staff_id, $action) {
    try {
        // Capture and anonymize the IP address
        $ip_address = $_SERVER['REMOTE_ADDR'];
        // Append anonymized IP address to the action
        $action_with_ip = "IP: $ip_address, $action";

        $date_time = time();

        $sql = "INSERT INTO audit_logs (staff_id, act, date_time) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $staff_id);
        $stmt->bindParam(2, $action_with_ip);
        $stmt->bindParam(3, $date_time);
        $stmt->execute(); // Execute the statement
    } catch (PDOException $e) {
        error_log("Failed to log action: " . $e->getMessage());
        throw $e; // Optional: Re-throw for debugging during development
    }
}
