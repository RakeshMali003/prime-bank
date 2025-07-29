<?php
// Include admin navbar
include('../admin/adminnavbar.php');

// Retrieve email from session
$email = $_SESSION['email'];
?>

<div class="main_content">
    <?php
    // Initialize an array to collect validation errors
    $errors = array();

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_info'])) {
        // Sanitize and validate input
        $name = htmlspecialchars($_POST['name']);
        $lname = htmlspecialchars($_POST['lname']);
        $email = htmlspecialchars($_POST['email']);
        $mobile = htmlspecialchars($_POST['mobile']);
        $address = htmlspecialchars($_POST['address']);
        $city = htmlspecialchars($_POST['city']);
        $state = htmlspecialchars($_POST['state']);
        $zip = htmlspecialchars($_POST['zip']);
        $password = htmlspecialchars($_POST['password']); // assuming you have a password field in your form

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        // Check if email already exists
        $check_email_query = "SELECT * FROM login WHERE email = '$email'";
        $check_email_result = mysqli_query($con, $check_email_query);
        if (mysqli_num_rows($check_email_result) > 0) {
            $errors[] = "Email already exists. Please use a different email.";
        }

        // Check if mobile number already exists
        $check_mobile_query = "SELECT * FROM login WHERE mobile_no = '$mobile'";
        $check_mobile_result = mysqli_query($con, $check_mobile_query);
        if (mysqli_num_rows($check_mobile_result) > 0) {
            $errors[] = "Mobile number already exists. Please use a different mobile number.";
        }

        // Validate mobile number
        if (!preg_match('/^[0-9]{10}$/', $mobile)) {
            $errors[] = "Invalid mobile number. Mobile number should be 10 digits long and contain only numbers.";
        }

        // If there are no errors, proceed with registration
        if (empty($errors)) {
            // Generate 12-digit account number
            function generateAccountNumber() {
                return mt_rand(100000000000, 999999999999);
            }
            $account_number = generateAccountNumber();

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert data into the database
            $insert_query = "INSERT INTO login (name, lname, email, mobile_no, address, city, state, zip, password, account_no) VALUES ('$name', '$lname', '$email', '$mobile', '$address', '$city', '$state', '$zip', '$hashed_password', '$account_number')";

            if (mysqli_query($con, $insert_query)) {
                echo "<script>alert('Registration successful');</script>";
            } else {
                echo "<script>alert('Registration failed');</script>";
            }
        } else {
            // Display all validation errors
            foreach ($errors as $error) {
                echo "<script>alert('$error');</script>";
            }
        }
    }
    ?>

    <h4 class="para"> Customer Management</h4> <br> <hr>

    <!-- Add New Customer Button -->
    <button type="button" class="btn btn-primary m-5" data-toggle="modal" data-target="#exampleModal">
        Add New Customer
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add New Customer Form -->
                    <h4 class="para"> Add New Customer</h4> <br> <hr>
                    <form class="m-5" method="POST" enctype="multipart/form-data">
                        <!-- Form fields -->
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer List Section -->
    <div id="customer-list-section">
        <h5 class="para"> Customer List</h5>
        <!-- Customer Table -->
        <div class="table-responsive">
            <table class="table table-bordered" id="example">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Account No</th>
                        <th scope="col">Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Mobile No</th>
                        <th scope="col">Address</th>
                        <th scope="col">State</th>
                        <th scope="col">Zip</th>
                        <th scope="col">City</th>
                        <th scope="col" colspan="2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Retrieve customer data from the database
                    $selectquery = "SELECT * FROM `login` ";
                    $query = mysqli_query($con, $selectquery);

                    while ($result = mysqli_fetch_assoc($query)) {
                    ?>
                        <tr>
                            <td><?php echo $result['account_no']; ?></td>
                            <td><?php echo $result['name']; ?></td>
                            <td><?php echo $result['lname']; ?></td>
                            <td><?php echo $result['email']; ?></td>
                            <td><?php echo $result['mobile_no']; ?></td>
                            <td><?php echo $result['address']; ?></td>
                            <td><?php echo $result['state']; ?></td>
                            <td><?php echo $result['zip']; ?></td>
                            <td><?php echo $result['city']; ?></td>
                            <td><a class="btn btn-primary" href="#">Update</a></td>
                            <td><a class="btn btn-danger" href="#">Delete</a></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include('..\admin\adminfotter.php');
?>