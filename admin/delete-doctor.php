<?php

session_start();

// Set the correct timezone for PHP
date_default_timezone_set('Asia/Manila'); // Adjust to your timezone

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'a') {
        header("location: ../login.php");
        exit();
    }
} else {
    header("location: ../login.php");
    exit();
}

// Import database and logging function
include("../connection.php");
include("../logfunction.php");

if ($_GET) {
    $id = $_GET["id"];

    // Fetch the doctor's details before deletion for logging
    $result001 = $database->query("SELECT * FROM doctor WHERE docid = $id");
    if ($result001->num_rows > 0) {
        $doctorData = $result001->fetch_assoc();
        $email = $doctorData["docemail"];
        $name = $doctorData["docname"];
        $specialties = $doctorData["specialties"];
        
        // Delete from webuser table
        $database->query("DELETE FROM webuser WHERE email = '$email'");

        // Delete from doctor table
        $database->query("DELETE FROM doctor WHERE docemail = '$email'");

        // Log the deletion action
        $adminEmail = $_SESSION["user"]; // Get the admin's email from the session
        $action = "Deleted doctor with details: 
                   Name: '$name', 
                   Email: '$email', 
                   Specialties: $specialties.";
        logAction('admin', $adminEmail, $action, $database);

        // Redirect to doctors page
        header("location: doctors.php?action=deleted&name=$name");
        exit();
    } else {
        // Handle case when doctor ID is invalid
        header("location: doctors.php?action=error&message=Doctor not found");
        exit();
    }
}
?>
