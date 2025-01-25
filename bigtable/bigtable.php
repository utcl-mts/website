
<!DOCTYPE html>
<html>
<head>
    <title>Hours Tracking - Student Medication</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body>
<div class="full_page_styling">
<?php
    include "../server/db_connect.php";
    include "../server/navbar/bigtable.php";
?>


    <br><br>

    <div id="search-bar">
        <form method="GET" action="">
            <input
                type="text"
                name="search"
                class="search_bar"
                placeholder="Search by student name, medication, brand, or year group"
                value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>"
            >
            <button class="submit" type="submit">Search</button>
        </form>
    </div>

    <br><br>

    <?php
    $results_per_page = 15;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $start_from = ($page - 1) * $results_per_page;
    $search_term = trim($_GET['search'] ?? '');

    try {
        $total_sql = "SELECT COUNT(*) AS total_records FROM takes 
                      INNER JOIN med ON takes.med_id = med.med_id 
                      INNER JOIN brand ON takes.brand_id = brand.brand_id 
                      INNER JOIN students ON takes.student_id = students.student_id 
                      WHERE CONCAT(students.first_name, ' ', students.last_name) LIKE :search 
                      OR med.med_name LIKE :search OR brand.brand_name LIKE :search OR students.year LIKE :search";
        $total_stmt = $conn->prepare($total_sql);
        $search_param = '%' . $search_term . '%';
        $total_stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
        $total_stmt->execute();
        $total_records = $total_stmt->fetch(PDO::FETCH_ASSOC)['total_records'];

        $total_pages = ceil($total_records / $results_per_page);

        $sql = "SELECT takes.takes_id, students.student_id, students.first_name, students.last_name, students.year, 
                       med.med_name, brand.brand_name, takes.exp_date, takes.current_dose, takes.min_dose
                FROM takes 
                INNER JOIN med ON takes.med_id = med.med_id 
                INNER JOIN brand ON takes.brand_id = brand.brand_id 
                INNER JOIN students ON takes.student_id = students.student_id 
                WHERE CONCAT(students.first_name, ' ', students.last_name) LIKE :search 
                OR med.med_name LIKE :search OR brand.brand_name LIKE :search OR students.year LIKE :search 
                LIMIT :limit OFFSET :offset";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $results_per_page, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $start_from, PDO::PARAM_INT);
        $stmt->execute();

        $custom_headings = [
            'takes_id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'year' => 'Year',
            'med_name' => 'Medication Name',
            'brand_name' => 'Brand Name',
            'exp_date' => 'Expiry Date',
            'current_dose' => 'Current Dose',
            'min_dose' => 'Minimum Dose',
        ];

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<div id='bigt'>";
        if ($results) {
            echo "<table class='big_table'>";
            echo "<tr>";
            foreach ($custom_headings as $heading) {
                echo "<th class='big_table_th'>" . htmlspecialchars($heading, ENT_QUOTES) . "</th>";
            }
            echo "<th class='big_table_th'>Actions</th>";
            echo "<th class='big_table_th'>Notes</th>";
            echo "</tr>";

            foreach ($results as $row) {
                echo "<tr>";
                foreach ($custom_headings as $column => $heading) {
                    $value = $row[$column] ?? '';
                    if ($column === 'takes_id') {
                        $value = "<b>" . htmlspecialchars($value, ENT_QUOTES) . "</b>";
                    } elseif ($column === 'exp_date' && is_numeric($value)) {
                        $value = date('d/m/y', $value);
                    }
                    echo "<td class='big_table_td'>" . $value . "</td>";
                }
                echo "<td>
                    <div class='centered-form'>
                        <button class='table_button decrement-btn' 
                            data-takes-id='" . htmlspecialchars($row['takes_id'], ENT_QUOTES) . "' 
                            data-current-dose='" . htmlspecialchars($row['current_dose'], ENT_QUOTES) . "'
                            " . ($row['current_dose'] <= 0 ? "disabled" : "") . ">
                            Decrement Dose
                        </button>
                    </div>
                </td>";
                echo "<td>
                    <div class='centered-form'>
                        <form method='GET' action='create_notes.php'>
                            <input type='hidden' name='student_id' value='" . htmlspecialchars($row['student_id'] ?? '', ENT_QUOTES) . "'>
                            <input type='hidden' name='takes_id' value='" . htmlspecialchars($row['takes_id'], ENT_QUOTES) . "'>
                            <button class='table_button' type='submit'>
                                Create Notes
                            </button>
                        </form>
                        <form method='GET' action='view_notes.php'>
                            <input type='hidden' name='student_id' value='" . htmlspecialchars($row['student_id'] ?? '', ENT_QUOTES) . "'>
                            <input type='hidden' name='takes_id' value='" . htmlspecialchars($row['takes_id'], ENT_QUOTES) . "'>
                            <button class='table_button' type='submit'>
                                View Notes
                            </button>
                        </form>
                    </div>
                </td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "No records found.";
        }
        echo "</div>";

        echo "<div class='pagination'>";
        if ($page > 1) {
            echo "<a href='?search=" . urlencode($search_term) . "&page=" . ($page - 1) . "'>Previous</a>";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                echo "<span class='active'>$i</span>";
            } else {
                echo "<a href='?search=" . urlencode($search_term) . "&page=$i'>$i</a>";
            }
        }

        if ($page < $total_pages) {
            echo "<a href='?search=" . urlencode($search_term) . "&page=" . ($page + 1) . "'>Next</a>";
        }
        echo "</div>";
    } catch (PDOException $e) {
        die("Database error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES));
    }
    ?>

    <!-- Decrement Popup -->
    <div id="decrementPopup" class="popup">
        <div class="popup-content">
            <span class="popup-close">&times;</span>
            <h1>Decrement Doses</h1>
            <form id="decrementForm" method="POST" action="doses.php">
                <input type="hidden" name="take_id" id="popupTakeId">
                <div class='text-element'>Enter number of doses to decrement</div>
                <div class='text-element-faded'>Example: 3</div>
                <input class="smaller_int_input" type="number" id="decrementAmount" name="decrement_amount" min="1" max="" required>
                <p id="currentDoseInfo"></p>
                <button type="submit" class="submit">Decrement</button>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const popup = document.getElementById('decrementPopup');
        const closeBtn = document.querySelector('.popup-close');
        const decrementBtns = document.querySelectorAll('.decrement-btn');

        decrementBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const takesId = this.getAttribute('data-takes-id');
                const currentDose = parseInt(this.getAttribute('data-current-dose'));

                document.getElementById('currentDoseInfo').textContent = `Current Doses: ${currentDose}`;

                document.getElementById('popupTakeId').value = takesId;
                document.getElementById('decrementAmount').max = currentDose;
                popup.style.display = 'block';
            });
        });

        closeBtn.addEventListener('click', function() {
            popup.style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target === popup) {
                popup.style.display = 'none';
            }
        });
    });
    </script>
</div>
</body>
</html>