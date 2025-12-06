<?php
session_start();
<<<<<<< HEAD
// Adjust path if necessary
include('../connection/connection.php');

// --- 1. SESSION & AUTH ---
if (!isset($_SESSION['email'])) {
    header('Location: ../index.php');
    exit();
}

$email = $_SESSION['email'];

// --- 2. FETCH USER DATA ---
$sql = "SELECT * FROM `login` WHERE email = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();
$stmt->close();

$account_no = $user_data['account_no']; // Current User's Account No

// Fetch Balance
$total_balance = "0.00";
$balance_sql = "SELECT total_balance FROM balance WHERE account_no = ?";
$stmt_bal = $con->prepare($balance_sql);
$stmt_bal->bind_param("s", $account_no);
$stmt_bal->execute();
$bal_result = $stmt_bal->get_result();
if ($bal_result->num_rows > 0) {
    $row_bal = $bal_result->fetch_assoc();
    $total_balance = number_format((float)$row_bal['total_balance'], 2);
    $raw_balance = (float)$row_bal['total_balance']; // For calculation
}

// --- 3. HELPER FUNCTIONS ---
if (!function_exists('generateTransactionID')) {
    function generateTransactionID() {
        return mt_rand(100000000000000, 999999999999999);
    }
}


// --- 4. HANDLE TRANSFER VIA ACCOUNT NUMBER ---
if (isset($_POST['transfer_money'])) {
    $sender_account_no = $account_no; // From Session
    $receiver_account_no = $_POST['receiver_account_no'];
    $amount = $_POST['amount'];

    if (!ctype_digit($amount)) {
        echo '<script>alert("Amount should contain only digits.");</script>';
    } elseif ($sender_account_no == $receiver_account_no) {
        echo '<script>alert("You cannot transfer money to your own account.");</script>';
    } else {
        // Check Sender Balance
        if ($raw_balance < $amount) {
            echo '<script>alert("Insufficient funds. Balance: ' . $raw_balance . '");</script>';
        } else {
            // Check if Receiver Exists & Get Balance
            $recv_sql = "SELECT total_balance FROM balance WHERE account_no = ?";
            $stmt = $con->prepare($recv_sql);
            $stmt->bind_param("s", $receiver_account_no);
            $stmt->execute();
            $recv_result = $stmt->get_result();

            if ($recv_result->num_rows > 0) {
                $recv_row = $recv_result->fetch_assoc();
                $receiver_balance = (float)$recv_row['total_balance'];

                // Perform Transfer
                $sender_new_balance = $raw_balance - $amount;
                $receiver_new_balance = $receiver_balance + $amount;

                // Update Sender
                $up_send = $con->prepare("UPDATE balance SET total_balance = ? WHERE account_no = ?");
                $up_send->bind_param("is", $sender_new_balance, $sender_account_no);
                $up_send->execute();

                // Update Receiver
                $up_recv = $con->prepare("UPDATE balance SET total_balance = ? WHERE account_no = ?");
                $up_recv->bind_param("is", $receiver_new_balance, $receiver_account_no);
                $up_recv->execute();

                // Record Transaction
                $transaction_id = generateTransactionID();
                $date = date("Y-m-d");
                
                $ins_trans = $con->prepare("INSERT INTO money_transer_accountno (account_no, amount, transaction_id, date, sender_account_no, transaction_time) VALUES (?, ?, ?, ?, ?, NOW())");
                $ins_trans->bind_param("iisss", $receiver_account_no, $amount, $transaction_id, $date, $sender_account_no);
                
                if ($ins_trans->execute()) {
                    echo "<script>alert('Money transferred successfully.'); window.location.href='afteruserlogin.php';</script>";
                }
            } else {
                echo '<script>alert("Receiver account not found.");</script>';
            }
        }
    }
}

