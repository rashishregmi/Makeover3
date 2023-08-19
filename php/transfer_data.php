<?php
// Include connection files for both databases
include('../php/connection.php');
include('../makeover_admin/includes/dbconnection.php');

// Retrieve data from makeover database users table (username, email)
$sqlUsers = "SELECT username, email FROM users";
$resultUsers = $conn->query($sqlUsers);

// Retrieve data from makeover database appointments table (contact, services)
$sqlAppointments = "SELECT contact, services FROM appointments";
$resultAppointments = $conn->query($sqlAppointments);

// Create an array to store the combined data
$combinedData = array();

// Combine data from users and appointments tables
while (($rowUsers = $resultUsers->fetch_assoc()) && ($rowAppointments = $resultAppointments->fetch_assoc())) {
    $combinedData[] = array(
        'Name' => $rowUsers['username'],
        'Email' => $rowUsers['email'],
        'MobileNumber' => $rowAppointments['contact'],
        'Details' => $rowAppointments['services']
    );
}

// Insert combined data into tblcustomers table in makeover_admin database
if (!empty($combinedData)) {
    foreach ($combinedData as $data) {
        $name = $data['Name'];
        $email = $data['Email'];
        $mobileNumber = $data['MobileNumber'];
        $details = $data['Details'];

        // Insert data into tblcustomers table in makeover_admin database
        $insertQuery = "INSERT INTO tblcustomers (Name, Email, MobileNumber, Details) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($insertQuery);
        $stmt->bind_param("ssss", $name, $email, $mobileNumber, $details);
        $stmt->execute();
    }
}

// Close the statements and connections
$stmt->close();
$conn->close();

echo "Data transferred successfully!";

// Redirect to the same page (appointment2.php) after data transfer
header("Location: http://localhost/Makeover/html/appointment2.html");
exit;
?>
