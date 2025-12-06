<?php
include('..\user\afteruserlogin.php');

$email = $_SESSION['email'];
$sql = "SELECT * FROM `login`  WHERE email = '$email'";
$query = mysqli_query($con, $sql);
while ($result = mysqli_fetch_assoc($query)) {

    if (isset($_POST['trasferwithmobile'])) {
        // Retrieve form data
        $senderAccountId = $result['account_no']; // Sender's account number
        $senderName = $result['name'];
        $recipientMobileNumber = $_POST['recipientMobileNumber']; // Mobile number entered by the user
        $recipientName = $_POST['recipientName'];
        $amount = $_POST['amount'];

        // Check if sender has sufficient balance
        $sender_balance_query = "SELECT total_balance FROM balance WHERE account_no = ?";
        $stmt = $con->prepare($sender_balance_query);
        $stmt->bind_param("s", $senderAccountId);
        $stmt->execute();
        $sender_balance_result = $stmt->get_result();

        if ($sender_balance_result->num_rows > 0) {
            $sender_balance_row = $sender_balance_result->fetch_assoc();
            $sender_balance = (float) $sender_balance_row["total_balance"]; // Cast to float

            if ($sender_balance >= $amount) {
                // Match recipient's mobile number with their account number
                $recipient_account_query = "SELECT account_no FROM `login` WHERE mobile_no = ?";
                $stmt = $con->prepare($recipient_account_query);
                $stmt->bind_param("s", $recipientMobileNumber);
                $stmt->execute();
                $recipient_account_result = $stmt->get_result();

                if ($recipient_account_result->num_rows > 0) {
                    $recipient_account_row = $recipient_account_result->fetch_assoc();
                    $recipientAccountId = $recipient_account_row['account_no'];

                    // Generate 15-digit transaction ID
                    $transactionId = substr(str_shuffle(str_repeat('0123456789', 5)), 0, 15);

                    // Get current date and time
                    $transferDate = date('Y-m-d H:i:s');

                    // SQL query to insert data into the MobileMoneyTransfers table
                    $sql = "INSERT INTO MobileMoneyTransfers (transfer_id, sender_account_id, sender_name, recipient_mobile_number, recipient_name, amount, transfer_date) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";

                    // Prepare and bind parameters
                    $stmt = $con->prepare($sql);
                    $stmt->bind_param("sssssss", $transactionId, $senderAccountId, $senderName, $recipientMobileNumber, $recipientName, $amount, $transferDate);
                    $stmt->execute();

                    // Deduct transferred amount from sender's balance
                    $new_sender_balance = $sender_balance - $amount;

                    // Update sender's balance in the balance table
                    $update_sender_balance_query = "UPDATE balance SET total_balance = ? WHERE account_no = ?";
                    $stmt = $con->prepare($update_sender_balance_query);
                    $stmt->bind_param("is", $new_sender_balance, $senderAccountId);
                    $stmt->execute();
                    
                    // Update recipient's balance
                    $recipient_balance_query = "SELECT total_balance FROM balance WHERE account_no = ?";
                    $stmt = $con->prepare($recipient_balance_query);
                    $stmt->bind_param("s", $recipientAccountId); // Use recipient's account number
                    $stmt->execute();
                    $recipient_balance_result = $stmt->get_result();

                    if ($recipient_balance_result->num_rows > 0) {
                        $recipient_balance_row = $recipient_balance_result->fetch_assoc();
                        $recipient_balance = (float) $recipient_balance_row["total_balance"]; // Cast to float

                        // Add transferred amount to recipient's balance
                        $new_recipient_balance = $recipient_balance + $amount;

                        // Update recipient's balance in the balance table
                        $update_recipient_balance_query = "UPDATE balance SET total_balance = ? WHERE account_no = ?";
                        $stmt = $con->prepare($update_recipient_balance_query);
                        $stmt->bind_param("is", $new_recipient_balance, $recipientAccountId);
                        $stmt->execute();

                        // Display transfer successful alert
                        echo "<script> alert('Transfer successful!'); </script>";
                        ?>
                        <script>
                        location.href='..\BANK ONLINE PROJECT\user\transferwithmobile.php';
                        </script>
                        <?php
                    } else {
                        // Display error alert if recipient account not found
                        echo "<script> alert('Error: Recipient account not found.'); </script>";
                    }
                    // Stop further execution
                    exit();
                } else {
                    // Display error alert if recipient account not found
                    echo "<script> alert('Error: Recipient account not found.'); </script>";
                }
            } else {
                // Display error alert if insufficient balance
                echo "<script> alert('Error: Insufficient balance'); </script>";
            }
        } else {
            // Display error alert if sender account not found
            echo "<script> alert('Error: Sender account not found.'); </script>";
        }
    }

?>


<div class="main_content">
    <h4 class="para">Transfer Money With Mobile Number</h4> <br> <hr>

    <form method="POST" id="transferForm">
        <div class="form-group">
            <label for="senderAccountId">Sender Account No:</label>
            <input type="text" class="form-control" id="senderAccountId" name="senderAccountId"
                value="<?php echo $result['account_no']; ?>" required readonly>
        </div>
        <div class="form-group">
            <label for="senderName">Sender Name:</label>
            <input type="text" class="form-control" id="senderName" name="senderName"
                value="<?php echo $result['name']; ?>" required readonly>
        </div>

        <div class="form-group">
            <label for="recipientMobileNumber">Recipient Mobile Number:</label>
            <input type="text" class="form-control" id="recipientMobileNumber" name="recipientMobileNumber"
                required onblur="getRecipientName()">
        </div>

        <div class="form-group">
            <label for="recipientName">Recipient Name:</label>
            <input type="text" class="form-control" id="recipientName" name="recipientName" required>
        </div>

        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="text" class="form-control" id="amount" name="amount" required>
        </div>

        <button type="submit" class="btn btn-primary" name="trasferwithmobile">Send</button>
    </form>
</div>


<?php
}
?>