// --- 5. HANDLE TRANSFER VIA MOBILE NUMBER ---
if (isset($_POST['trasferwithmobile'])) {
    $senderName = $user_data['name'];
    $recipientMobileNumber = $_POST['recipientMobileNumber'];
    $recipientName = $_POST['recipientName'];
    $amount = $_POST['amount'];

    if ($raw_balance >= $amount) {
        // Find Account by Mobile
        $mob_sql = "SELECT account_no FROM `login` WHERE mobile_no = ?";
        $stmt = $con->prepare($mob_sql);
        $stmt->bind_param("s", $recipientMobileNumber);
        $stmt->execute();
        $mob_res = $stmt->get_result();

        if ($mob_res->num_rows > 0) {
            $mob_row = $mob_res->fetch_assoc();
            $recipientAccountId = $mob_row['account_no'];

            // Generate ID
            $transactionId = substr(str_shuffle(str_repeat('0123456789', 5)), 0, 15);
            $transferDate = date('Y-m-d H:i:s');

            // Insert into Mobile Transfers Table
            $ins_mob = $con->prepare("INSERT INTO MobileMoneyTransfers (transfer_id, sender_account_id, sender_name, recipient_mobile_number, recipient_name, amount, transfer_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $ins_mob->bind_param("sssssss", $transactionId, $account_no, $senderName, $recipientMobileNumber, $recipientName, $amount, $transferDate);
            $ins_mob->execute();

            // Deduct Sender
            $new_sender_bal = $raw_balance - $amount;
            $up_s = $con->prepare("UPDATE balance SET total_balance = ? WHERE account_no = ?");
            $up_s->bind_param("is", $new_sender_bal, $account_no);
            $up_s->execute();

            // Add to Recipient
            // First fetch recipient current balance
            $r_bal_q = $con->prepare("SELECT total_balance FROM balance WHERE account_no = ?");
            $r_bal_q->bind_param("s", $recipientAccountId);
            $r_bal_q->execute();
            $r_res = $r_bal_q->get_result();
            $r_row = $r_res->fetch_assoc();
            $r_current = (float)$r_row['total_balance'];
            
            $new_rec_bal = $r_current + $amount;
            $up_r = $con->prepare("UPDATE balance SET total_balance = ? WHERE account_no = ?");
            $up_r->bind_param("is", $new_rec_bal, $recipientAccountId);
            $up_r->execute();

            echo "<script>alert('Mobile Transfer successful!'); window.location.href='afteruserlogin.php';</script>";

        } else {
            echo "<script>alert('Error: Recipient mobile number not linked to any account.');</script>";
        }
    } else {
        echo "<script>alert('Error: Insufficient balance');</script>";
    }
}

// --- 6. HANDLE PROFILE UPDATE ---
if(isset($_POST['update_profile'])){
    $lname = $_POST['lname'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    
    $update_sql = "UPDATE `login` SET lname=?, address=?, city=?, state=?, zip=? WHERE email=?";
    $stmt_up = $con->prepare($update_sql);
    $stmt_up->bind_param("ssssss", $lname, $address, $city, $state, $zip, $email);
    
    if($stmt_up->execute()){
        echo "<script>alert('Profile Updated Successfully'); window.location.href='afteruserlogin.php';</script>";
    } else {
        echo "<script>alert('Update Failed');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextGen Banking | <?php echo htmlspecialchars($user_data['name']); ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary: #4e22a1;
            --primary-dark: #3a187a;
            --secondary: #f4f7fa;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --sidebar-width: 280px;
        }

        body { font-family: 'Inter', sans-serif; background-color: var(--secondary); color: var(--text-dark); overflow-x: hidden; }

        /* --- SIDEBAR --- */
        .sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; top: 0; left: 0; background: #fff; border-right: 1px solid #e2e8f0; z-index: 1000; transition: margin-left 0.3s; overflow-y: auto; }
        .sidebar-brand { padding: 25px; display: flex; align-items: center; color: var(--primary); font-weight: 700; font-size: 1.5rem; border-bottom: 1px solid #f1f5f9; }
        .sidebar-menu { list-style: none; padding: 20px 15px; }
        .sidebar-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin: 20px 0 10px 15px; font-weight: 600; }
        .sidebar-menu li a { display: flex; align-items: center; padding: 12px 15px; color: var(--text-dark); text-decoration: none; border-radius: 10px; margin-bottom: 5px; font-weight: 500; transition: 0.2s; cursor: pointer; }
        .sidebar-menu li a:hover, .sidebar-menu li a.active-link { background-color: rgba(78, 34, 161, 0.08); color: var(--primary); }
        .sidebar-menu li a i { width: 25px; margin-right: 10px; color: var(--text-muted); }
        .sidebar-menu li a:hover i, .sidebar-menu li a.active-link i { color: var(--primary); }

        /* --- MAIN CONTENT --- */
        .main-content { margin-left: var(--sidebar-width); padding: 30px; min-height: 100vh; transition: margin-left 0.3s; }

        /* --- COMPONENTS --- */
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .stat-card { background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); border: 1px solid #f1f5f9; height: 100%; transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-3px); }

        /* Credit Card Visual */
        .credit-card-visual { background: linear-gradient(135deg, #4e22a1 0%, #a855f7 100%); border-radius: 20px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(78, 34, 161, 0.4); height: 200px; display: flex; flex-direction: column; justify-content: space-between; }
        .credit-card-visual::before { content: ''; position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; border-radius: 50%; background: rgba(255,255,255,0.1); }
        .card-chip { width: 40px; height: 30px; background: #fbbf24; border-radius: 6px; margin-bottom: 10px; }

        /* Buttons */
        .quick-action-btn { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 15px; background: white; border: 1px solid #e2e8f0; border-radius: 12px; text-decoration: none; color: var(--text-dark); transition: 0.2s; height: 100px; cursor: pointer;}
        .quick-action-btn:hover { border-color: var(--primary); background: rgba(78, 34, 161, 0.02); color: var(--primary); }
        .quick-action-btn i { font-size: 24px; margin-bottom: 8px; color: var(--primary); }

        /* Tab Switcher Logic */
        .content-section { display: none; animation: fadeIn 0.4s; }
        .content-section.active-section { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        @media (max-width: 768px) {
            .sidebar { margin-left: -280px; }
            .sidebar.active { margin-left: 0; }
            .main-content { margin-left: 0; padding: 15px; }
        }
    </style>
</head>
<body>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-layer-group me-2"></i> NextGen
        </div>
        
        <ul class="sidebar-menu">
            <div class="sidebar-label">Main Menu</div>
            <li><a onclick="switchSection('dashboard')" id="link-dashboard" class="nav-link-item active-link"><i class="fas fa-th-large"></i> Dashboard</a></li>
            <li><a onclick="switchSection('profile')" id="link-profile" class="nav-link-item"><i class="fas fa-user-shield"></i> Profile & KYC</a></li>
            
            <div class="sidebar-label">Finance</div>
            <li><a onclick="switchSection('transfers')" id="link-transfers" class="nav-link-item"><i class="fas fa-paper-plane"></i> Transfers & Pay</a></li>
            <li><a onclick="switchSection('history')" id="link-history" class="nav-link-item"><i class="fas fa-history"></i> Transactions</a></li>
            <li><a onclick="switchSection('cards')" id="link-cards" class="nav-link-item"><i class="fas fa-credit-card"></i> Cards & Loans</a></li>
            
            <div class="sidebar-label">Settings</div>
            <li><a href="#" class="nav-link-item"><i class="fas fa-headset"></i> Support & Help</a></li>
            <li><a href="userlogout.php" style="color: var(--danger);"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <div class="main-content">
        
        <div class="top-bar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-md-none me-3" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <div>
                    <h5 class="mb-0 fw-bold">Hello, <?php echo htmlspecialchars($user_data['name']); ?> 👋</h5>
                    <small class="text-muted">Welcome back to your financial hub.</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-white position-relative shadow-sm border rounded-circle" style="width:45px;height:45px">
                    <i class="fas fa-bell text-muted"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                </button>
                <img src="<?php echo !empty($user_data['image']) ? $user_data['image'] : 'https://via.placeholder.com/50'; ?>" class="rounded-circle shadow-sm border" width="45" height="45" style="object-fit: cover;">
            </div>
        </div>

        <div id="dashboard" class="content-section active-section">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="stat-card bg-primary text-white mb-4" style="border:none; background: linear-gradient(135deg, #4e22a1 0%, #2e1065 100%);">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-0 opacity-75">Total Balance</p>
                                <h2 class="fw-bold mb-1">₹<?php echo $total_balance; ?></h2>
                                <small class="badge bg-white text-primary rounded-pill px-2">Account: <?php echo $account_no; ?></small>
                            </div>
                            <div class="p-2 bg-white bg-opacity-10 rounded"><i class="fas fa-wallet fs-4"></i></div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold m-0">Recent Transactions</h6>
                            <a onclick="switchSection('history')" class="text-primary text-decoration-none small fw-bold" style="cursor:pointer">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr><th class="border-0">User</th><th class="border-0">Date</th><th class="border-0 text-end">Amount</th></tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Merged logic for dashboard preview
                                    $dash_sql = "SELECT sender_account_no as details, amount, transaction_time as tdate FROM `money_transer_accountno` WHERE account_no = '$account_no' 
                                                 UNION 
                                                 SELECT recipient_name as details, amount, transfer_date as tdate FROM `mobilemoneytransfers` WHERE sender_account_id = '$account_no' 
                                                 ORDER BY tdate DESC LIMIT 3";
                                    $dash_res = mysqli_query($con, $dash_sql);
                                    if(mysqli_num_rows($dash_res) > 0){
                                        while($drow = mysqli_fetch_assoc($dash_res)){
                                            echo "<tr>
                                                <td>
                                                    <div class='d-flex align-items-center'>
                                                        <div class='bg-light rounded-circle p-2 me-2'><i class='fas fa-user text-primary'></i></div>
                                                        <div><div class='fw-bold'>{$drow['details']}</div></div>
                                                    </div>
                                                </td>
                                                <td class='text-muted small'>".date('M d', strtotime($drow['tdate']))."</td>
                                                <td class='text-end fw-bold text-danger'>- ₹{$drow['amount']}</td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='3' class='text-center text-muted'>No recent transactions</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="credit-card-visual mb-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png" width="40" alt="Mastercard">
                            <span class="text-white opacity-75 small">Platinum Debit</span>
                        </div>
                        <div class="mt-4">
                            <div class="card-chip"></div>
                            <h5 class="font-monospace mb-1">**** **** **** <?php echo substr($account_no, -4); ?></h5>
                        </div>
                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <div class="small opacity-75">Card Holder</div>
                                <div class="fw-bold text-uppercase"><?php echo $user_data['name']; ?></div>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold text-muted mb-3 small text-uppercase">Quick Actions</h6>
                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <div onclick="switchSection('transfers')" class="quick-action-btn">
                                <i class="fas fa-exchange-alt"></i>
                                <span class="small fw-bold">Transfer</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="quick-action-btn">
                                <i class="fas fa-qrcode"></i>
                                <span class="small fw-bold">Scan & Pay</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="transfers" class="content-section">
            <h4 class="fw-bold mb-4">Transfer Money</h4>
            
            <div class="stat-card">
                <ul class="nav nav-pills mb-4 bg-light p-1 rounded" id="transferTabs" role="tablist">
                    <li class="nav-item flex-grow-1 text-center" role="presentation">
                        <button class="nav-link w-100 active rounded" id="acc-tab" data-bs-toggle="pill" data-bs-target="#pills-account" type="button">Via Account No</button>
                    </li>
                    <li class="nav-item flex-grow-1 text-center" role="presentation">
                        <button class="nav-link w-100 rounded" id="mobile-tab" data-bs-toggle="pill" data-bs-target="#pills-mobile" type="button">Via Mobile No</button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    
                    <div class="tab-pane fade show active" id="pills-account">
                        <form method="post" class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Sender Account (You)</label>
                                <input type="text" class="form-control bg-light" value="<?php echo $account_no; ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Current Balance</label>
                                <input type="text" class="form-control bg-light" value="₹<?php echo $total_balance; ?>" readonly>
                            </div>
                            <div class="col-12">
                                <hr class="my-2">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Receiver Account Number</label>
                                <input type="text" class="form-control" name="receiver_account_no" placeholder="Enter beneficiary account" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Amount (₹)</label>
                                <input type="number" class="form-control" name="amount" placeholder="0.00" required>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" name="transfer_money" class="btn btn-primary w-100 py-2"><i class="fas fa-paper-plane me-2"></i> Send Money Now</button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="pills-mobile">
                        <form method="post" class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Sender Name</label>
                                <input type="text" class="form-control bg-light" value="<?php echo $user_data['name']; ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Sender Account</label>
                                <input type="text" class="form-control bg-light" value="<?php echo $account_no; ?>" readonly>
                            </div>
                            <div class="col-12">
                                <hr class="my-2">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Recipient Mobile Number</label>
                                <input type="text" class="form-control" name="recipientMobileNumber" placeholder="10-digit mobile number" required>
                                <div class="form-text">Mobile number must be linked to a NextGen account.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Recipient Name</label>
                                <input type="text" class="form-control" name="recipientName" placeholder="Verify Name" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Amount (₹)</label>
                                <input type="text" class="form-control" name="amount" placeholder="0.00" required>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" name="trasferwithmobile" class="btn btn-primary w-100 py-2"><i class="fas fa-mobile-alt me-2"></i> Pay via Mobile</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <div id="history" class="content-section">
            <h4 class="fw-bold mb-4">Transaction History</h4>
            <div class="stat-card">
                <div class="table-responsive">
                    <table id="table_all" class="table table-hover" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>Trans ID</th>
                                <th>Date</th>
                                <th>Details</th>
                                <th>Type</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // UNION QUERY to merge Bank Transfers and Mobile Transfers
                            $sql_history = "SELECT transaction_id as id, transaction_time as tdate, sender_account_no as details, amount, 'Bank Transfer' as type FROM `money_transer_accountno` WHERE account_no = '$account_no' 
                                            UNION 
                                            SELECT transfer_id as id, transfer_date as tdate, recipient_name as details, amount, 'Mobile Transfer' as type FROM `mobilemoneytransfers` WHERE sender_account_id = '$account_no'";
                            
                            $query_hist = mysqli_query($con, $sql_history);
                            if ($query_hist) {
                                while($row = mysqli_fetch_assoc($query_hist)){
                            ?>
                            <tr>
                                <td><span class="font-monospace text-muted small">#<?php echo $row['id']; ?></span></td>
                                <td><?php echo date('M d, Y', strtotime($row['tdate'])); ?></td>
                                <td>
                                    <div class="fw-bold"><?php echo $row['details']; ?></div>
                                    <small class="text-muted"><?php echo $row['type']; ?></small>
                                </td>
                                <td><span class="badge bg-danger bg-opacity-10 text-danger">Debit</span></td>
                                <td class="text-end fw-bold">₹<?php echo $row['amount']; ?></td>
                            </tr>
                            <?php 
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div id="profile" class="content-section">
            <h4 class="fw-bold mb-4">My Profile</h4>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="stat-card text-center">
                        <img src="<?php echo !empty($user_data['image']) ? $user_data['image'] : 'https://via.placeholder.com/150'; ?>" class="rounded-circle mb-3 shadow" width="120" height="120" style="object-fit:cover">
                        <h5 class="fw-bold"><?php echo $user_data['name']; ?></h5>
                        <p class="text-muted small">Account ID: <?php echo $user_data['account_no']; ?></p>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="stat-card">
                        <ul class="nav nav-tabs mb-4">
                            <li class="nav-item"><a class="nav-link active">Personal Info</a></li>
                        </ul>
                        <form method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="small text-muted">First Name</label>
                                    <input type="text" class="form-control" value="<?php echo $user_data['name']; ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted">Last Name</label>
                                    <input type="text" name="lname" class="form-control" value="<?php echo $user_data['lname']; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted">Mobile</label>
                                    <input type="text" class="form-control" value="<?php echo $user_data['mobile_no']; ?>" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="small text-muted">Address</label>
                                    <input type="text" name="address" class="form-control" value="<?php echo $user_data['address']; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="small text-muted">City</label>
                                    <input type="text" name="city" class="form-control" value="<?php echo $user_data['city']; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="small text-muted">State</label>
                                    <select name="state" class="form-select">
                                        <option selected><?php echo $user_data['state']; ?></option>
                                        <option value="Rajasthan">Rajasthan</option>
                                        <option value="Daman">Daman</option>
                                        <option value="Gujarat">Gujarat</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="small text-muted">Zip Code</label>
                                    <input type="text" name="zip" class="form-control" value="<?php echo $user_data['zip']; ?>">
                                </div>
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-primary mt-4">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="cards" class="content-section">
            <h4 class="fw-bold mb-4">Cards & Loans</h4>
            <div class="alert alert-info">Feature coming soon...</div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#table_all').DataTable();
        });

        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('active'); }

        function switchSection(sectionId) {
            // Hide all sections
            $('.content-section').removeClass('active-section');
            // Remove active link styles
            $('.nav-link-item').removeClass('active-link');
            
            // Show target section
            $('#' + sectionId).addClass('active-section');
            // Highlight sidebar link
            $('#link-' + sectionId).addClass('active-link');
            
            if(window.innerWidth < 768) { document.getElementById('sidebar').classList.remove('active'); }
        }
    </script>
</body>
</html>









=======

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
>>>>>>> 75138d4784e452aef7ef999cabc36ef03d0f92ec
