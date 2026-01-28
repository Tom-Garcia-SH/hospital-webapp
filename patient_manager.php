<?php
/* Programmer ID: 87
   This script manages patient records within a healthcare system. 
   It allows administrators to add, update, and delete patients, 
   while interacting with a database to store and retrieve relevant details.
   It includes basic validation, feedback for success or errors, and weight conversion functionality. */

// Include database connection
include "connecttodb.php";

// Initialize variables
$errors = [];
$successMessage = "";

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Insert New Patient
    if (isset($_POST['add_patient'])) {
        $ohip = $_POST['ohip'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $birthdate = $_POST['birthdate'];
        $weight = $_POST['weight'];
        $height = $_POST['height'];
        $doctor = $_POST['doctor'];

        // Validate that OHIP is unique
        $check_query = "SELECT * FROM patient WHERE ohip = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("s", $ohip);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "This OHIP number is already in use.";
        } else {
            // Convert weight from pounds to kilograms if necessary
            if (isset($_POST['weight_unit']) && $_POST['weight_unit'] == 'pounds') {
                $weight = $weight / 2.205; // Convert pounds to kilograms
                $weight = round($weight); // Round to the nearest integer
            }

            // Insert the new patient
            $insert_query = "INSERT INTO patient (ohip, firstname, lastname, birthdate, weight, height, treatsdocid) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ssssids", $ohip, $firstname, $lastname, $birthdate, $weight, $height, $doctor);
            if ($stmt->execute()) {
                $successMessage = "Patient added successfully!";
            } else {
                $errors[] = "Error adding patient: " . $stmt->error;
            }
        }
    }

    // Delete Existing Patient
    if (isset($_POST['delete_patient'])) {
        $ohip = $_POST['delete_ohip'];

        // Check if patient exists
        $check_query = "SELECT * FROM patient WHERE ohip = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("s", $ohip);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $errors[] = "No patient found with this OHIP number.";
        } else {
            // Confirm deletion before proceeding
            if (isset($_POST['confirm_delete']) && $_POST['confirm_delete'] == 'yes') {
                $delete_query = "DELETE FROM patient WHERE ohip = ?";
                $stmt = $conn->prepare($delete_query);
                $stmt->bind_param("s", $ohip);
                if ($stmt->execute()) {
                    $successMessage = "Patient deleted successfully.";
                } else {
                    $errors[] = "Error deleting patient: " . $stmt->error;
                }
            }
        }
    }

    // Modify Existing Patient (update weight)
    if (isset($_POST['modify_patient'])) {
        $ohip = $_POST['modify_ohip'];
        $weight = $_POST['modify_weight'];

        // Convert weight from pounds to kilograms if necessary
        if (isset($_POST['weight_unit']) && $_POST['weight_unit'] == 'pounds') {
            $weight = $weight / 2.205; // Convert pounds to kilograms
            $weight = round($weight); // Round to the nearest integer
        }

        // Update the patient's weight
        $update_query = "UPDATE patient SET weight = ? WHERE ohip = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("is", $weight, $ohip);
        if ($stmt->execute()) {
            $successMessage = "Patient weight updated successfully.";
        } else {
            $errors[] = "Error updating patient weight: " . $stmt->error;
        }
    }
}

// Fetch list of doctors for patient assignment
$doctors_query = "SELECT docid, firstname, lastname FROM doctor";
$doctors_result = $conn->query($doctors_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Manager</title>
    <!-- Link to global styles -->
    <link rel="stylesheet" href="styles.css">
    <!-- Link to page-specific styles -->
    <link rel="stylesheet" href="patient_manager.css"> 
</head>
<body>
    <div class="header-container">
        <h1>Manage Patients</h1>
        <a href="patients.php" class="back-button">Patient Menu</a>
    </div>

    <!-- Error messages -->
    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <?php foreach ($errors as $error): ?>
                <p class="error"><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Success messages -->
    <?php if ($successMessage): ?>
        <div class="success-message">
            <p class="success"><?= $successMessage ?></p>
        </div>
    <?php endif; ?>

    <!-- Add New Patient Form -->
    <h2 class="form-title">Add New Patient</h2>
    <form method="POST" action="">
        <label for="ohip">OHIP Number:</label>
        <input type="text" name="ohip" required><br>

        <label for="firstname">First Name:</label>
        <input type="text" name="firstname" required><br>

        <label for="lastname">Last Name:</label>
        <input type="text" name="lastname"><br>

        <label for="birthdate">Birthdate:</label>
        <input type="date" name="birthdate"><br>

        <label for="weight">Weight:</label>
        <input type="number" name="weight" required><br>
        <label for="weight_unit">Weight Unit:</label>
        <label>
            <input type="radio" name="weight_unit" value="kilograms" checked> Kilograms
        </label>
        <label>
            <input type="radio" name="weight_unit" value="pounds"> Pounds
        </label><br>

        <label for="height">Height (in meters):</label>
        <input type="number" step="0.01" name="height" required><br>

        <label for="doctor">Assign Doctor:</label>
        <select name="doctor">
            <?php while ($row = $doctors_result->fetch_assoc()): ?>
                <option value="<?= $row['docid'] ?>"><?= $row['firstname'] . ' ' . $row['lastname'] ?></option>
            <?php endwhile; ?>
        </select><br>

        <button type="submit" name="add_patient">Add Patient</button>
    </form>

    <!-- Delete Patient Form -->
    <h2 class="form-title">Delete Patient</h2>
    <form method="POST" action="">
        <label for="delete_ohip">OHIP Number:</label>
        <input type="text" name="delete_ohip" required><br>

        <label>Are you sure you want to delete this patient?</label>
        <label>
            <input type="radio" name="confirm_delete" value="yes"> Yes
        </label>
        <label>
            <input type="radio" name="confirm_delete" value="no" checked> No
        </label><br>

        <button type="submit" name="delete_patient">Delete Patient</button>
    </form>

    <!-- Modify Patient Form -->
    <h2 class="form-title">Modify Patient</h2>
    <form method="POST" action="">
        <label for="modify_ohip">OHIP Number:</label>
        <input type="text" name="modify_ohip" required><br>

        <label for="modify_weight">New Weight:</label>
        <input type="number" name="modify_weight" required><br>
        <label for="weight_unit">Weight Unit:</label>
        <label>
            <input type="radio" name="weight_unit" value="kilograms" checked> Kilograms
        </label>
        <label>
            <input type="radio" name="weight_unit" value="pounds"> Pounds
        </label><br>

        <button type="submit" name="modify_patient">Modify Patient</button>
    </form>

</body>
</html>
