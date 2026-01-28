<?php
/* Programmer ID: 87
 This HTML file represents the main page of the Hospital Management System. 
 It provides a user interface for navigating to different sections of the system which manage patient, doctor, and nurse operations respectively.
 The page includes buttons that link to respective pages for further management of hospital records.
*/
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management System</title>
    <!-- Link to global styles -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Hospital Management System</h1>
    <div class="button-container">
        <form action="patients.php" method="get">
            <button type="submit">Patient Menu</button>
        </form>
        <form action="doctors.php" method="get">
            <button type="submit">Show Doctors</button>
        </form>
        <form action="nurses.php" method="get">
            <button type="submit">Show Nurses</button>
        </form>
    </div>
</body>
</html>