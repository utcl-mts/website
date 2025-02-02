<?php
require '../vendor/autoload.php';
session_start();

include "../server/check_cookie_admin.php";
include "../server/db_connect.php";
?>

<div>
    <ul class="nav_bar">
        <div class="nav_left">
            <li class="navbar_li"><a href="student_management.php">View All Students</a></li>
            <li class="navbar_li"><a href="progress_students.php">Progress Students</a></li>
            <li class="navbar_li"><a href="create_single.php">Create Single Student</a></li>
            <li class="navbar_li"><a href="bulk_upload.php">Bulk Upload</a></li>
            <li class="navbar_li"><a class="active" href="export_students.php">Export All Students</a></li>
        </div>
    </ul>
</div>

<?php

// Import PhpSpreadsheet classes at the top of the file
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Fetch brands data
$query = "SELECT * FROM students";
$stmt = $conn->prepare($query);
$stmt->execute();
$brandsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($brandsData)) {
    // Generate timestamp for file naming
    $timestamp = date('Y-m-d_H-i-s');

    // Paths for the files to be included in the zip
    $csvFile = "student_data.cdn";
    $excelFile = "student_data.xlsx";
    $zipFile = "student_data_$timestamp.zip";

    // Generate CSV file
    $csvHandle = fopen($csvFile, 'w');
    fputcsv($csvHandle, array_keys($brandsData[0])); // Add header row
    foreach ($brandsData as $row) {
        fputcsv($csvHandle, $row);
    }
    fclose($csvHandle);

    // Generate Excel file using PhpSpreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->fromArray(array_merge([array_keys($brandsData[0])], $brandsData), NULL, 'A1');

    $writer = new Xlsx($spreadsheet);
    $writer->save($excelFile);

    // Create a zip archive
    $zip = new ZipArchive();

    if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        $zip->addFile($csvFile, basename($csvFile));
        $zip->addFile($excelFile, basename($excelFile));
        $zip->close();

        // Set headers to prompt download
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($zipFile) . '"');
        header('Content-Length: ' . filesize($zipFile));

        // Output the file
        readfile($zipFile);

        // Clean up temporary files
        unlink($csvFile);
        unlink($excelFile);
        unlink($zipFile);
    } else {
        echo "Failed to create zip file.";
    }
} else {
    echo "No brands data available to export.";
}

$conn = null; // Close the database connection
?>
