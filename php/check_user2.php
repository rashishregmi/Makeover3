<?php
require '../php/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare the query to check if a user with the same email and password exists
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User with the same email and password exists
        echo "true";
    } else {
        // Check if user exists with the provided email
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User with the provided email exists, but password is incorrect
            echo "incorrect";
        } else {
            // User with the provided email does not exist
            echo "false";
        }
    }
    $stmt->close();
}
?>
