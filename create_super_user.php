<?php
// Use the pre-made database connection
//require 'server/db_connect.php';
include "server/db_connect.php";
include "server/audit-log.php";
include "server/functions.php";

if (root_checker()){
    header("refresh:0; url=index.php");
    echo "Super user already created";
}elseif ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sql = "INSERT INTO staff (first_name, last_name, email, password, `group`, staff_code) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1,$_POST['first_name']);
    $stmt->bindParam(2,$_POST['last_name']);
    $stmt->bindParam(3,$_POST['email']);
    $hash_password = password_hash($password = $_POST['password'], PASSWORD_DEFAULT);
    $stmt->bindParam(4,$hash_password);
    $group = "ROOT";
    $stmt->bindParam(5,$group);
    $stmt->bindParam(6,$_POST['staff_code']);

    $stmt->execute();
    header("refresh:0; url=index.php");
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/style/style.css">
    <title>Document</title>
</head>
<body class="full_page_styling">
<h1>Create a Super User</h1>
<form action="" method="POST">
    <div class='text-element'>Enter first name *</div>
    <div class='text-element-faded'>Example: Joe</div>
    <input class="text_input" type="text" name="first_name" required>
    <br><br>
    <div class='text-element'>Enter last name *</div>
    <div class='text-element-faded'>Example: Bloggs</div>
    <input class="text_input" type="text" name="last_name" required>
    <br><br>
    <div class='text-element'>Enter email *</div>
    <div class='text-element-faded'>Example: joe.bloggs@utcleeds.co.uk</div>
    <input class="text_input" type="text" name="email" required>
    <br><br>
    <div class='text-element'>Enter password *</div>
    <input class="text_input" type="text" name="password" required>
    <br><br>
    <div class='text-element'>Enter staff code *</div>
    <div class='text-element-faded'>Examole: JBL</div>
    <input class="text_input" type="text" name="staff_code" required>
    <br><br>
    <input class="submit" type='submit' name='submit' value='Continue'>
</form>
</body>
</html>