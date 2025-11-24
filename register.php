<?php
session_start();
    
$conn = new mysqli("localhost:3307", "root", "", "local_blog"); 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uname   = filter_var($_POST['u_name'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
    $age = filter_var($_POST['age'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var(['name'], FILTER_SANITIZE_STRING);


$sql = "INSERT INTO users(u_name,pass, age, name) VALUES ('$uname','$password', '$age', '$name');";

if ($conn->query($sql) === TRUE) {
        $message = "User registed successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
};

