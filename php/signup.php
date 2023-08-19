<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../php/connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if all required fields are provided
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Check for duplicate email entries
        $check_duplicate_sql = "SELECT * FROM users WHERE email = ?";
        $check_stmt = $conn->prepare($check_duplicate_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // User with the same email already exists
            $errorMessage = "Error: The email is already registered.";
        } else {
            // Insert the new user if no duplicate entries found
            $insert_sql = "INSERT INTO users (email, password) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("ss", $email, $password);

            try {
                if ($stmt->execute()) {
                    // Registration successful, redirect back to login page with a success message
                    session_start();
                    $_SESSION['user_id'] = $stmt->insert_id;
                    $_SESSION['email'] = $email;
                    header("Location: http://localhost/Makeover/html/login.html#success");
                    exit;
                } else {
                    // Redirect back to signup page with a general error message
                    $errorMessage = "Error: Unable to register. Please try again later.";
                }
            } catch (mysqli_sql_exception $e) {
                // Redirect back to signup page with a general error message
                $errorMessage = "Error: Unable to register. Please try again later.";
            }

            $stmt->close();
        }
    } else {
        // Missing required fields: email or password
        $errorMessage = "Error: Please provide all required fields.";
    }
}

$conn->close();
?>
