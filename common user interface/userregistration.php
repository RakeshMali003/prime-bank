<<<<<<< HEAD
<?php
session_start();
// ERROR REPORTING (Turn off in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../connection/connection.php'); // Check your path!

// --- PHP LOGIC (Unified) ---
$msg = "";
$msg_type = ""; // success or danger

// 1. REGISTRATION LOGIC
if (isset($_POST['register_submit'])) {
=======
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
>>>>>>> 75138d4784e452aef7ef999cabc36ef03d0f92ec
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $mobile = $_POST['mnumber'];

<<<<<<< HEAD
    // Basic Validations
    if (!preg_match('/^[a-zA-Z ]+$/', $name)) {
        $msg = "Name must contain only letters."; $msg_type = "danger";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email format."; $msg_type = "danger";
    } elseif (strlen($password) < 8) {
        $msg = "Password must be 8+ chars."; $msg_type = "danger";
    } elseif (!preg_match('/^\d{10}$/', $mobile)) {
        $msg = "Mobile must be 10 digits."; $msg_type = "danger";
    } else {
        // Check duplicate email
        $check = $con->prepare("SELECT email FROM login WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $msg = "Email already exists."; $msg_type = "warning";
        } else {
            // Create Account
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $acc_no = mt_rand(10000000000, 999999999999);
            
            $stmt = $con->prepare("INSERT INTO login (name, email, password, mobile_no, account_no) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $hashed, $mobile, $acc_no);
            if ($stmt->execute()) {
                // Add to balance
                $con->query("INSERT INTO balance (account_no) VALUES ('$acc_no')");
                $msg = "Registration Successful! Account: $acc_no. Please Login."; $msg_type = "success";
            } else {
                $msg = "Database Error."; $msg_type = "danger";
            }
            $stmt->close();
        }
        $check->close();
    }
}

// 2. LOGIN LOGIC
if(isset($_POST['login_submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $con->prepare("SELECT email, password FROM login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {
        $row = $res->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['email'] = $email;
            header("Location: ../user/profile.php"); // Redirect
            exit();
        } else {
            $msg = "Incorrect Password."; $msg_type = "danger";
        }
    } else {
        $msg = "Email not found."; $msg_type = "danger";
    }
    $stmt->close();
}
?>

=======
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



>>>>>>> 75138d4784e452aef7ef999cabc36ef03d0f92ec
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>Prime Bank | Secure Access</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0047ab;
            --secondary-color: #002e70;
            --accent-color: #00d2ff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* The Main Card */
        .auth-container {
            background: #fff;
            width: 900px;
            max-width: 95%;
            height: 600px;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            display: flex;
            overflow: hidden;
            position: relative;
        }

        /* Left Side (Visuals) */
        .auth-visuals {
            width: 45%;
            background: url('https://images.unsplash.com/photo-1565514020176-db7936a7d5eb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80') center/cover;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 40px;
            color: white;
            z-index: 1;
        }
        
        .auth-visuals::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to top, rgba(0, 71, 171, 0.9), rgba(0, 71, 171, 0.3));
            z-index: -1;
        }

        .auth-visuals h2 { font-weight: 700; margin-bottom: 10px; }
        .auth-visuals p { font-size: 0.9rem; opacity: 0.9; }

        /* Right Side (Forms) */
        .auth-forms {
            width: 55%;
            padding: 40px;
            position: relative;
            overflow-y: auto;
        }

        /* Tabs Styling */
        .nav-pills {
            background: #f1f3f6;
            border-radius: 12px;
            padding: 5px;
            margin-bottom: 30px;
        }
        .nav-pills .nav-link {
            color: #666;
            font-weight: 600;
            border-radius: 8px;
            transition: 0.3s;
        }
        .nav-pills .nav-link.active {
            background: #fff;
            color: var(--primary-color);
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        /* Floating Inputs */
        .form-floating > .form-control {
            border: 2px solid #eee;
            border-radius: 8px;
        }
        .form-floating > .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: none;
        }
        
        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            margin-top: 10px;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .google-btn {
            background: white;
            color: #333;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .google-btn:hover { background: #f8f9fa; color: #333; }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .auth-container { flex-direction: column; height: auto; min-height: 100vh; border-radius: 0; }
            .auth-visuals { display: none; } /* Hide image on mobile for speed */
            .auth-forms { width: 100%; padding: 30px 20px; }
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="auth-visuals">
        <img src="../weblogo.png" style="width: 100px; margin-bottom: auto;" alt="Logo">
        <h2>Future Banking</h2>
        <p>Experience the next generation of digital finance. Secure, fast, and reliable.</p>
    </div>

    <div class="auth-forms">
        
        <ul class="nav nav-pills nav-fill" id="authTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="login-tab" data-bs-toggle="pill" data-bs-target="#login" type="button">Sign In</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="register-tab" data-bs-toggle="pill" data-bs-target="#register" type="button">New Account</button>
            </li>
        </ul>

        <?php if ($msg != ""): ?>
            <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i> <?php echo $msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="tab-content" id="authTabContent">
            
            <div class="tab-pane fade show active" id="login" role="tabpanel">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Welcome Back</h3>
                    <p class="text-muted small">Enter your credentials to access your account.</p>
                </div>

                <form method="POST">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="email" id="loginEmail" placeholder="name@example.com" required>
                        <label for="loginEmail">Email Address</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="password" id="loginPass" placeholder="Password" required>
                        <label for="loginPass">Password</label>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label small text-muted" for="rememberMe">Remember me</label>
                        </div>
                        <a href="#" class="small text-decoration-none" style="color: var(--primary-color);">Forgot Password?</a>
                    </div>

                    <button type="submit" name="login_submit" class="btn btn-primary shadow-sm">Secure Login <i class="fas fa-arrow-right ms-2"></i></button>
                </form>

                <div class="position-relative mt-4 mb-4 text-center">
                    <hr class="text-muted">
                    <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">OR</span>
                </div>

                <button class="btn btn-primary google-btn w-100" onclick="signInWithGoogle()">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="20" alt=""> Continue with Google
                </button>
            </div>

            <div class="tab-pane fade" id="register" role="tabpanel">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Join Prime Bank</h3>
                    <p class="text-muted small">Create your account in 30 seconds.</p>
                </div>

                <form method="POST">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="name" id="regName" placeholder="Full Name" required>
                        <label for="regName">Full Legal Name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="email" id="regEmail" placeholder="Email" required>
                        <label for="regEmail">Email Address</label>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                             <div class="form-floating">
                                <input type="number" class="form-control" name="mnumber" id="regMobile" placeholder="Mobile" required>
                                <label for="regMobile">Mobile (10 Digits)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" class="form-control" name="password" id="regPass" placeholder="Password" required>
                                <label for="regPass">Password</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" required id="terms">
                        <label class="form-check-label small text-muted" for="terms">
                            I agree to the <a href="#">Terms & Privacy Policy</a>
                        </label>
                    </div>

                    <button type="submit" name="register_submit" class="btn btn-primary shadow-sm">Create Account</button>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // If you want to link directly to register: auth.php#register
    if(window.location.hash === '#register') {
        const triggerEl = document.querySelector('#register-tab');
        const tab = new bootstrap.Tab(triggerEl);
        tab.show();
    }
</script>

=======
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
>>>>>>> 75138d4784e452aef7ef999cabc36ef03d0f92ec
</body>
</html>