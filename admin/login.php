<?php
session_start();

// Assuming the database connection is established earlier
$con = mysqli_connect("localhost", "root", "", "onlineshop");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = mysqli_real_escape_string($con, $_POST["email"]);
    $password = $_POST["password"]; // No longer using md5 hashing

    $sql = "SELECT * FROM admin_info WHERE admin_email = '$email'";
    $run_query = mysqli_query($con, $sql);
    $count = mysqli_num_rows($run_query);

    if ($count == 1) {
        $row = mysqli_fetch_assoc($run_query);
        if ($row["admin_password"] == $password) {
            $_SESSION["uid"] = $row["admin_id"];
            $_SESSION["name"] = $row["admin_name"];
            $ip_add = $_SERVER["REMOTE_ADDR"];

            // Redirect to the add_product.php page
            header("Location:../admin/add_product.php");
            exit();
        } else {
            $error_message = "Incorrect password!";
        }
    } else {
        $error_message = "Please register before login..!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
        }
        
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            text-align: center;
        }
        
        .error {
            color: red;
            margin-bottom: 10px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
        }
        
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 16px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
        }
        
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($error_message)) : ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label>Email:</label>
            <input type="email" name="email" required><br>

            <label>Password:</label>
            <input type="password" name="password" required><br>

            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
