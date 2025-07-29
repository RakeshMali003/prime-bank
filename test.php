<?php
session_start();

include('../user/afteruserlogin.php');

// Function to generate a 15-digit random number for transaction ID
function generateTransactionID() {
    return mt_rand(100000000000000, 999999999999999);
}

// Function to get today's date
function getTodayDate() {
    return date("Y-m-d");
}

// Process form submission
if (isset($_POST['transfer_money'])) {
    include('../connection/connection.php');

    $sender_account_no = $_SESSION['account_no'];
    $receiver_account_no = $_POST['receiver_account_no'];
    $amount = $_POST['amount'];

    // Check if amount contains only digits
    if (!ctype_digit($amount)) {
        echo '<script>alert("Amount should contain only digits.");</script>';
        exit;
    }

    // Check if sender and receiver account numbers are the same
    if ($sender_account_no == $receiver_account_no) {
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
            echo '<script>alert("Sender does not have sufficient funds.");</script>';
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
            // Similar process as updating sender's balance

            // Insert transaction details into the transactions table
            // Similar process as before

            // Close database connection
            $con->close();

            echo '<script>alert("Money transferred successfully.");</script>';
        }
    } else {
        echo '<script>alert("Sender account not found.");</script>';
    }
}
?>
