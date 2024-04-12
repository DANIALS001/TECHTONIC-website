?><?php
echo "<pre>";
var_dump($_POST);
echo "</pre>";
// rest of your code...

session_start();

// Database configuration
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

// Check if the form data is set
if (isset($_POST['email']) && isset($_POST['psw'])) {
    // Retrieve form data
    $email_address = $_POST['email'];
    $password = $_POST['psw'];


    // SQL query to authenticate user
    // IMPORTANT: In a production system, passwords should be hashed
    $sql = "SELECT * FROM site_user WHERE email_address=? AND password=? LIMIT 1";


    // Prepare statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }


    $stmt->bind_param("ss", $email_address, $password);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows === 1) {
        // Login successful - retrieve user data
        $user = $result->fetch_assoc();
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email_address'] = $user['email_address'];
        // Redirect to the account page
        header("Location: account_page.php"); // Replace with the actual path to your account page
        exit; // Stop script execution after a redirect
    } else {
        // Login failed - handle the error, such as displaying a message to the user
        echo "Invalid email address or password";
    }


    $stmt->close();
    $conn->close();
} else {
    echo "Please fill in both email address and password.";
}


