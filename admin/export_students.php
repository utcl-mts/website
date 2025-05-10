<?php
require '../vendor/autoload.php';
session_start();

include "../server/check_cookie_admin.php";
include "../server/db_connect.php";
require "../server/audit-log.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Fetch students data
$query = "SELECT * FROM students";
$stmt = $conn->prepare($query);
$stmt->execute();
$studentsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($studentsData)) {
    $timestamp = date('Y-m-d_H-i-s');
    $csvFile = "student_data.csv";
    $excelFile = "student_data.xlsx";
    $zipFile = "student_data_$timestamp.zip";

    // Generate CSV file
    $csvHandle = fopen($csvFile, 'w');
    if ($csvHandle === false) {
        die("Error: Unable to create CSV file.");
    }
    fputcsv($csvHandle, array_keys($studentsData[0]));
    foreach ($studentsData as $row) {
        fputcsv($csvHandle, $row);
    }
    fclose($csvHandle);

    // Generate Excel file
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->fromArray(array_merge([array_keys($studentsData[0])], $studentsData), NULL, 'A1');

    $writer = new Xlsx($spreadsheet);
    $writer->save($excelFile);

    // Ensure files exist before zipping
    if (!file_exists($csvFile) || !file_exists($excelFile)) {
        die("Error: CSV or Excel file was not created.");
    }

    // Create ZIP archive
    $zip = new ZipArchive();
    if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        die("Error: Unable to create ZIP file.");
    }
    $zip->addFile($csvFile, basename($csvFile));
    $zip->addFile($excelFile, basename($excelFile));
    $zip->close();

    // Ensure ZIP file exists before sending
    if (!file_exists($zipFile)) {
        die("Error: ZIP file was not created.");
    }

    // Send ZIP file for download
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . basename($zipFile) . '"');
    header('Content-Length: ' . filesize($zipFile));
    flush();
    readfile($zipFile);

    // Audit log
    $staff_id = $_SESSION['staff_id'];
    $staff_code = $_SESSION['staff_code'];
    $action = "$staff_code created $zipFile with $csvFile, $excelFile";
    $source = "Export Students";

    logAction($conn, $staff_id, $action, $source);

    // Cleanup temporary files
    unlink($csvFile);
    unlink($excelFile);
    unlink($zipFile);
} else {
    echo "No student data available to export.";
}

$conn = null; // Close database connection
?>
