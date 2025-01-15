<?php
/**
 * Logs an action into the audit_log table, including the user's IP address in the action description.
 *
 * @param int $staff_id The ID of the user performing the action.
 * @param string $action The description of the action being performed.
 * @param string $ip_address The IP address of the user.
 * @return void
 */
function logAction($staff_id, $action, $ip_address) {

    include "../server/db_connect.php";

        // Include the IP address in the action description
        $action_with_ip = "$action, IP: $ip_address";

        // Prepare the SQL query
        $query = "INSERT INTO audit_log (staff_id, act) VALUES (:staff_id, :act)";
        $stmt = $db->prepare($query);

        // Bind parameters and execute the query
        $stmt->execute([
            ':staff_id' => $staff_id,
            ':act' => $action_with_ip
        ]);
    } catch (PDOException $e) {
        // Handle database errors
        error_log("Failed to log action: " . $e->getMessage());
    }
}

// Example Usage
$staff_id = 1; // ID of the user performing the action
$action = "Logged in"; // Description of the action
$ip_address = $_SERVER['REMOTE_ADDR']; // User's IP address

logAction($staff_id, $action, $ip_address);
