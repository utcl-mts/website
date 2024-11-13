<?php

    // session start to be able to use session variables
    session_start();

    //include database connection
    include '../server/db_connect.php';

    $student_id = $_POST['student_id'];

    //sql statement to get student informaition
    $sql = "SELECT student_id FROM Takes WHERE student_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(1, $student_id);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $student_id = $result['student_id'];

    //sql statement from takes to get medicaition
    $sql = "SELECT med_id FROM takes WHERE student_id = ? ORDER BY med_id DESC";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(1, $student_id);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    for ($i = 0; $i < count($result); $i++) {
        echo $result[$i]['med_id'];
    }

?>