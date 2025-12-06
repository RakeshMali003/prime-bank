<?php
// Start session
session_start();

// Include database connection
include('../connection/connection.php');

// Function to sanitize input data
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Function to generate a 15-digit deposit ID
function generateDepositID() {
    return time() . mt_rand(10000, 99999);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['depositform'])) {
    // Validate and sanitize input fields
    $accountID = sanitize_input($_POST["account_id"]);
    $depositorName = sanitize_input($_POST["depositor_name"]);
    $amount = sanitize_input($_POST["amount"]);
    $depositMethod = sanitize_input($_POST["deposit_method"]);
    $depositReference = sanitize_input($_POST["deposit_reference"]);

    // Validate input fields
    if (!preg_match("/^[a-zA-Z ]*$/", $depositorName)) {
        // Check if name contains only letters and whitespace
        $_SESSION['error'] = 'Depositor name should contain only letters and whitespace.';
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
            // Insert deposit record into database
            $depositID = generateDepositID();
            $depositDate = date("Y-m-d");
            $chequeNo = null;
            $chequeName = null;
            $bankName = null;
            $chequeDepositAcNo = null;

            if ($depositMethod == "cheque") {
                $chequeNo = sanitize_input($_POST["cheque_no"]);
                $chequeName = sanitize_input($_POST["cheque_name"]);
                $bankName = sanitize_input($_POST["bank_name"]);
                $chequeDepositAcNo = sanitize_input($_POST["cheque_deposit_ac_no"]);
            }

            // Insert into deposit table
            $insertQuery = "INSERT INTO deposit (deposit_id, account_id, amount, deposit_method, deposit_reference, deposit_date, cheque_no, cheque_name, bank_name, cheque_deposit_ac_no) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $con->prepare($insertQuery);
            $insertStmt->bind_param("ssdsssssss", $depositID, $accountID, $amount, $depositMethod, $depositReference, $depositDate, $chequeNo, $chequeName, $bankName, $chequeDepositAcNo);
            $insertStmt->execute();

            // Update balance in balance table
            $updateBalanceQuery = "UPDATE balance SET total_balance = total_balance + ? WHERE account_no = ?";
            $updateBalanceStmt = $con->prepare($updateBalanceQuery);
            $updateBalanceStmt->bind_param("ds", $amount, $accountID);
            $updateBalanceStmt->execute();

            // Insert into credit table
            $insertCreditQuery = "INSERT INTO credit (account_id, amount, transaction_id, transaction_date) VALUES (?, ?, ?, ?)";
            $transactionID = generateDepositID(); // Assuming deposit ID as transaction ID
            $transactionDate = date("Y-m-d H:i:s");
            $insertCreditStmt = $con->prepare($insertCreditQuery);
            $insertCreditStmt->bind_param("sdsd", $accountID, $amount, $transactionID, $transactionDate);
            $insertCreditStmt->execute();

            $_SESSION['success'] = 'Deposit transaction submitted successfully.';
        }
    }
    // Close connection
    $con->close();

    // Redirect to the same page
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
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
        <h4 class="para mt-5">Deposit Transaction Form</h4> <br> <hr>
   
        <div class="modal-body mt-3">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group mt-3">
                    <label for="account_id">Account No:</label>
                    <input type="text" class="form-control" id="account_id" name="account_id" required>
                </div>
                <div class="form-group">
                    <label for="depositor_name">Depositor Name:</label>
                    <input type="text" class="form-control" id="depositor_name" name="depositor_name" required>
                </div>
                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input type="text" class="form-control" id="amount" name="amount" required>
                </div>
                <div class="form-group">
                    <label for="deposit_method">Deposit Method:</label>
                    <select class="form-control" id="deposit_method" name="deposit_method" required>
                        <option value="cash">Cash</option>
                        <option value="cheque">Cheque</option>
                    </select>
                </div>
                <div class="form-group" id="cheque_fields" style="display: none;">
                    <label for="cheque_no">Cheque No:</label>
                    <input type="text" class="form-control" id="cheque_no" name="cheque_no">
                </div>
                <div class="form-group" id="cheque_name_fields" style="display: none;">
                    <label for="cheque_name">Cheque Name:</label>
                    <input type="text" class="form-control" id="cheque_name" name="cheque_name">
                </div>
                <div class="form-group" id="bank_name_fields" style="display: none;">
                    <label for="bank_name">Bank Name:</label>
                    <input type="text" class="form-control" id="bank_name" name="bank_name">
                </div>
                <div class="form-group" id="cheque_deposit_ac_no_fields" style="display: none;">
                    <label for="cheque_deposit_ac_no">Cheque Deposit Ac No:</label>
                    <input type="text" class="form-control" id="cheque_deposit_ac_no" name="cheque_deposit_ac_no">
                </div>
                <button type="submit" class="btn btn-primary" name="depositform">Submit</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Show/hide cheque fields based on deposit method selection
        document.getElementById('deposit_method').addEventListener('change', function () {
            var chequeFields = document.getElementById('cheque_fields');
            var chequeNameFields = document.getElementById('cheque_name_fields');
            var bankNameFields = document.getElementById('bank_name_fields');
            var chequeDepositAcNoFields = document.getElementById('cheque_deposit_ac_no_fields');

            if (this.value === 'cheque') {
                chequeFields.style.display = 'block';
                chequeNameFields.style.display = 'block';
                bankNameFields.style.display = 'block';
                chequeDepositAcNoFields.style.display = 'block';
            } else {
                chequeFields.style.display = 'none';
                chequeNameFields.style.display = 'none';
                bankNameFields.style.display = 'none';
                chequeDepositAcNoFields.style.display = 'none';
            }
        });
    </script>

<?php
include('..\admin\adminfotter.php');
?>
