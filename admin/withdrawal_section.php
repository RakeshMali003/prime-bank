<?php
// Start session
session_start();

// Include database connection
include('../connection/connection.php');

// Function to sanitize input data
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Function to generate a 15-digit withdrawal ID
function generateWithdrawalID() {
    return time() . mt_rand(10000, 99999);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['withdrawalform'])) {
    // Validate and sanitize input fields
    $accountID = sanitize_input($_POST["accountID"]);
    $amount = sanitize_input($_POST["amount"]);
    $withdrawalMethod = sanitize_input($_POST["withdrawalMethod"]);
    $withdrawalReference = sanitize_input($_POST["withdrawalReference"]);
    $name = sanitize_input($_POST["name"]);

    // Validate input fields
    if (empty($accountID) || empty($amount) || empty($withdrawalMethod) || empty($withdrawalReference) || empty($name)) {
        // If any field is empty, show an alert and stop processing
        $_SESSION['error'] = 'All fields are required. Please fill in all fields.';
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        // Check if name contains only letters and whitespace
        $_SESSION['error'] = 'Name should contain only letters and whitespace.';
    } else {
        // Check if the account exists
        $checkAccountQuery = "SELECT * FROM login WHERE account_no = ?";
        $checkAccountStmt = $con->prepare($checkAccountQuery);
        $checkAccountStmt->bind_param("s", $accountID);
        $checkAccountStmt->execute();
        $result = $checkAccountStmt->get_result();

        if ($result->num_rows == 0) {
            // If account does not exist, show an alert and stop processing
            $_SESSION['error'] = 'Account does not exist. Please enter a valid account ID.';
        } else {
            // Fetch user's current balance
            $balanceQuery = "SELECT total_balance FROM balance WHERE account_no = ?";
            $balanceStmt = $con->prepare($balanceQuery);
            $balanceStmt->bind_param("s", $accountID);
            $balanceStmt->execute();
            $balanceResult = $balanceStmt->get_result()->fetch_assoc();
            $currentBalance = $balanceResult['total_balance'];

            // Check if the user has sufficient balance for withdrawal
            if ($currentBalance >= $amount) {
                // Proceed with the withdrawal
                // Subtract the amount from the user's balance
                $newBalance = $currentBalance - $amount;
                $updateBalanceQuery = "UPDATE balance SET total_balance = ? WHERE account_no = ?";
                $updateBalanceStmt = $con->prepare($updateBalanceQuery);
                $updateBalanceStmt->bind_param("ds", $newBalance, $accountID);
                $updateBalanceStmt->execute();

                // Generate withdrawal ID
                $withdrawalID = generateWithdrawalID();

                // Prepare SQL statement for insertion
                $insertQuery = "INSERT INTO withdrawals (withdrawal_id, account_id, amount, withdrawal_method, withdrawal_reference, withdrawal_date, name) VALUES (?, ?, ?, ?, ?, NOW(), ?)";
                $insertStmt = $con->prepare($insertQuery);
                $insertStmt->bind_param("ssdsss", $withdrawalID, $accountID, $amount, $withdrawalMethod, $withdrawalReference, $name);
                $insertStmt->execute();

                // Set success message in session
                $_SESSION['success'] = 'Withdrawal transaction submitted successfully.';
            } else {
                // If the user has insufficient balance, show an alert
                $_SESSION['error'] = 'Insufficient balance.';
            }
        }
        // Close connection
        $con->close();

        // Redirect to the same page
        header("Location: ../admin/transactionmanagement.php");
        exit();
    }
}

// Check for success or error messages and display them
if (isset($_SESSION['success'])) {
    echo "<script>alert('{$_SESSION['success']}');</script>";
    unset($_SESSION['success']); // Clear the session variable
} elseif (isset($_SESSION['error'])) {
    echo "<script>alert('{$_SESSION['error']}');</script>";
    unset($_SESSION['error']); // Clear the session variable
}

?>

<div class="main_content">
        <h4 class=" para mt-5">Withdrawal Transaction Form</h4> <br> 
        <hr>
        <div class="modal-body mt-3">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group mt-3">
                    <label for="accountID">Account No:</label>
                    <input type="text" class="form-control" id="accountID" name="accountID" required>
                </div>
                <div class="form-group mt-3">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input type="number" class="form-control" id="amount" name="amount" required>
                </div>
                <div class="form-group">
                    <label for="withdrawalMethod">Withdrawal Method:</label>
                    <input type="text" class="form-control" id="withdrawalMethod" name="withdrawalMethod" required>
                </div>
                <div class="form-group">
                    <label for="withdrawalReference">Withdrawal Reference:</label>
                    <input type="text" class="form-control" id="withdrawalReference" name="withdrawalReference" required>
                </div>
                <button type="submit" class="btn btn-primary" name="withdrawalform">Submit</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
