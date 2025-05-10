<?php
session_start();
require '../vendor/autoload.php'; // Ensure this path is correct
include "../server/db_connect.php";
include "../server/audit-log.php";
include "../server/check_cookie_user.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_students'])) {
    $selected_students = $_POST['selected_students'];

    if (empty($selected_students)) {
        die("No students selected.");
    }

    $placeholders = implode(',', array_fill(0, count($selected_students), '?'));

    // Query to fetch data for selected students
    $sql = "SELECT students.student_id, students.first_name, students.last_name, students.year, 
                   med.med_name, brand.brand_name, takes.exp_date, takes.current_dose, takes.min_dose
            FROM takes 
            INNER JOIN med ON takes.med_id = med.med_id 
            INNER JOIN brand ON takes.brand_id = brand.brand_id 
            INNER JOIN students ON takes.student_id = students.student_id 
            WHERE students.student_id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($selected_students);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate the filename with the current date and time
    $filename = 'student_data_' . date('Y-m-d_H-i-s') . '.xlsx';

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Student Data');

    // Add headers (row 1)
    $headers = array_keys($data[0] ?? []);
    $columnLetter = 'A'; // Columns start with 'A'
    foreach ($headers as $header) {
        $sheet->setCellValue($columnLetter . '1', $header); // Row 1 for headers
        $columnLetter++;
    }

    // Add data (starting from row 2)
    $rowIndex = 2; // Data rows start at 2
    foreach ($data as $row) {
        $columnLetter = 'A';
        foreach ($row as $key => $value) {
            if ($key === 'exp_date' && is_numeric($value)) {
                // Convert epoch to formatted date (e.g., dd/mm/yyyy)
                $formattedDate = date('d/m/Y', $value);
                $sheet->setCellValue($columnLetter . $rowIndex, $formattedDate);
            } else {
                $sheet->setCellValue($columnLetter . $rowIndex, $value);
            }
            $columnLetter++;
        }
        $rowIndex++;
    }

    // Export file
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

    $staff_id = $_SESSION['staff_id']; // Fetch from POST, not SESSION
    $staff_code = $_SESSION['staff_code']; // Staff code is correctly from SESSION
    $action = "$staff_code generated $filename";
    $source = "Generate Excel";

    logAction($conn, $staff_id, $action, $source);

    exit;
} else {
    die("Invalid request.");
}
