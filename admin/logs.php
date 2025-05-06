<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <title>Dashboard</title>
    <style>
        .dashbord-tables {
            animation: transitionIn-Y-over 0.5s;
        }
        .filter-container {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>
<body>
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
                        <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Dashboard</p></a></div></a>
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
                        <a href="patient.php" class="non-style-link-menu"><div><p class="menu-text">Patients</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session menu-active menu-icon-session-active">
                        <a href="logs.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Logs</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="manage_anonymization.php" class="non-style-link-menu"><div><p class="menu-text">Manage Data</p></a></div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Dashboard Body -->
        <div class="dash-body" style="margin-top: 10px">
            <table border="0" width="0%" style="border-spacing: 0;margin:0;padding:0;">
                <tr>
                    <td colspan="2" class="nav-bar">
                        <form action="doctors.php" method="post" class="header-search">
                            <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Doctor name or Email" list="doctors">
                            &nbsp;&nbsp;
                            <?php
                            include '../connection.php';
                            echo '<datalist id="doctors">';
                            $list11 = $database->query("SELECT docname, docemail FROM doctor");
                            while ($row = $list11->fetch_assoc()) {
                                $d = htmlspecialchars($row["docname"]);
                                $c = htmlspecialchars($row["docemail"]);
                                echo "<option value='$d'>";
                                echo "<option value='$c'>";
                            }
                            echo '</datalist>';
                            ?>
                            <input type="submit" value="Search" class="login-btn btn-primary-soft btn">
                        </form>
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);text-align: right;">Today's Date</p>
                        <p class="heading-sub12"><?php echo date('Y-m-d'); ?></p>
                    </td>
                    <td width="10%">
                        <button class="btn-label" style="display: flex;justify-content: center;align-items: center;">
                            <img src="../img/calendar.svg" width="100%">
                        </button>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <center>
                            <table class="filter-container" style="border: none;" border="0">
                                <tr>
                                    <td>
                                        <h2>Admin Logs</h2>
                                        
<?php
// Database connection
include '../connection.php';
include '../logfunction.php';

// Fetch logs for each user type
$adminLogs = fetchLogs('admin', $database);
$doctorLogs = fetchLogs('doctor', $database);
$patientLogs = fetchLogs('patient', $database);
?>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 0px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
    <table>
        <tr>
            <th>Email</th>
            <th>Action</th>
            <th>Timestamp</th>
        </tr>
        <?php while ($row = $adminLogs->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['user_email']) ?></td>
            <td><?= htmlspecialchars($row['action']) ?></td>
            <td><?= htmlspecialchars($row['timestamp']) ?></td>
        </tr>
        <?php } ?>
    </table>

    <h2>Doctor Logs</h2>
    <table>
        <tr>
            <th>Email</th>
            <th>Action</th>
            <th>Timestamp</th>
        </tr>
        <?php while ($row = $doctorLogs->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['user_email']) ?></td>
            <td><?= htmlspecialchars($row['action']) ?></td>
            <td><?= htmlspecialchars($row['timestamp']) ?></td>
        </tr>
        <?php } ?>
    </table>

    <h2>Patient Logs</h2>
    <table>
        <tr>
            <th>Email</th>
            <th>Action</th>
            <th>Timestamp</th>
        </tr>
        <?php while ($row = $patientLogs->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['user_email']) ?></td>
            <td><?= htmlspecialchars($row['action']) ?></td>
            <td><?= htmlspecialchars($row['timestamp']) ?></td>
        </tr>
        <?php } ?>
    </table>

                                    </td>
                                </tr>
                                <!-- Additional log sections here -->
                            </table>
                        </center>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
