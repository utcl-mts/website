<?php
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../index.html");
    exit();
}

include "../server/db_connect.php";

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$password = $_POST['password'];
$c_password = $_POST['c_password'];
$group = $_POST['group'];

if($password!=$c_password){
    header("refresh:5; url=create_user_form.php");
    echo '<br>';
    echo"Your passwords do not match";
} else {
    try {
        $sql = "SELECT email FROM staff WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1,$email);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result){
            header("refresh:5; url=create_user_form.php");
            echo '<br>';
            echo "An Account with this email already exists. Try again!";

        } else {
            try {
                $hpswd = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO staff (first_name, last_name, email, password, `group`) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1,$first_name);
                $stmt->bindParam(2,$last_name);
                $stmt->bindParam(3,$email);
                $stmt->bindParam(4,$hpswd);
                $stmt->bindParam(5,$group);

                $stmt->execute();
                header("refresh:5; url=admin_home.php");
                echo '<br>';
                echo "Successfully registered";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }

        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}