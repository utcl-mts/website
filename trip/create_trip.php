<?php
// create_trip.php
session_start();
include "../server/db_connect.php";           // Adjust path as needed
include "../server/check_cookie_user.php";     // Adjust path as needed
include "../server/navbar/trip_management.php";     // Adjust path as needed

// Retrieve the selected students from the session
$selected_students = $_SESSION['selected_students'] ?? [];

// If no students have been selected, redirect back to selection page
if (empty($selected_students)) {
    header("Location: select_students.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Trip</title>
  <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body class="full_page_styling">
<div >
    <br>

    <div>
        <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a href="trip_management.php">View All Trips</a></li>
                <li class="navbar_li"><a class='active' href="select_students.php">Create a new trip</a></li>
            </div>
        </ul>
    </div>
  <h1>Create a New Trip</h1>
  <!-- Display the selected student IDs -->
  <p><strong>Selected Student IDs:</strong> <?php echo implode(", ", $selected_students); ?></p>
  
  <!-- Trip Creation Form -->
  <form method="POST" action="save_trip.php">
    <!-- Trip Details -->
      <div class='text-element'>Enter trip name:</div>
      <div class='text-element-faded'>Example: IWEX - Spain</div>
      <input class="text_input" type="text" name="trip_name" id="trip_name" required>
      <br><br>
      <div class='text-element'>Enter start date:</div>
      <div class='text-element-faded'>Example: 02/05/2025</div>
      <input class="temp_date_field" type="date" name="start_date" id="start_date" required>
      <br><br>
      <div class='text-element'>Enter end date:</div>
      <div class='text-element-faded'>Example: 05/05/2025</div>
      <input class="temp_date_field" type="date" name="end_date" id="end_date" required>

    <!-- Hidden field with the JSON-encoded selected students -->
    <input type="hidden" name="selected_students_json" value='<?php echo json_encode($selected_students); ?>'>
    
    <br><br>
    <button class="submit" type="submit">Create Trip</button>
  </form>
</div>
</body>
</html>
