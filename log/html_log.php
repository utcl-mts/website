<?php

    echo "<html lang='en'>";

        echo "<head>";

            echo "<meta charset='UTF-8'>";
            echo "<title>Log</title>";
            echo "<link rel='stylesheet' href='../style.css'>";

        echo "</head>";

        echo "<body>";

            echo "<div class='container'>";

                //universal nav bar
                echo "<div class='navbar'>";

                    echo "<img id='logo' src='../assets/UTCLeeds.svg' alt='UTC Leeds'>";

                    echo "<h1 id='med_tracker'>Med Tracker</h1>";

                    echo "<ul>";

                        echo "<li><a href='../dashboard/dashboard.php'>Home</a></li>";
                        echo "<li><a href='../insert_data/insert_data_home.php'>Insert Data</a></li>";
                        echo "<li><a href='../bigtable/bigtable.php'>Student Medication</a></li>";
                        echo "<li><a href='../administer/administer.html'>Administer Medication</a></li>";
                        echo "<li><a href='../whole_school/whole_school.php'>Whole School Medication</a></li>";
                        echo "<li class='logout'><a>Logout</a></li>";

                    echo "</ul>";

                echo "</div>";

                // choose student for log page
                echo "<div class='enter_student_log'>";

                    // sends to php to output student to select from database
                    echo "<form action='choose_student.php' method='post'>";

                        echo "<table>";

                            echo "<tr>";
                                echo "<td><label for='sfn'>Student First name: </label></td>";
                                echo "<td><input type='text' id='sfn' name='student_fname' placeholder='Enter Student First Name' required></td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<td><label for='syg'>Student Year Group: </label></td>";
                                echo "<td><input type='text' id='syg' name='student_yeargroup' placeholder='Enter Student Year Group' required></td>";
                            echo "</tr>";

                            echo "<tr>";
                                echo "<td><button type='submit'>Submit</button></td>";
                            echo "</tr>";

                        echo "</table>";

                    echo "</form>";

                echo "</div>";

            echo "</div>";

        echo "</body>";

    echo "</html>";

?>