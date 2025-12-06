<?php
session_start();
include('../connection/connection.php');

$name_error = $email_error = $password_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (empty($name)) {
        $name_error = "Name is required.";
    }

    if (empty($email)) {
        $email_error = "Email is required.";
    }

    if (empty($password)) {
        $password_error = "Password is required.";
    }

    if (!empty($name) && !empty($email) && !empty($password)) {
        $sql = "SELECT * FROM adminlogin WHERE email='$email'";
        $result = $con->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $stored_password = $row["password"];
            $stored_name = $row["name"];

            if ($password === $stored_password && $name === $stored_name) {
                session_start();
                $_SESSION["name"] = $row["name"];
                $_SESSION["email"] = $row["email"];
                header("Location: ../admin/admindashboard.php");
                exit();
            } else {
                echo "<script>alert('Incorrect password.');</script>";
            }
        } else {
            echo "<script>alert('Email not found.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../bootstrap/bootstrap-4.6.2-dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #0047ab;
            color: white;
            border-radius: 10px 10px 0 0;
            text-align: center;
            padding: 20px 0;
        }

        .card-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #0047ab;
            border-color: #0047ab;
        }

        .btn-primary:hover {
            background-color: #003a7e;
            border-color: #003a7e;
        }

        .admin-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <img src="../photo/avatar.png" alt="Admin" class="admin-img">
                        <h2 class="mb-0">Admin Login</h2>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <span class="text-danger"><?php echo $name_error; ?></span>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <span class="text-danger"><?php echo $email_error; ?></span>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <span class="text-danger"><?php echo $password_error; ?></span>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../bootstrap/bootstrap-4.6.2-dist/js/bootstrap.min.js"></script>
</body>
</html>
