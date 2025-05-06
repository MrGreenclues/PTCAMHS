<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <title>Patients</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>
<body>
    <?php
    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='a'){
            header("location: ../login.php");
        }
    } else {
        header("location: ../login.php");
    }

    // Import database
    include("../connection.php");

    // Include the anonymization function
    include("anonymize_function.php");

    // Display message after anonymization
    $message = ""; // To store the status message

    // Handle the button click
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['anonymize_data'])) {
        $message = anonymizePatientData($database);
    }
    ?>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px" >
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title">Administrator</p>
                                    <p class="profile-subtitle">admin@edoc.com</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                <a href="../logout.php" ><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                    </table>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-dashbord" >
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Dashboard</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor ">
                        <a href="doctors.php" class="non-style-link-menu "><div><p class="menu-text">Doctors</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-schedule">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Schedule</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">Appointment</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-patient">
                        <a href="patient.php" class="non-style-link-menu "><div><p class="menu-text">Patients</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session">
                        <a href="logs.php" class="non-style-link-menu"><div><p class="menu-text">Logs</p></a></div>
                    </td>
                </tr>
                                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings menu-active menu-icon-settings-active">
                        <a href="manage_anonymization.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Manage Data</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session">
                        <a href="encrypt_database.php" class="non-style-link-menu"><div><p class="menu-text">Encryption</p></a></div>
                    </td>
                </tr>

            </table>
        </div>

        <div class="dash-body">
            <!-- Header and controls -->
            <table border="0" width="100%" style="border-spacing: 0;margin:0;padding:0;margin-top:25px;">
                <tr>
                    <td width="13%">
                        <a href="index.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <form action="" method="post" class="header-search">
                            <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Patient name or Email" list="patient">&nbsp;&nbsp;
                            <input type="Submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                        </form>
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">Today's Date</p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 
                            date_default_timezone_set('Asia/Kolkata');
                            $date = date('Y-m-d');
                            echo $date;
                            ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button class="btn-label" style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <form method="POST" action="" style="display: flex; position:absolute; padding-left:40%; padding-top:3px;">
                            <button type="submit" name="backup" class="btn">Create Backup</button>
                        </form>
                    </td>
                </tr>

                <!-- Anonymize Button -->
                <tr>
                    <td colspan="4">
                        <form method="POST" action="" style="display: flex; position:absolute; padding-left:20%;">
                            <button type="submit" name="anonymize_data" class="btn">Anonymize Patient Data</button>
                        </form>
                    </td>
                </tr>



                <?php if (!empty($message)) { ?>
                    <div class="message"><?php echo $message; ?></div>
                <?php } ?>

                <!-- Display Anonymized Patients Table -->
                <tr>
                    <td colspan="4" style="padding-top:10px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">Anonymized Patient Data</p>
                    </td>
                </tr>

                <tr>
                    <td colspan="4">
                        <?php
                            // Call function to display anonymized patients table
                            displayAnonymizedPatients($database);
                        ?>
                    </td>
                </tr>

            </table>

  
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['backup'])) {
        include("backup_function.php"); // Ensure the function is included
        $message = backupDatabase("edoc", "../backups", "root", "");
        echo "<p>$message</p>";
    }
    ?>
        </div>
    </div>
</body>
</html>



