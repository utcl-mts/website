<?php
// select_students.php
session_start();
include "../server/db_connect.php";           // Adjust path as needed
include "../server/check_cookie_user.php";     // Adjust path as needed
include "../server/navbar/trip_management.php";     // Adjust path as needed

// Initialize the session variable for selected students if not already set
if (!isset($_SESSION['selected_students'])) {
    $_SESSION['selected_students'] = [];
}

// Get the current search term and page from the query string (if any)
$search_term = trim($_GET['search'] ?? '');
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$results_per_page = 10;
$start_from = ($page - 1) * $results_per_page;
$search_param = '%' . $search_term . '%';

// Process the form submission from this page (to add selections to the session)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_students'])) {
    // Merge new selections with those already stored (avoid duplicates)
    $newSelections = $_POST['selected_students'];
    $_SESSION['selected_students'] = array_unique(array_merge($_SESSION['selected_students'], $newSelections));
    // Optionally, display a message
    $message = "Selected students added. You currently have " . count($_SESSION['selected_students']) . " student(s) selected.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Students for Trip</title>
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
    <h1>Select Students for Your Trip</h1>

    <!-- Optional: Show current selections -->
    <?php if(isset($message)): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if(!empty($_SESSION['selected_students'])): ?>
        <p><strong>Currently selected student IDs:</strong> <?php echo implode(", ", $_SESSION['selected_students']); ?></p>
    <?php endif; ?>
  
  <!-- Search Form for Students -->
  <div id="search-bar">
    <form method="GET" action="">
      <input type="text" name="search" class="text_input2" placeholder="Search by student name, medication, brand, or year group"
             value="<?php echo htmlspecialchars($search_term, ENT_QUOTES); ?>">
      <button class="submit" type="submit">Search</button>
    </form>
  </div>
  
  <br>
  
  <!-- Student Selection Form -->
  <!-- Notice that this form is only used to add the selected students to the session -->
  <form method="POST" action="select_students.php<?php echo '?search=' . urlencode($search_term) . '&page=' . $page; ?>">
    <?php
    try {
        $sql = "SELECT takes.takes_id, students.student_id, students.first_name, students.last_name, students.year, 
                       med.med_name, brand.brand_name, takes.exp_date, takes.current_dose, takes.min_dose
                FROM takes 
                INNER JOIN med ON takes.med_id = med.med_id 
                INNER JOIN brand ON takes.brand_id = brand.brand_id 
                INNER JOIN students ON takes.student_id = students.student_id 
                WHERE CONCAT(students.first_name, ' ', students.last_name) LIKE :search 
                   OR med.med_name LIKE :search 
                   OR brand.brand_name LIKE :search 
                   OR students.year LIKE :search 
                ORDER BY students.last_name ASC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $results_per_page, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $start_from, PDO::PARAM_INT);
        $stmt->execute();
        
        $custom_headings = [
            'takes_id'     => 'ID',
            'first_name'   => 'First Name',
            'last_name'    => 'Last Name',
            'year'         => 'Year',
            'med_name'     => 'Medication Name',
            'brand_name'   => 'Brand Name',
            'exp_date'     => 'Expiry Date',
            'current_dose' => 'Current Dose',
            'min_dose'     => 'Minimum Dose',
        ];
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<table class='big_table'>";
        echo "<tr>";
        // Checkbox to select all students on this page
        echo "<th class='big_table_th'><input type='checkbox' id='select_all'></th>";
        foreach ($custom_headings as $heading) {
            echo "<th class='big_table_th'>" . htmlspecialchars($heading, ENT_QUOTES) . "</th>";
        }
        echo "</tr>";
        
        foreach ($results as $row) {
            echo "<tr>";
            // Here we use the unique takes_id as the value; adjust as needed.
            echo "<td class='big_table_td'>
                    <input type='checkbox' name='selected_students[]' value='" . htmlspecialchars($row['takes_id'], ENT_QUOTES) . "'>
                  </td>";
            foreach ($custom_headings as $column => $heading) {
                $value = $row[$column] ?? '';
                if ($column === 'exp_date' && is_numeric($value)) {
                    $value = date('d/m/Y', $value);
                }
                echo "<td class='big_table_td'>" . htmlspecialchars($value, ENT_QUOTES) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        die("Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES));
    }
    ?>
    <br><br>
    <!-- Button to add selections from this page to the session -->
    <button class="submit" type="submit">Add Selected to Trip</button>
  </form>
  
  <br>
  <!-- Pagination Controls for the Student Table -->
  <div class="pagination">
    <?php
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM takes 
                                INNER JOIN students ON takes.student_id = students.student_id 
                                WHERE CONCAT(students.first_name, ' ', students.last_name) LIKE :search");
        $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
        $stmt->execute();
        $total_records = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $total_pages = ceil($total_records / $results_per_page);
        
        if ($page > 1) {
            echo "<a href='?search=" . urlencode($search_term) . "&page=" . ($page - 1) . "'>Previous</a>";
        }
        
        for ($i = 1; $i <= $total_pages; $i++) {
            $activeClass = ($i == $page) ? "class='active'" : "";
            echo "<a href='?search=" . urlencode($search_term) . "&page=" . $i . "' $activeClass>" . $i . "</a>";
        }
        
        if ($page < $total_pages) {
            echo "<a href='?search=" . urlencode($search_term) . "&page=" . ($page + 1) . "'>Next</a>";
        }
    } catch (PDOException $e) {
        die("Pagination error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES));
    }
    ?>
  </div>
  
  <br>
  <!-- Once finished selecting, the user can proceed to the Trip Creation page -->
  <a href="create_trip.php" class="submit">Proceed to Create Trip</a>
</div>

<script>
// JavaScript to allow selecting all checkboxes on this page
document.getElementById('select_all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="selected_students[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = this.checked);
});
</script>
</body>
</html>
