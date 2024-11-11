<?php

    // Start a new session or resume the existing session
    session_start();

    // Include the database connection file
    include "../dashboard/db_connect.php";

    $time = time();
    $time_left = "";

    $sql = "SELECT exp_date, takes_id FROM takes";
    $stat = $conn->prepare($sql);
    $stat->execute();
    $result = $stat->fetchAll();

    // Loop through each row checking if it is below 4 weeks, 2 weeks or expired
    while ($row = $result->fetch_assoc()) {
        // Access the specific column value
        $value = $row["exp_date"];

        // Compare the value
        if ($value < $time + 2419200) {
            $time_left = "Less than 4 weeks";
        }
        if ($value < $time + 1209600) {
            $time_left = "Less than 2 weeks";
        }
        if ($value < $time) {
            $time_left = "Expired";
        }
        echo $row["takes_id"] .$time_left. "<br>";
    }

    $sql = "SELECT takes_id FROM takes WHERE current_does < min_does ";
    $stat = $conn->prepare($sql);
    $stat->execute();
    $result = $stat->fetchAll();

    while ($row = $result->fetch_assoc()) {
        echo $row["takes_id"] ."Below minimum doses". "<br>";
    }


?>
