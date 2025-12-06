<?php

include('.\connection\connection.php');

// Function to validate email format
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate password
function validatePassword($input_password, $hashed_password) {
    return password_verify($input_password, $hashed_password);
}

// Check if form is submitted
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email format
    if (!validateEmail($email)) {
        echo "<script>alert('Email does not exist. Please try a different email or register.');</script>";
    } else {
        // Check if email exists in the database
        $sql = "SELECT email, password FROM login WHERE email = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Email exists, fetch hashed password
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];

            // Verify password
            if (validatePassword($password, $hashed_password)) {
                // Password correct, login successful
                $_SESSION['email'] = $email;
                ?>
                <script>
                location.href='../BANK ONLINE PROJECT/user/profile.php';
                </script>
                <?php
                
                // You can redirect the user to another page here
            } else {
                // Password incorrect
                echo "<script>alert('Password is incorrect. Please enter correct password.');</script>";
            }
        } else {
            // Email not found
            echo "<script>alert('Error: Email not found.');</script>";
        }

        $stmt->close();
    }
}

// Close connection
$con->close();
?>



<!-- Modal -->
<div class="modal fade" id="signin" tabindex="-1" role="dialog" aria-labelledby="signin" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" style="width:400px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="signin">User Login</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="btn-google text-center">
        <button type="button" class="btn-google" onclick="signInWithGoogle()" style="width:300px; text-align:center; border-radius:3px; background-color:white; color:grey; height:35px;font-size:15px; border:0.5px solid grey;   font-weight: bolder; "><img src="..\hireproject\photo\google.png" width="25px" height="25px" alt="">Login with Google</button>
</div>
        <hr>
     <form method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" required>
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder="Password" required>
    <a href="#" class="m-3" style="float:right"> Forgot Password?</a>
  </div>

        <input type="submit" class="btn btn-primary btn-lg btn-block" name="login" class="btn1" value="Login"> <br>

      <p class="text-center">New to PRIME Bank? Register (<a href=".\common user interface\userregistration.php">User</a> ) </p>
    </div>
</form>
    
    </div>

  </div>
</div>