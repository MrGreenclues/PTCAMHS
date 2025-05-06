<?php

// Import database and logging function
include("../connection.php");
include("../logfunction.php");

// Set the correct timezone for PHP
date_default_timezone_set('Asia/Manila'); // Adjust to your timezone

if ($_POST) {
    $name = $_POST['name'];
    $oldemail = $_POST["oldemail"];
    $nic = $_POST['nic'];
    $spec = $_POST['spec'];
    $email = $_POST['email'];
    $tele = $_POST['Tele'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $id = $_POST['id00'];

    if ($password == $cpassword) {
        $error = '3';
        $result = $database->query("SELECT doctor.docid FROM doctor INNER JOIN webuser ON doctor.docemail = webuser.email WHERE webuser.email = '$email'");

        if ($result->num_rows == 1) {
            $id2 = $result->fetch_assoc()["docid"];
        } else {
            $id2 = $id;
        }

        if ($id2 != $id) {
            $error = '1';
        } else {
            // Update doctor details
            $sql1 = "UPDATE doctor SET docemail = '$email', docname = '$name', docpassword = '$password', docnic = '$nic', doctel = '$tele', specialties = $spec WHERE docid = $id";
            $database->query($sql1);

            // Update webuser email
            $sql2 = "UPDATE webuser SET email = '$email' WHERE email = '$oldemail'";
            $database->query($sql2);

            // Log the action
            $adminEmail = $_SESSION["user"]; // Get the admin's email from the session
            $action = "Edited doctor's details: 
                       Name: '$name', 
                       Email: '$email', 
                       NIC: '$nic', 
                       Telephone: '$tele', 
                       Specialties: $spec.";
            logAction('admin', $adminEmail, $action, $database);

            $error = '4';
        }
    } else {
        $error = '2';
    }
} else {
    $error = '3';
}

header("location: doctors.php?action=edit&error=" . $error . "&id=" . $id);
?>
