<?php
// Start a new session
session_start();

// Include the database connection file
include "../server/db_connect.php";

$time = time();

// Arrays for which medication is expired
$expired = [];
$less_than_2_weeks = [];
$less_than_4_weeks = [];
$below_minimum_doses = [];

// Fetch medid and expdate
$sql = "SELECT exp_date, takes_id FROM takes";
$stat = $conn->prepare($sql);
$stat->execute();
$result = $stat->fetchAll(PDO::FETCH_ASSOC);

// put each med into right array
foreach ($result as $row) {
    $expiry_date = $row["exp_date"];
    $takes_id = $row["takes_id"];

    if ($expiry_date < $time) {
        $expired[] = $takes_id;
    } elseif ($expiry_date < $time + 1209600) { // Less than 2 weeks
        $less_than_2_weeks[] = $takes_id;
    } elseif ($expiry_date < $time + 2419200) { // Less than 4 weeks
        $less_than_4_weeks[] = $takes_id;
    }
}

// get meds below minimum dose
$sql = "SELECT takes_id FROM takes WHERE current_dose < min_dose";
$stat = $conn->prepare($sql);
$stat->execute();
$dose_result = $stat->fetchAll(PDO::FETCH_ASSOC);

foreach ($dose_result as $row) {
    $below_minimum_doses[] = $row["takes_id"] . " - Below minimum doses";
}
?>
<link rel="stylesheet" href="../style.css">
<body>
<div class="container">
    <div class="navbar">
        <img id="logo" src="../assets/UTCLeeds.svg" alt="UTC Leeds">
        <ul>
            <li><a href="../index.html">Home</a></li>
            <li><a href="bigtable.html">Table</a></li>
            <li class="logout"><a>Logout</a></li>
        </ul>
        <h1 id="med_tracker">Med Tracker</h1>
    </div>

    <div class="notification_container">
        <!-- Expired Table -->
        <table class="notification_table" id="out_date">
            <tr>
                <th><h2>Expired</h2></th>
            </tr>
            <?php foreach ($expired as $medication): ?>
                <tr><td><?php echo $medication; ?></td></tr>
            <?php endforeach; ?>
        </table>

        <!-- Less than 2 Weeks Table -->
        <table class="notification_table" id="near_out_date">
            <tr>
                <th><h2>Less than 2 Weeks</h2></th>
            </tr>
            <?php foreach ($less_than_2_weeks as $medication): ?>
                <tr><td><?php echo $medication; ?></td></tr>
            <?php endforeach; ?>
        </table>

        <!-- Less than 4 Weeks Table -->
        <table class="notification_table" id="far_out_date">
            <tr>
                <th><h2>Less than 4 Weeks</h2></th>
            </tr>
            <?php foreach ($less_than_4_weeks as $medication): ?>
                <tr><td><?php echo $medication; ?></td></tr>
            <?php endforeach; ?>
        </table>

        <!-- Below Minimum Doses Table -->
        <table class="notification_table" id="below_min_dose">
            <tr>
                <th><h2>Below Minimum Doses</h2></th>
            </tr>
            <?php foreach ($below_minimum_doses as $medication): ?>
                <tr><td><?php echo $medication; ?></td></tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
</body>
</html>
