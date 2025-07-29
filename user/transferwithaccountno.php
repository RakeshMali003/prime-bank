<?php
include('..\user\afteruserlogin.php');

// Function to generate a 15-digit random number for transaction ID
function generateTransactionID() {
    return mt_rand(100000000000000, 999999999999999);
}

// Function to get today's date
function getTodayDate() {
    return date("Y-m-d");
}

// Fetch account number from login table based on session email-
$session_email = $_SESSION['email'];
$fetch_account_query = "SELECT account_no FROM login WHERE email = ?";
$stmt = $con->prepare($fetch_account_query);
$stmt->bind_param("s", $session_email);
$stmt->execute();
$fetch_account_result = $stmt->get_result();

if ($fetch_account_result->num_rows > 0) {
    $fetch_account_row = $fetch_account_result->fetch_assoc();
    $session_account_no = $fetch_account_row["account_no"];
} else {
    echo '<script>alert("Account number not found for this session.");</script>';
}

// Process form submission
if (isset($_POST['transfer_money'])) {
    $sender_account_no = $session_account_no;
    $account_no = $_POST['account_no'];
    $amount = $_POST['amount'];

    // Check if amount contains only digits
    if (!ctype_digit($amount)) {
        echo '<script>alert("Amount should contain only digits.");</script>';
        exit; // Exit the script if the amount is not valid
    }

    // Check if sender and receiver account numbers are the same
    if ($sender_account_no == $account_no) {
        echo '<script>alert("Sender and receiver account numbers cannot be the same.");</script>';
        exit;
    }

    // Check if sender has sufficient funds
    $sender_balance_query = "SELECT total_balance FROM balance WHERE account_no = ?";
    $stmt = $con->prepare($sender_balance_query);
    $stmt->bind_param("s", $sender_account_no);
    $stmt->execute();
    $sender_balance_result = $stmt->get_result();

    if ($sender_balance_result->num_rows > 0) {
        $sender_balance_row = $sender_balance_result->fetch_assoc();
        $sender_balance = $sender_balance_row["total_balance"];

        if ($sender_balance < $amount) {
            echo '<script>alert("Sender does not have sufficient funds. Balance: ' . $sender_balance . ', Amount: ' . $amount . '");</script>';
            exit;
        } else {
            // Proceed with the transfer

            // Update sender's balance
            $sender_new_balance = $sender_balance - $amount;

            $update_sender_balance_query = "UPDATE balance SET total_balance = ? WHERE account_no = ?";
            $stmt = $con->prepare($update_sender_balance_query);
            $stmt->bind_param("is", $sender_new_balance, $sender_account_no);
            $stmt->execute();

            // Update receiver's balance
            $receiver_balance_query = "SELECT total_balance FROM balance WHERE account_no = ?";
            $stmt = $con->prepare($receiver_balance_query);
            $stmt->bind_param("s", $account_no);
            $stmt->execute();
            $receiver_balance_result = $stmt->get_result();

            if ($receiver_balance_result->num_rows > 0) {
                $receiver_balance_row = $receiver_balance_result->fetch_assoc();
                $receiver_balance = $receiver_balance_row["total_balance"];

                $receiver_balance = floatval($receiver_balance);
                $amount = floatval($amount);
                
                // Perform the arithmetic operation
                $receiver_new_balance = $receiver_balance + $amount;

                $update_receiver_balance_query = "UPDATE balance SET total_balance = ? WHERE account_no = ?";
                $stmt = $con->prepare($update_receiver_balance_query);
                $stmt->bind_param("is", $receiver_new_balance, $account_no);
                $stmt->execute();

                // Generate transaction ID
                $transaction_id = generateTransactionID();

                // Get today's date
                $date = getTodayDate();

                // Insert transaction details into the transactions table including the current timestamp
                $insert_transaction_query = "INSERT INTO money_transer_accountno (account_no, amount, transaction_id, date, sender_account_no, transaction_time) VALUES (?, ?, ?, ?, ?, NOW())";
                $stmt = $con->prepare($insert_transaction_query);
                $stmt->bind_param("iisss", $account_no, $amount, $transaction_id, $date, $sender_account_no);
                if ($stmt->execute()) {
                    echo '<script>alert("Money transferred successfully.");</script>';
                } else {
                    echo "Error: " . $insert_transaction_query . "<br>" . $con->error;
                }
            } else {
                echo '<script>alert("Receiver account not found.");</script>';
            }
        }
    } else {
        echo '<script>alert("Sender account not found.");</script>';
    }

    $con->close();
}
?>

<div class="main_content">
    <h4 class="para">Transfer Money With Account</h4> <br> <hr>

    <form method="post">
        <div class="form-group">
            <label for="account_no">Your Account Number (Sender):</label>
            <input type="text" class="form-control" id="account_no" name="account_no" value="<?php echo $session_account_no; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="receiver_account_no">Receiver Account Number:</label>
            <input type="text" class="form-control" id="receiver_account_no" name="account_no" required>
        </div>
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" class="form-control" id="amount" name="amount" required>
        </div>
        <button type="submit" name="transfer_money" class="btn btn-primary">Transfer Money</button>
    </form>
</div>
