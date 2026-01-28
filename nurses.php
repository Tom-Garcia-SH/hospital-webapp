<?php
/* Programmer ID: 87
       The page allows the user to select a nurse from a dropdown list. Once selected, it queries the database for the following:
       1. The nurse's details (name and supervisor).
       2. A list of doctors the nurse works for, along with the hours worked for each.
       3. The total hours worked by the nurse and their supervisor.

       It then displays this information to the user

       If no nurse is found, an error message is displayed. External CSS is linked for styling (`styles.css`). */

// Include the database connection file
include "connecttodb.php";

// Variables for nurse selection and details
$nurseId = $_POST['nurse'] ?? null;
$nurseDetails = [];
$doctorHours = [];
$totalHours = 0;
$supervisorDetails = null;

if ($nurseId) {
    // Query 1: Get the selected nurse's first and last name (and the supervisor's id)
    $queryNurseDetails = "SELECT firstname, lastname, reporttonurseid FROM nurse WHERE nurseid = ?";
    $stmt = $conn->prepare($queryNurseDetails);
    $stmt->bind_param("s", $nurseId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $nurseDetails = $result->fetch_assoc();

        // Query 2: Get doctors the nurse works for and the hours worked
        $queryDoctorHours = "
            SELECT d.firstname AS doctor_firstname, d.lastname AS doctor_lastname, wf.hours 
            FROM workingfor wf
            JOIN doctor d ON wf.docid = d.docid
            WHERE wf.nurseid = ?
        ";
        $stmt = $conn->prepare($queryDoctorHours);
        $stmt->bind_param("s", $nurseId);
        $stmt->execute();
        $doctorHoursResult = $stmt->get_result();
        while ($row = $doctorHoursResult->fetch_assoc()) {
            $doctorHours[] = $row;
            $totalHours += $row['hours'];
        }

        // Query 3: Get supervisor's name (if any)
        if (!empty($nurseDetails['reporttonurseid'])) {
            $querySupervisor = "SELECT firstname, lastname FROM nurse WHERE nurseid = ?";
            $stmt = $conn->prepare($querySupervisor);
            $stmt->bind_param("s", $nurseDetails['reporttonurseid']);
            $stmt->execute();
            $supervisorResult = $stmt->get_result();
            if ($supervisorResult->num_rows > 0) {
                $supervisorDetails = $supervisorResult->fetch_assoc();
            }
        }
    } else {
        $error = "Nurse not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nurses</title>
    <!-- Link to global styles -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="nurse-page">
        <div class="header-container">
            <h1>Nurse Details</h1>
            <a href="mainmenu.php" class="back-button">Main Menu</a>
        </div>

        <!-- Form to select a nurse -->
        <form method="post" action="">
            <label for="nurse">Select a Nurse:</label>
            <select name="nurse" id="nurse" required>
                <option value="">-- Select a Nurse --</option>
                <?php
                // Fetch all nurses for the dropdown
                $queryAllNurses = "SELECT nurseid, firstname, lastname FROM nurse";
                $resultAllNurses = $conn->query($queryAllNurses);
                while ($row = $resultAllNurses->fetch_assoc()) {
                    $selected = ($nurseId == $row['nurseid']) ? "selected" : "";
                    echo "<option value=\"" . htmlspecialchars($row['nurseid']) . "\" $selected>";
                    echo htmlspecialchars($row['firstname'] . " " . $row['lastname']);
                    echo "</option>";
                }
                ?>
            </select>
            <button type="submit">View Details</button>
        </form>

        <?php if ($nurseId && !empty($nurseDetails)): ?>
            <!-- Display Nurse Details -->
            <h2>Nurse: <?php echo htmlspecialchars($nurseDetails['firstname'] . " " . $nurseDetails['lastname']); ?></h2>

            <!-- Table for Doctors and Hours Worked -->
            <h3>Doctors and Hours Worked</h3>
            <div class="table-container">
                <table border="1">
                    <tr>
                        <th>Doctor First Name</th>
                        <th>Doctor Last Name</th>
                        <th>Hours Worked (Nurse)</th>
                    </tr>
                    <?php foreach ($doctorHours as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['doctor_firstname']); ?></td>
                            <td><?php echo htmlspecialchars($row['doctor_lastname']); ?></td>
                            <td><?php echo htmlspecialchars($row['hours']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($doctorHours)): ?>
                        <tr><td colspan="3">No records found.</td></tr>
                    <?php endif; ?>
                </table>
            </div>

            <!-- Total Hours Worked -->
            <p><strong>Total Hours Worked:</strong> <?php echo htmlspecialchars($totalHours); ?></p>

            <!-- Supervisor Details -->
            <?php if ($supervisorDetails): ?>
                <p><strong>Supervisor:</strong> <?php echo htmlspecialchars($supervisorDetails['firstname'] . " " . $supervisorDetails['lastname']); ?></p>
            <?php else: ?>
                <p><strong>Supervisor:</strong> None</p>
            <?php endif; ?>
        <?php elseif ($nurseId): ?>
            <p><?php echo htmlspecialchars($error ?? "No details found."); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
