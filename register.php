<?php
session_start();

// Include your database connection code here.
// ...
$host = "localhost";
$dbUsername = "root"; // Replace with your MySQL username
$dbPassword = "";     // Replace with your MySQL password
$dbname = "techtonic";

// Create connection
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function registerUser($conn, $username, $phone, $password) {
    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement to insert the new user
    $sql = "INSERT INTO site_user (email_address, phone_number, password) VALUES (?, ?, ?)";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        // Handle error
        echo "Error preparing statement: " . htmlspecialchars($conn->error);
        return false;
    }
    $stmt->bind_param("sss", $username, $phone, $hashedPassword);
    
    // Execute the query
    if (!$stmt->execute()) {
        // Handle error
        echo "Error executing statement: " . htmlspecialchars($stmt->error);
        $stmt->close();
        return false;
    }

    $stmt->close();
    return true;
}

function loginUser($conn, $username, $password) {
    // Prepare the SQL statement to select the user
    $sql = "SELECT * FROM site_user WHERE email_address=? LIMIT 1";
    
    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        // Handle error
        echo "Error preparing statement: " . htmlspecialchars($conn->error);
        return false;
    }
    $stmt->bind_param("s", $username);
    
    // Execute the query
    if (!$stmt->execute()) {
        // Handle error
        echo "Error executing statement: " . htmlspecialchars($stmt->error);
        $stmt->close();
        return false;
    }

    // Get the result
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email_address'] = $user['email_address'];
            $stmt->close();
            return true;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }

    $stmt->close();
    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check what type of action it is (sign-up or sign-in)
    if (isset($_POST['username']) && isset($_POST['phone']) && isset($_POST['password'])) {
        // This is a sign-up request
        $username = trim($_POST['username']);
        $phone = trim($_POST['phone']);
        $password = trim($_POST['password']);
        
        if (registerUser($conn, $username, $phone, $password)) {
            echo "User registered successfully.";
            // Redirect to login page or wherever you want
            header("Location: login_page.php"); // Adjust the location as needed
            exit;
        }
    } else if (isset($_POST['email']) && isset($_POST['psw'])) {
        // This is a sign-in request
        $username = trim($_POST['email']);
        $password = trim($_POST['psw']);
        
        if (loginUser($conn, $username, $password)) {
            echo "Login successful.";
            // Redirect to user dashboard or wherever you want
            header("Location: dashboard.php"); // Adjust the location as needed
            exit;
        }
    }
}

// Close database connection
$conn->close();
?>
