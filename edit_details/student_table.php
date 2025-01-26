<!DOCTYPE html>
<html>
<head>
    <title>Hours Tracking - Student Medication</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body>
<div class="full_page_styling">

<?php
session_start();
    include "../server/db_connect.php";
    include "../server/navbar/student_management.php";

// Pagination and search setup
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;

// Base query
$query = "SELECT * FROM students";
$params = [];

// Add search condition if applicable
if ($search !== '') {
    $query .= " WHERE first_name LIKE :search OR last_name LIKE :search OR year LIKE :search";
    $params['search'] = "%$search%";
}

// Count total records for pagination
$count_query = str_replace("SELECT *", "SELECT COUNT(*) as total", $query);
$stmt = $conn->prepare($count_query);
$stmt->execute($params);
$total_records = $stmt->fetch()['total'];
$total_pages = ceil($total_records / $records_per_page);

// Fetch data with pagination
$query .= " LIMIT :offset, :limit";
$stmt = $conn->prepare($query);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$students = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Table</title>

</head>
<body>

    <br>

    <div>
    <ul class="nav_bar">
            <div class="nav_left">
                <li class="navbar_li"><a class='active' href="active_records.php">View Students</a></li>
                <li class="navbar_li"><a href="progress_students.php">Progress Students</a></li>
            </div>
        </ul>
    </div>

    <h1>Students Table</h1>
    <form method="get" action="">
        <input type="text" class="text_input2" name="search" placeholder="Search by First Name, Last Name or Year Group" value="<?php echo htmlspecialchars($search); ?>">
        <button class="submit" type="submit">Search</button>
    </form>

    <table class='big_table'>
        <thead>
            <tr>
                <th class='big_table_th'>Student ID</th>
                <th class='big_table_th'>First Name</th>
                <th class='big_table_th'>Last Name</th>
                <th class='big_table_th'>Year</th>
                <th class='big_table_th'>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($students) > 0): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td class='big_table_td_custom_one'><?php echo htmlspecialchars($student['student_id']); ?></td>
                        <td class='big_table_td'><?php echo htmlspecialchars($student['first_name']); ?></td>
                        <td class='big_table_td'><?php echo htmlspecialchars($student['last_name']); ?></td>
                        <td class='big_table_td'><?php echo htmlspecialchars($student['year']); ?></td>
                        <td class='big_table_td'>
                        <div class='centered-form'>
                            <form method="GET" action="edit_student.php">
                                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['student_id']); ?>">
                                <button class="table_button" type="submit">Edit</button>
                            </form>
                        </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td class=big_table_td" colspan="4">No records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>
</body>
</html>
