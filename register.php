<?php
session_start();
    
$conn = new mysqli("localhost:3307", "root", "", "local_blog"); 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $uname    = $_POST['u_name'] ?? '';
    $password = $_POST['pass'] ?? '';
    $age      = (int)($_POST['age'] ?? 0);
    $name     = $_POST['name'] ?? '';

    $password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users(u_name,pass, age, name) VALUES ('$uname','$password', '$age', '$name');";

if ($conn->query($sql) === TRUE) {
        $message = "User registed successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
};

?>

<!DOCTYPE html>
<html>



