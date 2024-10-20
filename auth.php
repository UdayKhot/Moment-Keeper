<?php
include 'db_connection.php';

$message = ""; // For displaying messages
$formType = isset($_GET['action']) && $_GET['action'] === 'login' ? 'login' : 'register'; // Default to registration, unless 'login' is requested

// Process registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $gmail = $_POST['gmail'];
    $password = $_POST['password']; // Store plain text password

    // Check if email already exists
    $checkEmail = "SELECT * FROM registration2 WHERE Gmail='$gmail'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        $message = "Email already registered. Please log in.";
        $formType = 'login'; // Switch to login form if email exists
    } else {
        // Insert into the database
        $sql = "INSERT INTO registration2 (Gmail, password) VALUES ('$gmail', '$password')";

        if ($conn->query($sql) === TRUE) {
            $message = "Registration successful! You can now log in.";
            $formType = 'login'; // Switch to login form after successful registration
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Process login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $gmail = $_POST['gmail'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM registration2 WHERE Gmail='$gmail'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['password']) { // Compare plain text password
            // Successful login
            $message = "Login successful! Welcome back!";
            // Redirect to momentkeeper.php after successful login
            header("Location: momentkeeper.php");
            exit(); // Stop further execution
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "No user found with that email.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login / Register</title>
</head>
<body>
    <div class="container">
        <h2><?php echo $formType === 'login' ? 'Login' : 'Register'; ?></h2>
        <?php if ($message) echo "<p>$message</p>"; ?>
        <form action="auth.php?action=<?php echo $formType; ?>" method="POST">
            <input type="email" name="gmail" placeholder="Enter your email" required>
            <input type="password" name="password" placeholder="<?php echo $formType === 'login' ? 'Enter your password' : 'Create a password'; ?>" required>
            <button type="submit" name="<?php echo $formType; ?>">
                <?php echo $formType === 'login' ? 'Login' : 'Register'; ?>
            </button>
        </form>
        <p>
            <?php if ($formType === 'login'): ?>
                Don't have an account? <a href="auth.php?action=register">Register here</a>
            <?php else: ?>
                Already have an account? <a href="auth.php?action=login">Login here</a>
            <?php endif; ?>
        </p>
    </div>
</body>
</html>
