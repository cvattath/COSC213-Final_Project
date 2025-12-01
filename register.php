    <?php
    session_start();
        
    $conn = new mysqli("localhost:3307", "root", "", "local_blog"); 
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $uname    = trim($_POST['u_name'] ?? '');
        $password = $_POST['pass'] ?? '';
        $age      = (int)($_POST['age'] ?? 0);
        $name     = trim($_POST['name'] ?? '');

        if (empty($uname) || empty($name) || $age < 1 || empty($pass)) {
            $message = "All fields are required!";
        }else{
            $check = $conn->prepare("SELECT u_name FROM users WHERE u_name =?");
            $check->bind_param('s',$uname);
            $check->execute();
            $result = $check->get_result();

            if($result->num_rows > 0){  
                $message = "Username already taken!";
            }else{
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $sql = $conn->prepare("INSERT INTO users(u_name,pass, age, name) VALUES (?,?,?,?);");
                $sql -> bind_param('ssis',$uname,$hashed_password, $age, $name);

                if ($sql->execute()) {
                $message = "User registed successfully!";
                
                } else {
                $message = "Error: " . $conn->error;
                }
           $sql->close();
        }
     $check->close();
    }
    
    }
    $conn->close();
    ?>

    <!DOCTYPE html>
    <html>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <head>
            <link rel="stylesheet" href="style.css">
        </head>
        <body class="register-body">
            <div class="register-box">
                <h2>Create Account</h2>
                <div class="msg"><?= $message ?></div>
                <form method="post" action="">

                    <input type="text" name="u_name" placeholder="Username" required><br>
                    <input type="text" name="name" placeholder="Full Name" required><br>
                    <input type="number" name="age" placeholder="Age" min="1" max="120" required><br>
                    <input type="password" name="pass" placeholder="Password" required><br>
                    <button type="submit">Register</button>
                </form>

            <p><br>
            Already have an account? <a href="home.php">Login here</a>
        </p>

            </div>
        </body>
    </html>



