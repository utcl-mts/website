<?php
session_start();

// Check for valid session and cookie
if (!isset($_SESSION['ssnlogin']) || !isset($_COOKIE['cookies_and_cream'])) {
    header("Location: ../index.html");
    exit();
}

include "../server/db_connect.php";
include "../server/navbar/admin_dashboard.php";

try {

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['staff_id'])) {
        $staff_id = $_GET['staff_id'];

        // Fetch user details
        $query = "SELECT first_name, last_name, email, staff_code FROM staff WHERE staff_id = :staff_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("User not found.");
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['staff_id'])) {
        $staff_id = $_POST['staff_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $staff_code = $_POST['staff_code'];

        // Validate inputs
        if (empty($first_name) || empty($last_name) || empty($email) || empty($staff_code)) {
            $error = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } else {
            // Update user details
            $query = "UPDATE staff SET first_name = :first_name, last_name = :last_name, email = :email, staff_code = :staff_code WHERE staff_id = :staff_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':staff_code', $staff_code);
            $stmt->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $success = "Details updated successfully.";
            } else {
                $error = "Failed to update details.";
            }
        }
    } else {
        $error = "Invalid request.";
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<link rel="stylesheet" href="../assets/style/style.css">
<body class="full_page_styling">
<title>Hours Tracking - Dashboard</title>
<div>

<h1>Edit User Details</h1>

<?php if (isset($error)): ?>
    <div class="error-banner">
        <div class="error-header">
            <h2>Error</h2>
        </div>
        <div class="error-content">
            <p><?php echo htmlspecialchars($error); ?></p>
        </div>
    </div>
    <br>  
<?php elseif (isset($success)): ?>
    <div class="success-banner">
        <div class="success-header">
            <h2>Success</h2>
        </div>
        <div class="success-content">
            <p><?php echo htmlspecialchars($success); ?></p>
        </div>
    </div>
    <br>    
<?php endif; ?>

<?php if (!isset($success) && isset($user)): ?>
    <form action="edit_user.php" method="POST">
        <input type="hidden" name="staff_id" value="<?php echo htmlspecialchars($staff_id); ?>">
        <div>
            <div class='text-element'>Enter first name</div>
            <div class='text-element-faded'>Example: Joe</div>
            <input class='text_input' type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
        </div>
        <br>
        <div>
            <div class='text-element'>Enter last name</div>
            <div class='text-element-faded'>Example: Bloggs</div>
            <input class='text_input' type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
        </div>
        <br>
        <div>
            <div class='text-element'>Enter email</div>
            <div class='text-element-faded'>Example: joe.bloggs@utcleeds.co.uk</div>
            <input class='text_input' type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <br>
        <div>
            <div class='text-element'>Enter staff code</div>
            <div class='text-element-faded'>Example: JBL</div>
            <input class='smaller_int_input' type="text" id="staff_code" name="staff_code" value="<?php echo htmlspecialchars($user['staff_code']); ?>" required>
        </div>
        <br>
        <button class='submit' type="submit">Update Details</button>
    </form>
<?php endif; ?>

<a class='back_link' href="staff_home.php"> < Go Back</a>

</body>
</html>
