<?php
function backupDatabase($databaseName, $backupDir, $username, $password, $host = "localhost") {
    // Path to mysqldump in XAMPP (update this if needed)
    $mysqldumpPath = "C:/xampp/mysql/bin/mysqldump.exe";

    // Ensure the mysqldump utility exists
    if (!file_exists($mysqldumpPath)) {
        return "mysqldump utility not found at $mysqldumpPath. Please check the path.";
    }

    // Ensure the backup directory exists
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true); // Create directory if not exists
    }

    // Create a backup filename with timestamp
    $backupFile = $backupDir . DIRECTORY_SEPARATOR . $databaseName . "_backup_" . date("Y-m-d_H-i-s") . ".sql";

    // Construct the mysqldump command with full path
    $command = "\"$mysqldumpPath\" -h $host -u $username" . ($password ? " -p$password" : "") . " $databaseName > \"$backupFile\"";

    // Execute the command
    $output = null;
    $return_var = null;
    exec($command, $output, $return_var);

    // Check for errors
    if ($return_var === 0) {
        return "Backup successful! File saved to: $backupFile";
    } else {
        return "Backup failed! Please check your MySQL credentials, permissions, and database connection.";
    }
}

// Example Usage:
$username = "root"; // Replace with your MySQL username
$password = "";     // Replace with your MySQL password
$databaseName = "edoc";
$backupDir = "../backups"; // Relative path for the backup directory

$message = backupDatabase($databaseName, $backupDir, $username, $password);
echo $message;
?>
