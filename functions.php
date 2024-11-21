<?php


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
function addSubject($subjectCode, $subjectName) {
    $conn = openCon();
    
    // Prepare the SQL query to insert a new subject
    $sql = "INSERT INTO subjects (subject_code, subject_name) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $subjectCode, $subjectName);
    
    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        debugLog("Subject added: $subjectCode - $subjectName");
    } else {
        debugLog("Error adding subject: " . $stmt->error);
    }
    
    // Close the statement and connection
    $stmt->close();
    closeCon($conn);
}

function getSubjects() {
    $conn = openCon();
    
    // Fetch all subjects from the database
    $sql = "SELECT * FROM subjects";
    $result = $conn->query($sql);

    // Initialize an array to store the subjects
    $subjects = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $subjects[] = $row;
        }
    }
    
    // Close the connection
    closeCon($conn);
    
    return $subjects;
}

?>