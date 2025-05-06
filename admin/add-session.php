<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

if ($_POST) {
    // Import database and logging function
    include("../connection.php");
    include("../logfunction.php");
    include("../vendor/autoload.php");

    // Set the correct timezone for MySQL
    $database->query("SET time_zone = '+05:30'"); // Adjust to your timezone offset

    // Sanitize and fetch form data
    $title = $database->real_escape_string($_POST["title"]);
    $docid = (int)$_POST["docid"];
    $nop = (int)$_POST["nop"];
    $date = $database->real_escape_string($_POST["date"]);
    $time = $database->real_escape_string($_POST["time"]);
    

    // Insert new session into the schedule table
    $sql = "INSERT INTO schedule (docid, title, scheduledate, scheduletime, nop) 
            VALUES ($docid, '$title', '$date', '$time', $nop)";
    $result = $database->query($sql);

    if ($result) {
        // Log the action
        $adminEmail = $_SESSION["user"]; // Fetch admin email from session
        $action = "Added a session titled '$title' for doctor ID $docid on $date at $time with $nop patients.";
        logAction('admin', $adminEmail, $action, $database);

       // **Fetch doctor and patient emails**
       $doctorQuery = $database->query("SELECT docemail FROM doctor WHERE docid = $docid");
       $doctor = $doctorQuery->fetch_assoc();
       $doctorEmail = $doctor['docemail'];

       $patientsQuery = $database->query("SELECT pemail FROM patient LIMIT $nop");
       $patientEmails = [];
       while ($row = $patientsQuery->fetch_assoc()) {
           $patientEmails[] = $row['pemail'];
       }

       // **Send Email Notification**
       $mail = new PHPMailer(true);
       try {
           // **SMTP Configuration**
           $mail->isSMTP();
           $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
           $mail->SMTPAuth = true;
           $mail->Username = 'castillano.khim12@gmail.com'; // Your email
           $mail->Password = 'osgh vvft edof kzaa'; // Your email password
           $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
           $mail->Port = 587;

           // **Email Content**
           $mail->setFrom('NonoyShabuLaboratory@gmail.com', 'Nonoy Shabu Clinic');
           $mail->addAddress($doctorEmail); // Send email to the doctor

           foreach ($patientEmails as $email) {
               $mail->addAddress($email); // Send email to each patient
           }

           $mail->isHTML(true);
           $mail->Subject = "New Scheduled Session: $title";
           $mail->Body = "
               <p>Dear Doctor and Patients,</p>
               <p>A new session titled '<strong>$title</strong>' has been scheduled.</p>
               <p><strong>Date:</strong> $date</p>
               <p><strong>Time:</strong> $time</p>
              
               <p>Thank you!</p>";

           // **Send the Email**
           $mail->send();

           // Redirect to schedule page with success message
           header("location: schedule.php?action=session-added&title=$title");
           exit();
       } catch (Exception $e) {
           echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
       }
    } else {
        // Handle database error
        $error = $database->error;
        echo "Error: Unable to add session. $error";
    }
}
?>
