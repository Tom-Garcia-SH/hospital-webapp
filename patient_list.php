<?php
/*  Programmer ID: 87
    This file handles displaying a list of all current patients with sorting options.
       The process includes:
       1. Establishing a database connection.
       2. Handling form submission to sort patients by either first or last name, and in ascending or descending order.
       3. Fetching patient and doctor details from the database with the selected sorting.
       4. Displaying the results in a table, including patient information (e.g., name, height, weight) and doctor details.
       5. Providing a message if no patients are found or if an error occurs during the query execution. */

// Handle form submission for showing patient list
if (isset($_GET['show_list'])) {
    include 'connecttodb.php'; // Database connection

    // Get sorting options from the form, with defaults
    $orderBy = $_GET['order_by'] ?? 'firstname'; // Default order by patient's firstname
    $sortOrder = $_GET['sort_order'] ?? 'asc'; // Default sort

    // SQL query to fetch patient details with sorting
    $query = "SELECT p.ohip, p.firstname AS patient_firstname, p.lastname AS patient_lastname, 
                     p.birthdate, p.height, p.weight, p.treatsdocid, 
                     d.firstname AS doctor_firstname, d.lastname AS doctor_lastname
              FROM patient p  -- Alias for patient table is defined here
              JOIN doctor d ON p.treatsdocid = d.docid";
    
    // Ensure that the ordering column references patient.firstname or patient.lastname
    if ($orderBy == 'firstname') {
        $orderBy = 'p.firstname'; // Always refer to patient's first name
    } elseif ($orderBy == 'lastname') {
        $orderBy = 'p.lastname'; // Always refer to patient's last name
    }

    // Append the ORDER BY clause to the query after handling order by logic
    $query .= " ORDER BY $orderBy $sortOrder;"; // Sorting applied here

    // Execute query and handle results
    $result = mysqli_query($conn, $query);
    if ($result) {
        $patientList = mysqli_fetch_all($result, MYSQLI_ASSOC); // Store the fetched results
    } else {
        echo "Error executing query: " . mysqli_error($conn); // Added error handling for query execution
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient List</title>
    <!-- Link to global styles -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="header-container">
        <h1>Patient List</h1>
        <a href="patients.php" class="back-button">Patient Menu</a>
    </div>

    <form method="get" action=""> <!-- Form action fixed to ensure it submits to the same page -->
        <!-- Interaction area -->
        <div class="controls">
            <button type="submit" name="show_list">Show Patient List</button>
            
            <label>
                <input type="radio" name="order_by" value="firstname" <?= isset($_GET['order_by']) && $_GET['order_by'] == 'firstname' ? 'checked' : '' ?>> Order by First Name
            </label>
            <label>
                <input type="radio" name="order_by" value="lastname" <?= isset($_GET['order_by']) && $_GET['order_by'] == 'lastname' ? 'checked' : '' ?>> Order by Last Name
            </label>
            
            <select name="sort_order">
                <option value="asc" <?= isset($_GET['sort_order']) && $_GET['sort_order'] == 'asc' ? 'selected' : '' ?>>Sort in Ascending Order</option>
                <option value="desc" <?= isset($_GET['sort_order']) && $_GET['sort_order'] == 'desc' ? 'selected' : '' ?>>Sort in Descending Order</option>
            </select>
        </div>
    </form>

    <!-- Table for displaying patient list -->
    <div class="table-container">
        <?php if (isset($patientList) && count($patientList) > 0): ?> <!-- Check if patient list is not empty -->
            <table border="1">
                <thead>
                    <tr>
                        <th>OHIP</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Birthdate</th>
                        <th>Height (Metric)</th>
                        <th>Height (Imperial)</th>
                        <th>Weight (Metric)</th>
                        <th>Weight (Imperial)</th>
                        <th>Doctor Assigned</th>
                        <th>Doctor ID</th> <!-- Doctor ID column -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patientList as $patient): ?>
                        <?php
                        // Convert height from meters to feet/inches
                        $height_in_meters = $patient['height'];
                        $feet = floor($height_in_meters / 0.3048);
                        $inches = round(($height_in_meters / 0.0254) - ($feet * 12));

                        // Convert weight from kilograms to pounds
                        $weight_in_kg = $patient['weight'];
                        $weight_in_lbs = round($weight_in_kg * 2.205);

                        // Format heights and weights for display
                        $height_metric = $height_in_meters . ' meters';
                        $height_imperial = $feet . 'ft ' . $inches . 'in';

                        $weight_metric = $weight_in_kg . ' kg';
                        $weight_imperial = $weight_in_lbs . ' lbs';
                        ?>
                        <tr>
                            <td><?= $patient['ohip'] ?></td>
                            <td><?= $patient['patient_firstname'] ?></td>
                            <td><?= $patient['patient_lastname'] ?></td>
                            <td><?= $patient['birthdate'] ?></td>
                            <td><?= $height_metric ?></td>
                            <td><?= $height_imperial ?></td>
                            <td><?= $weight_metric ?></td>
                            <td><?= $weight_imperial ?></td>
                            <td><?= $patient['doctor_firstname'] ?> <?= $patient['doctor_lastname'] ?></td>
                            <td><?= $patient['treatsdocid'] ?></td> <!-- Display Doctor ID -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (isset($patientList) && count($patientList) == 0): ?>
            <p>No patients found.</p> <!-- Display a message if no patients are found after the button click -->
        <?php endif; ?>
    </div>
</body>
</html>
