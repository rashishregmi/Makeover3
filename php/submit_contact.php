<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "makeover";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Prepare and execute the SQL query
    $sql = "INSERT INTO contact (first_name, last_name, contact_number, email, message)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $first_name, $last_name, $contact_number, $email, $message);

    if ($stmt->execute()) {
        // Return success message to JavaScript
        $response = array(
            'success' => true,
            'message' => 'Form submitted successfully!'
        );
    } else {
        // Return error message to JavaScript
        $response = array(
            'success' => false,
            'message' => 'An error occurred while submitting the form.'
        );
    }

    // Close database connection
    $stmt->close();
    $conn->close();

    // Send JSON response back to JavaScript
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
