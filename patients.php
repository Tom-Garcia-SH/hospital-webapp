<?php
    /* Programmer ID: 87
       This is the Patient Menu page.
        It allows users to view and manage patient records.
        The page includes two main forms:
        - One for viewing the list of patients (`patient_list.php`).
        - One for managing patients (`patient_manager.php`).

        External CSS is linked for styling (`styles.css`). */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Menu</title>
    <!-- Link to global styles -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="header-container">
        <h1>Patient Menu</h1>
        <a href="mainmenu.php" class="back-button">Main Menu</a>
    </div>

    <div class="button-container">
        <form action="patient_list.php" method="get">
            <button type="submit">View Patient List</button>
        </form>
        <form action="patient_manager.php" method="get">
            <button type="submit">Manage Patients</button>
        </form>
    </div>
</body>
</html>