<?php

echo "save_services.php script executed.";


ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);
error_log(__FILE__ . " started at " . date('Y-m-d H:i:s')); // Log script execution



require_once('../makeover_admin/includes/dbconnection.php'); // Include the database connection file

// Get service name and cost from the AJAX request
$serviceName = mysqli_real_escape_string($conn, $_POST['service_name']);
$serviceCost = intval($_POST['service_cost']);

// Echo the received data for debugging purposes
echo "Received service name: " . $serviceName . "<br>";
echo "Received service cost: " . $serviceCost . "<br>";

// Insert the data into the tblservices table
// Insert the data into the tblservices table
$insertQuery = "INSERT INTO tblservices (ServiceName, Cost) VALUES ('$serviceName', $serviceCost)";

error_log("Insert Query: " . $insertQuery); // Log the query

if (mysqli_query($conn, $insertQuery)) {
    echo "Service added successfully: $serviceName - $serviceCost";
} else {
    echo "Error: " . $insertQuery . "<br>" . mysqli_error($conn);
}

?>
