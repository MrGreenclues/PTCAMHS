<?php
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