<?php
session_start();
include('../connection/connection.php');

if (!isset($_SESSION['email'])) {
    header('Location: ../admin/adminlogin.php');
    exit();
}

$email = $_SESSION['email'];

$sql = "SELECT * FROM `adminlogin` WHERE email = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
} else {
    echo "Error: Admin not found.";
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../bootstrap/bootstrap-4.6.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <style>
        .para {
            float: left;
            color: #4e22a1ba;
        }

        th {
            width: 200px;
            font-weight: bold;
            margin: 20px;
        }

        .card-box {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<header class="cd-main-header js-cd-main-header" style="background-color: rgba(51, 9, 143, 0.943);">
    <div class="cd-logo-wrapper">
        <a class="navbar-brand" href=""><img src="../weblogo.png" height="50px" width="70px" alt=""></a>
    </div>

    <div class=" js-cd-search">
        <form></form>
    </div>

    <button class="reset cd-nav-trigger js-cd-nav-trigger" aria-label="Toggle menu"><span></span></button>

    <ul class="cd-nav__list js-cd-nav__list">
        <li class="cd-nav__item cd-nav__item--has-children js-cd-item--has-children">
            <a href="#0">
                <p style="font-size:20px; margin:10px">Admin Email : <?php echo $row['email']; ?></p>
            </a>
        </li>
    </ul>
</header>

<main class="cd-main-content">
    <nav class="cd-side-nav js-cd-side-nav" style="background-color: rgb(44, 44, 236);font-size:19px">
        <ul class="cd-side__list js-cd-side__list">
            <li class="cd-side__item cd-side__item--notifications cd-side__item--selected js-cd-item--has-children">
                <a class="nav-link active" href="../admin/admindashboard.php" aria-disabled="page">Dashboard</a>
            </li>

            <li class="cd-side__item cd-side__item--notifications cd-side__item--selected js-cd-item--has-children">
                <a class="nav-link active" href="../admin/customer_management.php" aria-disabled="page">Customer Management</a>
            </li>

            <li class="cd-side__item cd-side__item--notifications cd-side__item--selected js-cd-item--has-children">
                <a class="nav-link active" href="../admin/transactionmanagement.php" aria-disabled="page">Transaction Management</a>
            </li>

            <li class="cd-side__item cd-side__item--notifications cd-side__item--selected js-cd-item--has-children">
                <a class="nav-link active" href="#" aria-disabled="page">Account Management</a>
            </li>

            <li class="cd-side__item cd-side__item--notifications cd-side__item--selected js-cd-item--has-children">
                <a class="nav-link active" href="../admin/adminlogout.php" aria-disabled="page">Logout</a>
            </li>
          
        </ul>
    </nav>

    <div class="cd-content-wrapper">
        <div class="text-component">
            <!-- Your content here -->
