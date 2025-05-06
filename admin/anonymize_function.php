<?php
// anonymize_function.php

include("../connection.php");

function anonymizePatientData($database) {
    // Functions for anonymization
    function pseudonymizeName($name) {
        return substr(hash('sha256', $name), 0, 10); // Generate pseudonym from name
    }

    function anonymizeEmail($email) {
        return 'anon' . rand(1000, 9999) . '@example.com'; // Generate fake email
    }

    function generalizeNIC($nic) {
        return substr($nic, 0, 3) . '***'; // Mask part of the NIC
    }

    function anonymizePhone($phone) {
        return substr($phone, 0, 3) . '****'; // Mask part of the phone number
    }

    function generalizeDOB($dob) {
        return date('Y', strtotime($dob)); // Only keep the year
    }

    // Delete all existing records in the anonymized_patient_data table
    $database->query("DELETE FROM anonymized_patient_data");

    // Fetch all patients
    $result = $database->query("SELECT * FROM patient");

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $originalPid = $row['pid'];
            $anonymizedEmail = anonymizeEmail($row['pemail']);
            $pseudonymizedName = pseudonymizeName($row['pname']);
            $generalizedAddress = "Generalized Address"; // Replace with specific generalization logic if needed
            $generalizedNIC = generalizeNIC($row['pnic']);
            $anonymizedPhone = anonymizePhone($row['ptel']);
            $generalizedDOB = generalizeDOB($row['pdob']);

            // Insert anonymized data into the anonymized_patient_data table
            $sql = "INSERT INTO anonymized_patient_data 
                    (original_pid, anonymized_pemail, anonymized_pname, anonymized_paddress, anonymized_pnic, anonymized_ptel, generalized_pdob) 
                    VALUES ('$originalPid', '$anonymizedEmail', '$pseudonymizedName', '$generalizedAddress', '$generalizedNIC', '$anonymizedPhone', '$generalizedDOB')";
            $database->query($sql);
        }
        return "Data anonymization completed successfully.";
    } else {
        return "No patient data available for anonymization.";
    }
}

function displayAnonymizedPatients($database) {
    // Query to fetch all anonymized patient data
    $sql = "SELECT * FROM anonymized_patient_data";
    $result = $database->query($sql);

    // Check if any records exist
    if ($result->num_rows > 0) {
        echo '<table width="100%" class="sub-table scrolldown" style="border-spacing:0;">
                <thead>
                    <tr>
                        <th class="table-headin">Name</th>
                        <th class="table-headin">NIC</th>
                        <th class="table-headin">Telephone</th>
                        <th class="table-headin">Email</th>
                        <th class="table-headin">Date of Birth</th>
                    </tr>
                </thead>
                <tbody>';
        
        // Loop through the results and display each row
        while ($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td>' . htmlspecialchars($row['anonymized_pname']) . '</td>
                    <td>' . htmlspecialchars($row['anonymized_pnic']) . '</td>
                    <td>' . htmlspecialchars($row['anonymized_ptel']) . '</td>
                    <td>' . htmlspecialchars($row['anonymized_pemail']) . '</td>
                    <td>' . htmlspecialchars($row['generalized_pdob']) . '</td>
                  </tr>';
        }
        echo '  </tbody>
              </table>';
    } else {
        // Display a message if no data is available
        echo '<div style="text-align: center; padding: 20px;">
                <img src="../img/notfound.svg" width="25%" alt="No data found">
                <p class="heading-main12" style="font-size: 20px; color: rgb(49, 49, 49);">
                    No anonymized patient data available.
                </p>
              </div>';
    }
}

?>
