<?php
// trip_expiration.php
session_start();
require "../server/db_connect.php";           // Adjust path as needed
require "../server/audit-log.php";
require "../server/check_cookie_user.php";     // Adjust path as needed
require "../server/navbar/trip_management.php";     // Adjust path as needed

// Ensure a trip_id is provided
if (!isset($_GET['trip_id'])) {
    die("Trip ID is required.");
}

$trip_id = intval($_GET['trip_id']);

// First, fetch the trip details to get the JSON with the selected takes_ids
try {
    $sql = "SELECT trip_id, trip_name, start_date, end_date, takes FROM trips WHERE trip_id = :trip_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':trip_id', $trip_id, PDO::PARAM_INT);
    $stmt->execute();
    $trip = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$trip) {
        die("Trip not found.");
    }
    
    // Decode the JSON array (which should be an array of takes_id values)
    $takesIDs = json_decode($trip['takes'], true);
    if (!is_array($takesIDs) || empty($takesIDs)) {
        die("No students associated with this trip.");
    }
    
    // Prepare a string of placeholders for the IN clause
    $placeholders = implode(',', array_fill(0, count($takesIDs), '?'));
    
    // Query the related 'takes' records (joined with related tables)
    // Now ordering the results alphabetically by the student's last name.
    $sqlTakes = "SELECT takes.takes_id, students.student_id, students.first_name, students.last_name, 
                        med.med_name, brand.brand_name, takes.exp_date
                 FROM takes 
                 INNER JOIN med ON takes.med_id = med.med_id 
                 INNER JOIN brand ON takes.brand_id = brand.brand_id 
                 INNER JOIN students ON takes.student_id = students.student_id 
                 WHERE takes.takes_id IN ($placeholders)
                 ORDER BY students.last_name ASC";
    
    $stmtTakes = $conn->prepare($sqlTakes);
    // Bind each takes_id value
    foreach ($takesIDs as $index => $id) {
        $stmtTakes->bindValue($index + 1, $id, PDO::PARAM_INT);
    }
    $stmtTakes->execute();
    $takesResults = $stmtTakes->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trip Expirations - <?php echo htmlspecialchars($trip['trip_name'], ENT_QUOTES); ?></title>
  <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body class="full_page_styling">
<div >
    <br>

    <div>
        <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a class='active' href="trip_management.php">View All Trips</a></li>
                <li class="navbar_li"><a href="select_students.php">Create a new trip</a></li>
            </div>
        </ul>
    </div>
  <h1>Trip: <?php echo htmlspecialchars($trip['trip_name'], ENT_QUOTES); ?></h1>
  <p>
    <strong>Trip Dates:</strong> 
    <?php 
      echo date('d/m/Y', $trip['start_date']) . " to " . date('d/m/Y', $trip['end_date']); 
    ?>
  </p>
  <h2>Student Medication Expirations</h2>
  
  <?php
  if ($takesResults) {
      echo "<table class='big_table'>";
      echo "<tr>";
      echo "<th class='big_table_th'>Takes ID</th>";
      echo "<th class='big_table_th'>Last Name</th>";
      echo "<th class='big_table_th'>First Name</th>";
      echo "<th class='big_table_th'>Medication</th>";
      echo "<th class='big_table_th'>Brand</th>";
      echo "<th class='big_table_th'>Expiry Date</th>";
      echo "</tr>";
      
      foreach ($takesResults as $record) {
          // Format the expiration date if it is a Unix timestamp
          $expDate = is_numeric($record['exp_date']) ? date('d/m/Y', $record['exp_date']) : htmlspecialchars($record['exp_date'], ENT_QUOTES);
          echo "<tr>";
          echo "<td class='big_table_td_custom_one'>" . htmlspecialchars($record['takes_id'], ENT_QUOTES) . "</td>";
          echo "<td class='big_table_td'>" . htmlspecialchars($record['last_name'], ENT_QUOTES) . "</td>";
          echo "<td class='big_table_td'>" . htmlspecialchars($record['first_name'], ENT_QUOTES) . "</td>";
          echo "<td class='big_table_td'>" . htmlspecialchars($record['med_name'], ENT_QUOTES) . "</td>";
          echo "<td class='big_table_td'>" . htmlspecialchars($record['brand_name'], ENT_QUOTES) . "</td>";
          echo "<td class='big_table_td'>" . htmlspecialchars($expDate, ENT_QUOTES) . "</td>";
          echo "</tr>";
      }

      $staff_id = $_SESSION['staff_id'];
      $staff_code = $_SESSION['staff_code'];
      $name = (htmlspecialchars($trip['trip_name']));
      $source = "Trip Expiration";
      $action = "$staff_code viewed $trip_id, $name";

      logAction($conn, $staff_id, $action, $source);


      echo "</table>";
  } else {
      echo "<p>No medication records found for this trip.</p>";
  }
  ?>
  
  <br>
  <a class="back_link" href="trip_management.php" class="action-link"> > Go Back</a>
</div>
</body>
</html>
