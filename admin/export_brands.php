<?php
require '../vendor/autoload.php';
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../../index.html");
    exit();
}

include "../server/db_connect.php";

// Import PhpSpreadsheet classes at the top of the file
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Fetch brands data
$query = "SELECT * FROM brand";
$stmt = $conn->prepare($query);
$stmt->execute();
$brandsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($brandsData)) {
    // Generate timestamp for file naming
    $timestamp = date('Y-m-d_H-i-s');

    // Paths for the files to be included in the zip
    $csvFile = "brands_data.csv";
    $excelFile = "brands_data.xlsx";
    $zipFile = "brands_data_$timestamp.zip";

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
