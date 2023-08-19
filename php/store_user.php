<?php
require '../php/connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the JSON data sent from the client
    $jsonData = file_get_contents('php://input');
    $user = json_decode($jsonData);

    // Check if all required fields are provided
    if (isset($user->username) && isset($user->email) && isset($user->password)) {
        $username = $user->username;
        $email = $user->email;
        $password = $user->password;

        // Check for duplicate entries
        $check_duplicate_sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $check_stmt = $conn->prepare($check_duplicate_sql);
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // User with the same username or email already exists
            $response = array('success' => false, 'message' => 'Error: The username or email is already registered.');
            echo json_encode($response);
        } else {
            // Insert the new user if no duplicate entries found
            $insert_sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("sss", $username, $email, $password);

            try {
                if ($stmt->execute()) {
                    // Registration successful
                    $response = array('success' => true, 'message' => 'Registration successful. You can now log in.');
                    echo json_encode($response);
                } else {
                    // Registration failed
                    $response = array('success' => false, 'message' => 'Error: Unable to register. Please try again later.');
                    echo json_encode($response);
                }
            } catch (mysqli_sql_exception $e) {
                // Registration failed
                $response = array('success' => false, 'message' => 'Error: Unable to register. Please try again later.');
                echo json_encode($response);
            }

            $stmt->close();
        }
    } else {
        // Missing required fields: username, email, or password
        $response = array('success' => false, 'message' => 'Error: Please provide all required fields.');
        echo json_encode($response);
    }
}

$conn->close();
?>
