<?php
session_start();

function openCon() {
    $conn = new mysqli("localhost", "root", "", "dct-ccs-finals");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function closeCon($conn) {
    $conn->close();
}

function debugLog($message) {
    error_log("[DEBUG] " . $message);
}

function loginUser($username, $password) {
    $conn = openCon();

    // Fetch user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Check if the password matches `md5`
        if (md5($password) === $user['password']) {
            // Rehash and update the password securely
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $updateStmt->bind_param("si", $newHash, $user['id']);
            $updateStmt->execute();
            $updateStmt->close();

            $_SESSION['email'] = $user['email'];
            $_SESSION['user_id'] = $user['id'];
            closeCon($conn);
            return true;
        }

        // Verify with `password_verify`
        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_id'] = $user['id'];
            closeCon($conn);
            return true;
        }
    }

    $stmt->close();
    closeCon($conn);
    return false;
}




function isLoggedIn() {
    return isset($_SESSION['email']);
}
function addUser() {
    $con = openCon();
    if ($con) {
        $email = $_POST['email'];
        $hashedPassword = md5($_POST['password']); 
        $name = $_POST['name'];
        $sql = "INSERT INTO users (email, password, name) VALUES ('$email', '$hashedPassword', '$name')";
        if (mysqli_query($con, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($con);
        }
        closeCon($con);
    } else {
        echo "Failed to connect to the database.";
    }
}

?>