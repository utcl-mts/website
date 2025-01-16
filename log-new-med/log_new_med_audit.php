<?php

    // Start a new session
    session_start();
    // Include the database connection file
    include "../server/db_connect.php";

    $sid = $_POST['student_id'];
    $max_dose = $_POST['max_dose'];
    $min_dose = $_POST['min_dose'];
    $expiry = $_POST['expiry'];
    $med = $_POST['meds'];
    $brand = $_POST['brand'];
    $strength = $_POST['strength'];

    $sql = "INSERT into takes VALUES (?,?,?,?,?,?,?)"

    $stmt = $conn->prepare($sql);
    $stmt ->bindParam(1, $sid, PDO::PARAM_STR);
    $stmt ->bindParam(2, $max_dose, PDO::PARAM_STR);
    $stmt ->bindParam(3, $min_dose, PDO::PARAM_STR);
    $stmt ->bindParam(4, $expiry, PDO::PARAM_STR);
    $stmt ->bindParam(5, $med, PDO::PARAM_STR);
    $stmt ->bindParam(6, $brand, PDO::PARAM_STR);
    $stmt ->bindParam(7, $strength, PDO::PARAM_STR);
    $stmt ->execute();
?>