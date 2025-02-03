<?php
// export_trip_expirations.php
session_start();
// Require is better practice than include as they are "required" for the page to start
require "../server/db_connect.php";           // Adjust path as needed
require "../server/check_cookie_user.php";     // Adjust path as needed
require "../server/audit-log.php";
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Validate that a trip_id is provided
if (!isset($_GET['trip_id'])) {
    die("Trip ID is required.");
}

$trip_id = intval($_GET['trip_id']);

try {
    // Fetch trip details (similar to trip_expiration.php)
    $sql = "SELECT trip_id, trip_name, start_date, end_date, takes FROM trips WHERE trip_id = :trip_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':trip_id', $trip_id, PDO::PARAM_INT);
    $stmt->execute();
    $trip = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$trip) {
        die("Trip not found.");
    }

    // Decode the JSON array from the takes field
    $takesIDs = json_decode($trip['takes'], true);
    if (!is_array($takesIDs) || empty($takesIDs)) {
        die("No students associated with this trip.");
    }

    // Build placeholders and fetch the expiration records ordered by student's last name.
    $placeholders = implode(',', array_fill(0, count($takesIDs), '?'));

    $sqlTakes = "SELECT takes.takes_id, students.first_name, students.last_name, 
                        med.med_name, brand.brand_name, takes.exp_date
                 FROM takes 
                 INNER JOIN med ON takes.med_id = med.med_id 
                 INNER JOIN brand ON takes.brand_id = brand.brand_id 
                 INNER JOIN students ON takes.student_id = students.student_id 
                 WHERE takes.takes_id IN ($placeholders)
                 ORDER BY students.last_name ASC";

    $stmtTakes = $conn->prepare($sqlTakes);
    foreach ($takesIDs as $index => $id) {
        $stmtTakes->bindValue($index + 1, $id, PDO::PARAM_INT);
    }
    $stmtTakes->execute();
    $takesResults = $stmtTakes->fetchAll(PDO::FETCH_ASSOC);

    // Store the count of items created
    $itemCount = count($takesResults);

} catch (PDOException $e) {
    die("Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES));
}

// Create a new Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set document properties (optional)
$spreadsheet->getProperties()
    ->setCreator("Your Application Name")
    ->setTitle("Trip Expirations for " . $trip['trip_name']);

// Define header row
$headers = ['Takes ID', 'Last Name', 'First Name', 'Medication', 'Brand', 'Expiry Date'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $col++;
}

// Write data rows
$rowNum = 2;
foreach ($takesResults as $record) {
    // Format student name as "Last Name, First Name"
    $studentName = $record['last_name'] . ", " . $record['first_name'];

    // Format the expiry date if numeric
    $expDate = is_numeric($record['exp_date']) ? date('d/m/Y', $record['exp_date']) : $record['exp_date'];

    $sheet->setCellValue('A' . $rowNum, $record['takes_id']);
    $sheet->setCellValue('B' . $rowNum, $record['last_name']);
    $sheet->setCellValue('C' . $rowNum, $record['first_name']);
    $sheet->setCellValue('D' . $rowNum, $record['med_name']);
    $sheet->setCellValue('E' . $rowNum, $record['brand_name']);
    $sheet->setCellValue('F' . $rowNum, $expDate);
    $rowNum++;
}

// Set filename (for example, including the trip name and current date)
$filename = "Trip_Expirations_" . preg_replace('/\s+/', '_', $trip['trip_name']) . "_" . date('Y-m-d') . ".xlsx";

// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$staff_id = $_SESSION['staff_id'];
$staff_code = $_SESSION['staff_code'];
$action = "$staff_code created $filename with $itemCount records";
// Call to the ../server/audit-log.php function
logAction($conn, $staff_id, $action);

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
