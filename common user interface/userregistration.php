<?php 
// Start session
session_start();

include('../connection/connection.php');

// Function to validate name (only characters)
function validateName($name) {
  return preg_match('/^[a-zA-Z ]+$/', $name);
}

// Function to validate email (check if already exists in database)
function validateEmail($email, $con) {
  $sql = "SELECT * FROM login WHERE email = ?";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  return $result->num_rows == 0; // If 0 rows returned, email doesn't exist yet
}

// Function to validate mobile number (10 digits)
function validateMobile($mobile) {
  return preg_match('/^\d{10}$/', $mobile);
}

// Function to generate random 12-digit number
function generateAccountNumber() {
    return mt_rand(10000000000, 999999999999);
}

if (isset($_POST['Signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $mobile = $_POST['mnumber'];

    if (!validateName($name)) {
        echo "<script>alert('Name must contain only characters.')</script>";
    } elseif (!validateEmail($email, $con)) {
        echo "<script>alert('Email already exists. Please choose a different one.')</script>";
    } elseif(strlen($password) < 8) {
        echo "<script>alert('Password must be more than 8 characters long.')</script>";
    } elseif(!validateMobile($mobile)) {
        echo "<script>alert('Mobile number must be 10 digits.')</script>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $accountNumber = generateAccountNumber();

        $sql = "INSERT INTO `login`(`name`, `email`, `password`, `mobile_no`, `account_no`) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssss", $name, $email, $hashed_password, $mobile, $accountNumber); 
        $result = $stmt->execute();
        
        if ($result) {
            $_SESSION['email'] = $email;

            $insert_sql = "INSERT INTO balance (account_no) VALUES (?)";
            $insert_stmt = $con->prepare($insert_sql);
            $insert_stmt->bind_param("s", $accountNumber);
            $insert_stmt->execute();
            
            echo "<script>alert('Successfully Registration.')</script>";
            echo "<script>window.location.href = '../index.php';</script>";
        } else {
            echo "<script>alert('Sorry, unable to Registration');</script>";
        }

        $stmt->close();
    }
}

$con->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="..\style.css">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>


<link rel="stylesheet" href="..\css\bootstrap.min.css">
<script src="..\js\bootstrap.min.js"></script>
<style>
    .navbar {
      background-color: #0047ab; /* Bank's primary color */
    }
#sign-in:hover{
     background-color: rgb(44, 44, 236);
}
@media (max-width: 768px) {
        /* Adjust navbar width for mobile devices */
        .navbar {
            width: 100%;
            margin-left: 0;
            border-radius: 0;
        }

        /* Adjust image size for smaller screens */
        .navbar-brand img {
            height: 20px; /* Set the height of the image */
            width: auto; /* Let the width adjust automatically */
            margin-right: 200px; /* Add some margin between the image and the navbar */
            margin-left: 0;
            margin-bottom: -80px;
            margin-top:-80px
        }

        /* Center align the navbar items */
        .navbar-nav {
            width: 100%;
        }

        /* Adjust margin for sign-in and sign-up buttons */
        .navbar-nav .nav-item {
            margin: 5px;
        }
    }
</style>
</head>
<body>

<!----------------nabar start --------------------->


<nav class="navbar navbar-expand-lg navbar-dark fixed-top " style=" width:auto; justify-content:center; margin:50px; border-radius:50px; border-top:2px solid white; height:60px ">
  <a class="navbar-brand" href="..\BANK ONLINE PROJECT\index.php"><img src="..\weblogo.png" style="height:90px; width:140px;"></a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav mr-auto">

    </ul>
    <!-- Google Translate Widget -->

    <ul class="navbar-nav">
    <li class="nav-item">
        <a class="btn  my-2 my-sm-0" href="..\index.php" data-toggle="modal" data-target="#signin" id="sign-in" style="text-decoration:none; border-radius:20px">Sign In</a>
      </li>


    </ul>
  </div>
</nav>
<!------------------------------------------end nabar ---------------------------->


<!---------------model start------------>
<!-- Button trigger modal -->


<!-- Modal -->

  <div class="modal-dialog modal-dialog-centered" role="document" style="margin-top:150px">
    <div class="modal-content">
<div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Sign up as User</h5>
      </div>
      <div class="modal-body">


    <form action="" method="POST">
<div class="form-group">
    <label for="name">Full name</label>
<input type="text" class="form-control" id="name" name="name" placeholder="Enter a full name" required>

        <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" placeholder="Enter email" required>
        </div>

        <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <?php
include('..\passwordvalidation.php');
?>
  </div> 

  
  <div class="form-group">
    <label for="number">Mobile number</label>
    <input type="number" class="form-control" name="mnumber" placeholder="Enter mobile number" pattern="^\d{10}$" required>
</div>
<p style="font-size:12px">By clicking Register, you agree to the Terms and Conditions & Privacy Policy of prime bank</p>
    
      <div class="modal-footer">
      <input type="submit"  id="sign-up" class="btn btn-primary btn-lg btn-block" name="Signup" class="btn1" value="Sign up"> <br>

        </form>
      </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>