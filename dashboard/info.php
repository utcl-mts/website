<link rel="stylesheet" href="../style.css">
<body>
<div class="container">
    <div class="navbar">
        <img id="logo" src="../assets/UTCLeeds.svg" alt="UTC Leeds">
        <h1 id="med_tracker">Med Tracker</h1>
        <ul>
            <li><a href="../dashboard/dashboard.php">Home</a></li>
            <li><a href="../insert_data/insert_data.php">Insert Data</a></li>
            <li><a href="../bigtable/bigtable.php">Student Medication</a></li>
            <li><a href="../administer/administer.html">Administer Medication</a></li>
            <li><a href="../whole_school/whole_school.php">Whole School Medication</a></li>
            <li class="logout"><a>Logout</a></li>
        </ul>
    </div>
<?php
if (isset($_POST['notes'])) {
    $notes = htmlspecialchars($_POST['notes']);
    echo "<h1>Notes</h1>";
    echo "<p>$notes</p>";
    echo "<a href='javascript:history.back()'>Go Back</a>";
} else {
    echo "No notes available.";
}
?>
