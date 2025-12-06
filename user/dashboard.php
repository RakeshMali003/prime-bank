

<div class="main_content">


<?php
include('../user/afteruserlogin.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php"); // Redirect to login page if not logged in
    exit();
}

$session_email = $_SESSION['email'];

// Fetch account number based on session email
$fetch_account_query = "SELECT account_no FROM login WHERE email = ?";
$stmt = $con->prepare($fetch_account_query);
$stmt->bind_param("s", $session_email);
$stmt->execute();
$fetch_account_result = $stmt->get_result();

if ($fetch_account_result->num_rows > 0) {
    $fetch_account_row = $fetch_account_result->fetch_assoc();
    $session_account_no = $fetch_account_row["account_no"];

    // Fetch balance based on account number
    $fetch_balance_query = "SELECT total_balance FROM balance WHERE account_no = ?";
    $stmt = $con->prepare($fetch_balance_query);
    $stmt->bind_param("s", $session_account_no);
    $stmt->execute();
    $fetch_balance_result = $stmt->get_result();

    if ($fetch_balance_result->num_rows > 0) {
        $balance_row = $fetch_balance_result->fetch_assoc();
        $total_balance = $balance_row['total_balance'];
    } else {
        // Handle balance not found
        $total_balance = "N/A";
    }
} else {
    // Handle account number not found
    echo '<script>alert("Account number not found for this session.");</script>';
}

// Close database connection
$stmt->close();
$con->close();
?>

        <h4 class="para">Dashboard</h4> <br><hr>
        <div class="" style="margin-top:100px">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">KYC Verification Required</h5>
                    <p style="font-size:13px">Dear user, we need your KYC data for some action. Don't hesitate to provide data, it's so much potential for us too. Don't worry. it's is very much secure in our system. <a href="">Click here to verify </a></p>
                    <hr>
                    <!-- Add other content related to KYC verification here -->
                </div>
            </div>

            <div class="mt-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-box">
                            <div class="card-body">
                                <h5 class="card-title">Balance</h5>
                                <p class="card-text">Total Balance: $<?php echo $total_balance; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
      <div class="card card-box">
        <div class="card-body">
          <h5 class="card-title">Deposit Money</h5>
          <!-- Display deposit information here -->
          <p class="card-text">Deposit: $5000</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-box">
        <div class="card-body">
          <h5 class="card-title">Withdrawals</h5>
          <!-- Display withdrawal information here -->
          <p class="card-text">Withdrawal: $2000</p>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4">
      <div class="card card-box">
        <div class="card-body">
          <h5 class="card-title">Total Transactions</h5>
          <!-- Display total transactions here -->
          <p class="card-text">Total Transactions: 50</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-box">
        <div class="card-body">
          <h5 class="card-title">Running Loan</h5>

          <!-- Display loan details here -->
          <p class="card-text">Loan Amount: $50000</p>
        </div>
      </div>
    </div>
  </div>
</div>
