<?php
/* Programmer ID: 87
       This page displays doctors and their patient assignments.
       It contains two main sections:
       - A list of doctors without assigned patients.
       - A list of doctors with assigned patients, showing both the doctor and patient details.
       
       The following steps are performed:
       1. A query is run to fetch doctors who do not have any assigned patients.
       2. Another query is run to fetch doctors who have at least one assigned patient.
       The results from these queries are displayed in two separate tables.
       
       External CSS is linked for styling (`styles.css`). */

// Include the database connection file
include "connecttodb.php";

// Query 1: Doctors without assigned patients
$queryNoPatients = "
    SELECT firstname, lastname, docid 
    FROM doctor 
    WHERE docid NOT IN (SELECT treatsdocid FROM patient WHERE treatsdocid IS NOT NULL)
";
$resultNoPatients = $conn->query($queryNoPatients);

// Query 2: Doctors with assigned patients
$queryWithPatients = "
    SELECT d.firstname AS doctor_firstname, d.lastname AS doctor_lastname, 
           p.firstname AS patient_firstname, p.lastname AS patient_lastname 
    FROM doctor d 
    JOIN patient p ON d.docid = p.treatsdocid
";
$resultWithPatients = $conn->query($queryWithPatients);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors</title>
    <!-- Link to global styles -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="header-container">
        <h1>Doctors</h1>
        <a href="mainmenu.php" class="back-button">Main Menu</a>
    </div>

    <!-- Table for doctors without assigned patients -->
    <h2>Doctors Without Assigned Patients</h2>
    <table border="1">
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Doctor ID</th>
        </tr>
        <?php
        if ($resultNoPatients->num_rows > 0) {
            while ($row = $resultNoPatients->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['docid']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No doctors without patients found.</td></tr>";
        }
        ?>
    </table>

    <!-- Table for doctors with assigned patients -->
    <h2>Doctors With Assigned Patients</h2>
    <table border="1">
        <tr>
            <th>Doctor First Name</th>
            <th>Doctor Last Name</th>
            <th>Patient First Name</th>
            <th>Patient Last Name</th>
        </tr>
        <?php
        if ($resultWithPatients->num_rows > 0) {
            while ($row = $resultWithPatients->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['doctor_firstname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['doctor_lastname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['patient_firstname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['patient_lastname']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No doctors with assigned patients found.</td></tr>";
        }
        ?>
    </table>

</body>
</html>
