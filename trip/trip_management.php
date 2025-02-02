<?php
// trip_management.php
session_start();
include "../server/db_connect.php";           // Adjust path as needed
include "../server/check_cookie_user.php";     // Adjust path as needed
include "../server/navbar/trip_management.php";     // Adjust path as needed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Management</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body  class="full_page_styling">
<div>
    <br>

    <div>
    <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a class='active' href="trip_management.php">View All Trips</a></li>
                <li class="navbar_li"><a href="select_students.php">Create a new trip</a></li>
            </div>
        </ul>
    </div>

    <h1>Trip Management</h1>

    <?php
    try {
      // Query all trips (both past and present)
        $sql = "SELECT trip_id, trip_name, start_date, end_date, takes FROM trips ORDER BY trip_id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($trips) {
            echo "<table class='big_table'>";
            echo "<tr>";
            echo "<th class='big_table_th'>Trip ID</th>";
            echo "<th class='big_table_th'>Trip Name</th>";
            echo "<th class='big_table_th'>Start Date</th>";
            echo "<th class='big_table_th'>End Date</th>";
            echo "<th class='big_table_th'>Number of Students</th>";
            echo "<th class='big_table_th'>Actions</th>";
            echo "</tr>";

            foreach ($trips as $trip) {
            // Convert the Unix timestamps to human-readable dates
            $startDate = date('d/m/Y', $trip['start_date']);
            $endDate   = date('d/m/Y', $trip['end_date']);

            // Decode the JSON field to count the number of students
            $studentIDs = json_decode($trip['takes'], true);
            $studentCount = is_array($studentIDs) ? count($studentIDs) : 0;

            echo "<tr>";
            echo "<td class='big_table_td'>" . htmlspecialchars($trip['trip_id'], ENT_QUOTES) . "</td>";
            echo "<td class='big_table_td'>" . htmlspecialchars($trip['trip_name'], ENT_QUOTES) . "</td>";
            echo "<td class='big_table_td'>" . htmlspecialchars($startDate, ENT_QUOTES) . "</td>";
            echo "<td class='big_table_td'>" . htmlspecialchars($endDate, ENT_QUOTES) . "</td>";
            echo "<td class='big_table_td'>" . htmlspecialchars($studentCount, ENT_QUOTES) . "</td>";
            // The "Check Expirations" action sends you to trip_expiration.php with the trip id
            echo "
                <td class='big_table_td'>
                    <a class='table_button' href='trip_expiration.php?trip_id=" . urlencode($trip['trip_id']) . "'>Check Expirations</a>
                    <a class='table_button' href='export_excel.php?trip_id=" . urlencode($trip['trip_id']) . "'>Export Excel</a>
                </td>";
            echo "</tr>";
            }
        echo "</table>";
        } else {
            echo "<p>No trips found.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error fetching trips: " . htmlspecialchars($e->getMessage(), ENT_QUOTES) . "</p>";
    }
    ?>
</div>
</body>
</html>
