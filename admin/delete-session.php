<?php

session_start();

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'a') {
        header("location: ../login.php");
        exit();
    }
} else {
    header("location: ../login.php");
    exit();
}

// Set the correct timezone for PHP
date_default_timezone_set('Asia/Manila'); // Adjust to your timezone

if ($_GET) {
    // Import database and logging function
    include("../connection.php");
    include("../logfunction.php");

    $id = $_GET["id"];

    // Fetch session details for logging
    $result = $database->query("SELECT * FROM schedule WHERE scheduleid='$id'");
    if ($result && $result->num_rows == 1) {
        $session = $result->fetch_assoc();
        $title = $session['title'];
        $docid = $session['docid'];
        $date = $session['scheduledate'];
        $time = $session['scheduletime'];

        // Log the action
        $adminEmail = $_SESSION["user"]; // Fetch admin email from session
        $action = "Dropped session titled '$title' for doctor ID $docid on $date at $time.";
        logAction('admin', $adminEmail, $action, $database);
    }

    // Delete the session from the database
    $sql = $database->query("DELETE FROM schedule WHERE scheduleid='$id'");

    // Redirect back to the schedule page
    header("location: schedule.php?action=session-dropped&title=$title");
    exit();
}
?>
