<?php

    // Start session to use session variables
    session_start();

    // Include database connection
    include '../server/db_connect.php';

?>

<html lang="en">

  <head>

      <meta charset="UTF-8">
      <title>Audit Dashboard</title>
      <link rel="stylesheet" href="../style.css">

  </head>

  <body>

    <div class="container">

      <!-- universal nav bar-->
      <div class="navbar">

        <img id="logo" src="/assets/UTCLeeds.svg" alt="UTC Leeds">

        <ul>
          <li><a href="/index.html">Home </a></li>
          <li><a href="bigtable.html"> Table </a></li>
          <li class="logout"><a>Logout</a></li>
        </ul>

        <H1 id="med_tracker">Med Tracker</H1>

      </div>

    </div>

  </body>

</html>

<?php

    

?>