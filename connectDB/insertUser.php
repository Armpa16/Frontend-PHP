<?php
// เชื่อมต่อฐานข้อมูล
$servername = "yamabiko.proxy.rlwy.net";
$port=13821;
$username = "root";
$password = "EKRiEnzCXzuGRzsXOanDuXnpFPvzKpOv";
$dbname = "railway";

$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and sanitize user inputs
$user = mysqli_real_escape_string($conn, $_POST['username']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$c_password = mysqli_real_escape_string($conn, $_POST['c_password']);


// Check if username already exists
$sql_check = "SELECT * FROM users WHERE username = '$user'";
$result = $conn->query($sql_check);

if ($result->num_rows > 0) {
    header("Location: /register.php?status=username_exists");
    exit();
}
// Check if passwords match
if ($password !== $c_password) {
    header("Location: /register.php?status=password_mismatch");
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
// Insert data into the database
$sql = "INSERT INTO users (username, email, password) 
        VALUES ('$user','$email', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
    // ส่ง status=registered_successfully ไปยัง loginform.php เพื่อให้แสดง SweetAlert (ถ้าต้องการ)
    header("Location: /loginform.php?status=registered_successfully");
    exit();

} else {
    header("Location: /register.php?status=registration_failed&error=" . urlencode($conn->error));
    exit();
}

$conn->close();
?>
