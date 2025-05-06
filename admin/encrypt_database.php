<?php
// Include database connection and encryption function
include("../connection.php");

// Encryption key (ensure this is stored securely)
define("ENCRYPTION_KEY", "AAA");

// Function to encrypt data
function encryptData($data) {
    return openssl_encrypt($data, "AES-256-CBC", ENCRYPTION_KEY, 0, substr(ENCRYPTION_KEY, 0, 16));
}

// Function to create and copy the database with encryption
function copyAndEncryptDatabase($sourceDb, $targetDb, $connection) {
    // Drop the existing encrypted database
    $connection->query("DROP DATABASE IF EXISTS $targetDb");

    // Create the encrypted database
    $connection->query("CREATE DATABASE IF NOT EXISTS $targetDb");

    // Select the source database
    $connection->query("USE $sourceDb");

    // Fetch all tables from the source database
    $tablesResult = $connection->query("SHOW TABLES");
    if ($tablesResult) {
        while ($tableRow = $tablesResult->fetch_array()) {
            $tableName = $tableRow[0];

            // Get the schema of the table
            $createTableResult = $connection->query("SHOW CREATE TABLE $tableName");
            $createTableRow = $createTableResult->fetch_array();
            $createTableSQL = $createTableRow[1];

            // Modify the CREATE TABLE SQL for the target database
            $createTableSQL = str_replace("CREATE TABLE `$tableName`", "CREATE TABLE `$targetDb`.`$tableName`", $createTableSQL);

            // Create the table in the target database
            $connection->query($createTableSQL);

            // Copy and encrypt data from the source table to the target table
            $dataResult = $connection->query("SELECT * FROM $tableName");
            if ($dataResult) {
                while ($dataRow = $dataResult->fetch_assoc()) {
                    $columns = array_keys($dataRow);
                    $values = array_map(function ($value) {
                        return encryptData($value); // Encrypt each value
                    }, array_values($dataRow));

                    $columnsList = implode(", ", array_map(function ($col) {
                        return "`$col`";
                    }, $columns));

                    $valuesList = implode(", ", array_map(function ($val) use ($connection) {
                        return "'" . $connection->real_escape_string($val) . "'";
                    }, $values));

                    $connection->query("INSERT INTO `$targetDb`.`$tableName` ($columnsList) VALUES ($valuesList)");
                }
            }
        }
        return "Database encrypted successfully!";
    } else {
        return "Failed to fetch tables from the source database.";
    }
}


// Check if the button was clicked
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['encrypt_database'])) {
    $message = copyAndEncryptDatabase("edoc", "edoc_encrypted", $database);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encrypt Database</title>
    <style>
        .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 4px;
            color: #495057;
        }
    </style>
</head>
<body>
    <h1>Update Database</h1>
    <p></p>
    <form method="POST">
        <button type="submit" name="encrypt_database" class="btn">Update</button>
    </form>
    <?php if (!empty($message)) { ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php } ?>
</body>
</html>
