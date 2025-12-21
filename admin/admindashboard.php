<?php

// =================================================================================
//  PRIME BANK - ULTIMATE ADMIN PANEL (FULL DATABASE INTEGRATION)
// =================================================================================
session_start();
$host = 'localhost'; $dbname = 'primebank'; $username = 'root'; $password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) { die("DB Connection Failed: " . $e->getMessage()); }

// --- HELPERS ---
function sanitize($data) { return htmlspecialchars(stripslashes(trim($data))); }
function genID() { return time() . mt_rand(100, 999); } // Generates IDs similar to your dump (e.g., 1712...)
function logAction($pdo, $msg, $type='INFO') {
    $pdo->prepare("INSERT INTO logs (message, type, date) VALUES (?, ?, NOW())")->execute([$msg, $type]);
}

$msg = ""; 

// =================================================================================
//  1. BACKEND CONTROLLER
// =================================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- CREATE CUSTOMER ---
    if (isset($_POST['action']) && $_POST['action'] == 'add_customer') {
        $acc = mt_rand(100000000000, 999999999999);
        $pwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        try {
            $pdo->beginTransaction();
            // 1. Insert Login
            $sql = "INSERT INTO login (account_no, name, lname, email, mobile_no, address, city, state, zip, password, image) VALUES (?,?,?,?,?,?,?,?,?,?, '')";
            $pdo->prepare($sql)->execute([$acc, $_POST['name'], $_POST['lname'], $_POST['email'], $_POST['mobile'], $_POST['address'], $_POST['city'], $_POST['state'], $_POST['zip'], $pwd]);
            
            // 2. Insert Balance
            $pdo->prepare("INSERT INTO balance (account_no, total_balance) VALUES (?, 0)")->execute([$acc]);
            
            // 3. Insert Extended Details (KYC Pending)
            $pdo->prepare("INSERT INTO user_extended_details (account_no, kyc_status, account_type) VALUES (?, 'Pending', 'Savings')")->execute([$acc]);

            $pdo->commit();
            logAction($pdo, "Created Customer: $acc", "SUCCESS");
            $msg = "Customer Created Successfully! AC: $acc";
        } catch (Exception $e) { 
            $pdo->rollBack();
            $msg = "Error: " . $e->getMessage(); 
        }
    }

    // --- DEPOSIT ---
    if (isset($_POST['action']) && $_POST['action'] == 'deposit') {
        $acc = $_POST['account_id'];
        $amt = (float)$_POST['amount'];
        $method = $_POST['deposit_method'];
        $did = genID();

        // Cheque Details
        $c_no = ($method=='cheque') ? $_POST['cheque_no'] : null;
        $c_name = ($method=='cheque') ? $_POST['cheque_name'] : null;
        $c_bank = ($method=='cheque') ? $_POST['bank_name'] : null;
        $c_d_acc = ($method=='cheque') ? $_POST['cheque_deposit_ac_no'] : null;

        $chk = $pdo->prepare("SELECT account_no FROM login WHERE account_no=?");
        $chk->execute([$acc]);
        if($chk->rowCount() > 0){
            $sql = "INSERT INTO deposit (deposit_id, account_id, amount, deposit_method, deposit_reference, deposit_date, cheque_no, cheque_name, bank_name, cheque_deposit_ac_no) VALUES (?,?,?,?,?,NOW(),?,?,?,?)";
            $pdo->prepare($sql)->execute([$did, $acc, $amt, $method, $_POST['deposit_reference'], $c_no, $c_name, $c_bank, $c_d_acc]);
            
            $pdo->prepare("UPDATE balance SET total_balance = total_balance + ? WHERE account_no = ?")->execute([$amt, $acc]);
            
            // Mirror in Credit Table (as per your DB structure)
            $pdo->prepare("INSERT INTO credit (account_id, amount, transaction_id, transaction_date) VALUES (?,?,?,NOW())")->execute([$acc, $amt, $did]);

            logAction($pdo, "Deposit $$amt to $acc", "TRANSACTION");
            $msg = "Deposit Successful";
        } else { $msg = "Account Not Found"; }
    }

    // --- WITHDRAWAL ---
    if (isset($_POST['action']) && $_POST['action'] == 'withdraw') {
        $acc = $_POST['account_id'];
        $amt = (float)$_POST['amount'];
        $wid = genID();
        
        $bQ = $pdo->prepare("SELECT total_balance FROM balance WHERE account_no=?");
        $bQ->execute([$acc]);
        $bal = $bQ->fetchColumn();

        if($bal !== false && $bal >= $amt){
            $sql = "INSERT INTO withdrawals (withdrawal_id, account_id, amount, withdrawal_method, withdrawal_reference, withdrawal_date, name) VALUES (?,?,?,?,?,NOW(),?)";
            $pdo->prepare($sql)->execute([$wid, $acc, $amt, $_POST['method'], $_POST['ref'], $_POST['name']]);
            
            $pdo->prepare("UPDATE balance SET total_balance = total_balance - ? WHERE account_no = ?")->execute([$amt, $acc]);
            
            // Mirror in Debit Table
            $pdo->prepare("INSERT INTO debit (account_no, amount, reason, date) VALUES (?,?,?,NOW())")->execute([$acc, $amt, $_POST['ref']]);

            logAction($pdo, "Withdraw $$amt from $acc", "TRANSACTION");
            $msg = "Withdrawal Successful";
        } else { $msg = "Insufficient Funds or Invalid Account"; }
    }

    // --- LOAN STATUS UPDATE ---
    if (isset($_POST['action']) && $_POST['action'] == 'update_loan') {
        $lid = $_POST['loan_id'];
        $status = $_POST['status'];
        $pdo->prepare("UPDATE loans SET status = ? WHERE loan_id = ?")->execute([$status, $lid]);
        logAction($pdo, "Loan $lid set to $status", "ADMIN");
        $msg = "Loan Status Updated";
    }

    // --- ADD STAFF ---
    if (isset($_POST['action']) && $_POST['action'] == 'add_staff') {
        $pdo->prepare("INSERT INTO staff (staff_id, name, role, status) VALUES (?,?,?,?)")->execute([genID(), $_POST['name'], $_POST['role'], 'Active']);
        $msg = "Staff Added";
    }

    // --- DELETE GENERIC ---
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $tbl = $_POST['table']; $col = $_POST['col']; $id = $_POST['id'];
        $allowed = ['login', 'staff', 'deposit', 'withdrawals']; // Security whitelist
        if(in_array($tbl, $allowed)){
            $pdo->prepare("DELETE FROM $tbl WHERE $col = ?")->execute([$id]);
            if($tbl == 'login') {
                $pdo->prepare("DELETE FROM balance WHERE account_no = ?")->execute([$id]);
                $pdo->prepare("DELETE FROM user_extended_details WHERE account_no = ?")->execute([$id]);
            }
            $msg = "Record Deleted";
        }
    }
}

// =================================================================================
//  2. DATA FETCHING
// =================================================================================

// Stats
$tot_cust = $pdo->query("SELECT COUNT(*) FROM login")->fetchColumn();
$tot_dep = $pdo->query("SELECT SUM(amount) FROM deposit")->fetchColumn() ?: 0;
$tot_wd = $pdo->query("SELECT SUM(amount) FROM withdrawals")->fetchColumn() ?: 0;
$tot_loan = $pdo->query("SELECT SUM(loan_amount) FROM loans WHERE status='Active'")->fetchColumn() ?: 0;

// Customers
$cust_html = "";
$q = $pdo->query("SELECT l.*, b.total_balance, u.kyc_status FROM login l LEFT JOIN balance b ON l.account_no = b.account_no LEFT JOIN user_extended_details u ON l.account_no = u.account_no ORDER BY l.account_no DESC LIMIT 20");
while($r = $q->fetch()){
    $kyc = $r['kyc_status'] == 'Verified' ? 'st-active' : 'st-pending';
    $cust_html .= "<tr><td>{$r['account_no']}</td><td>{$r['name']} {$r['lname']}</td><td>\${$r['total_balance']}</td><td>{$r['mobile_no']}</td><td><span class='status $kyc'>{$r['kyc_status']}</span></td>
    <td><form method='POST' style='display:inline' onsubmit='return confirm(\"Delete?\")'><input type='hidden' name='action' value='delete'><input type='hidden' name='table' value='login'><input type='hidden' name='col' value='account_no'><input type='hidden' name='id' value='{$r['account_no']}'><button class='btn-xs btn-danger'>Del</button></form></td></tr>";
}

// Deposits
$dep_html = "";
$q = $pdo->query("SELECT * FROM deposit ORDER BY deposit_date DESC LIMIT 10");
while($r = $q->fetch()){
    $dep_html .= "<tr><td>{$r['deposit_id']}</td><td>{$r['account_id']}</td><td style='color:var(--success)'>+\${$r['amount']}</td><td>{$r['deposit_method']}</td><td>{$r['deposit_date']}</td></tr>";
}

// Withdrawals
$wd_html = "";
$q = $pdo->query("SELECT * FROM withdrawals ORDER BY withdrawal_date DESC LIMIT 10");
while($r = $q->fetch()){
    $wd_html .= "<tr><td>{$r['withdrawal_id']}</td><td>{$r['account_id']}</td><td style='color:var(--danger)'>-\${$r['amount']}</td><td>{$r['withdrawal_method']}</td><td>{$r['withdrawal_date']}</td></tr>";
}

// Transfers (Online)
$tf_html = "";
$q = $pdo->query("SELECT * FROM money_transer_accountno ORDER BY transaction_time DESC LIMIT 10");
while($r = $q->fetch()){
    $tf_html .= "<tr><td>{$r['transaction_id']}</td><td>{$r['sender_account_no']} -> {$r['account_no']}</td><td>\${$r['amount']}</td><td>{$r['date']}</td></tr>";
}

// Loans
$loan_html = "";
$q = $pdo->query("SELECT * FROM loans ORDER BY start_date DESC");
while($r = $q->fetch()){
    $st = $r['status'] == 'Active' ? 'st-active' : ($r['status'] == 'Closed' ? 'st-active' : 'st-pending');
    $loan_html .= "<tr><td>{$r['loan_id']}</td><td>{$r['account_no']}</td><td>\${$r['loan_amount']}</td><td>{$r['loan_type']}</td><td><span class='status $st'>{$r['status']}</span></td>
    <td>
        <form method='POST' style='display:inline'><input type='hidden' name='action' value='update_loan'><input type='hidden' name='loan_id' value='{$r['loan_id']}'><input type='hidden' name='status' value='Active'><button class='btn-xs st-active'>Approve</button></form>
        <form method='POST' style='display:inline'><input type='hidden' name='action' value='update_loan'><input type='hidden' name='loan_id' value='{$r['loan_id']}'><input type='hidden' name='status' value='Closed'><button class='btn-xs btn-danger'>Reject</button></form>
    </td></tr>";
}

// Support Tickets
$tick_html = "";
$q = $pdo->query("SELECT * FROM support_tickets ORDER BY created_at DESC LIMIT 10");
while($r = $q->fetch()){
    $tick_html .= "<tr><td>{$r['ticket_id']}</td><td>{$r['account_no']}</td><td>{$r['subject']}</td><td>{$r['status']}</td><td>{$r['created_at']}</td></tr>";
}

// Cards
$card_html = "";
$q = $pdo->query("SELECT * FROM cards ORDER BY card_id DESC LIMIT 10");
while($r = $q->fetch()){
    $card_html .= "<tr><td>{$r['account_no']}</td><td>{$r['card_number']}</td><td>{$r['card_type']}</td><td>{$r['expiry_date']}</td><td>{$r['status']}</td></tr>";
}

// Staff
$stf_html = "";
$q = $pdo->query("SELECT * FROM staff");
while($r = $q->fetch()){
    $stf_html .= "<tr><td>{$r['staff_id']}</td><td>{$r['name']}</td><td>{$r['role']}</td><td>{$r['status']}</td></tr>";
}

// Logs
$log_html = "";
$q = $pdo->query("SELECT * FROM logs ORDER BY date DESC LIMIT 20");
while($r = $q->fetch()){
    $c = $r['type']=='SUCCESS'?'var(--success)':'var(--accent)';
    $log_html .= "<div style='padding:5px; border-bottom:1px solid #333; font-size:12px;'><span style='color:$c'>[{$r['type']}]</span> {$r['message']} <span style='float:right; color:#666'>{$r['date']}</span></div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prime Bank | Admin Master</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #020408; --glass: rgba(20, 30, 50, 0.6); --border: 1px solid rgba(255, 255, 255, 0.08);
            --accent: #00f2ff; --secondary: #0066ff; --text: #fff; --muted: #94a3b8;
            --success: #00d26a; --danger: #f87171; --radius: 12px;
        }
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Plus Jakarta Sans',sans-serif; }
        body { background: var(--bg); color: var(--text); height: 100vh; display: flex; overflow: hidden; }
        
        .sidebar { width: 260px; background: var(--glass); border-right: var(--border); padding: 20px; display: flex; flex-direction: column; }
        .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; background: radial-gradient(circle at 50% -20%, #0d1b2a, #020408); }
        .header { height: 70px; border-bottom: var(--border); display: flex; align-items: center; justify-content: space-between; padding: 0 30px; background: var(--glass); }
        .content { flex: 1; overflow-y: auto; padding: 30px; }

        .brand { font-size: 20px; font-weight: 800; color: white; margin-bottom: 40px; display: flex; align-items: center; gap: 10px; }
        .brand i { color: var(--accent); }
        
        .nav-item { padding: 12px 15px; margin: 5px 0; border-radius: 8px; cursor: pointer; color: var(--muted); transition: 0.3s; display: flex; align-items: center; gap: 10px; font-weight: 500; }
        .nav-item:hover, .nav-item.active { background: rgba(0, 242, 255, 0.1); color: var(--accent); border: 1px solid rgba(0, 242, 255, 0.2); }
        
        .card { background: rgba(255,255,255,0.02); border: var(--border); border-radius: var(--radius); padding: 20px; margin-bottom: 20px; backdrop-filter: blur(10px); }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        input, select { background: rgba(0,0,0,0.3); border: var(--border); color: white; padding: 10px; width: 100%; border-radius: 6px; margin-bottom: 10px; outline: none; }
        input:focus { border-color: var(--accent); }
        label { color: var(--muted); font-size: 12px; margin-bottom: 5px; display: block; }
        
        .btn { width: 100%; padding: 12px; background: linear-gradient(135deg, var(--secondary), var(--accent)); border: none; border-radius: 6px; color: #000; font-weight: 700; cursor: pointer; margin-top: 10px; }
        .btn:hover { filter: brightness(1.1); }
        .btn-xs { padding: 4px 10px; font-size: 11px; width: auto; margin: 0 2px; border-radius: 4px; border: none; cursor: pointer; color: white; }
        .btn-danger { background: var(--danger); }
        
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { text-align: left; color: var(--muted); padding: 12px; border-bottom: var(--border); text-transform: uppercase; font-size: 11px; }
        td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        
        .status { padding: 4px 8px; border-radius: 4px; font-size: 11px; }
        .st-active { background: rgba(0,210,106,0.1); color: var(--success); }
        .st-pending { background: rgba(245,158,11,0.1); color: var(--warning); }
        
        .stat-num { font-size: 28px; font-weight: 700; color: white; margin-top: 5px; }
        .stat-label { font-size: 12px; color: var(--muted); }
        .hidden { display: none; }
        .alert { padding: 15px; background: rgba(0,210,106,0.1); border: 1px solid var(--success); border-radius: 8px; margin-bottom: 20px; color: white; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="brand"><i class="fas fa-university"></i> PRIME BANK</div>
        <div class="nav-item active" onclick="router('dashboard')"><i class="fas fa-th-large"></i> Dashboard</div>
        <div class="nav-item" onclick="router('customers')"><i class="fas fa-users"></i> Customers</div>
        <div class="nav-item" onclick="router('transactions')"><i class="fas fa-exchange-alt"></i> Banking</div>
        <div class="nav-item" onclick="router('loans')"><i class="fas fa-hand-holding-usd"></i> Loans & Credit</div>
        <div class="nav-item" onclick="router('services')"><i class="fas fa-credit-card"></i> Cards & Services</div>
        <div class="nav-item" onclick="router('support')"><i class="fas fa-headset"></i> Support Desk</div>
        <div class="nav-item" onclick="router('staff')"><i class="fas fa-id-badge"></i> Staff</div>
        <div class="nav-item" onclick="router('logs')"><i class="fas fa-shield-alt"></i> Logs</div>
<div class="nav-item">
    <a href="..\admin\adminlogout.php" class="logout-link">
        <i class="fas fa-shield-alt"></i> Logout
    </a>
</div>

    </div>

    <div class="main">
        <header class="header">
            <h3 id="pageTitle">Dashboard</h3>
            <div style="display:flex; align-items:center; gap:10px;">
                <span style="font-size:12px; color:var(--muted)">Admin</span>
                <img src="https://ui-avatars.com/api/?name=Admin&background=00f2ff&color=000" style="width:35px; border-radius:50%;">
            </div>
        </header>

        <div class="content" id="app">
            <?php if($msg): ?><div class="alert"><?php echo $msg; ?></div><?php endif; ?>
        </div>
    </div>

<script>
    // --- VIEWS ---
    const views = {
        dashboard: `
            <div class="grid-4">
                <div class="card"><div class="stat-label">Total Deposits</div><div class="stat-num" style="color:var(--success)">$<?php echo number_format($tot_dep); ?></div></div>
                <div class="card"><div class="stat-label">Total Withdrawals</div><div class="stat-num" style="color:var(--danger)">$<?php echo number_format($tot_wd); ?></div></div>
                <div class="card"><div class="stat-label">Total Loans Active</div><div class="stat-num">$<?php echo number_format($tot_loan); ?></div></div>
                <div class="card"><div class="stat-label">Total Customers</div><div class="stat-num"><?php echo $tot_cust; ?></div></div>
            </div>
            <div class="grid-2">
                <div class="card"><h3>Recent Deposits</h3><table><thead><tr><th>ID</th><th>Acc</th><th>Amt</th><th>Method</th></tr></thead><tbody><?php echo $dep_html; ?></tbody></table></div>
                <div class="card"><h3>Recent Withdrawals</h3><table><thead><tr><th>ID</th><th>Acc</th><th>Amt</th><th>Method</th></tr></thead><tbody><?php echo $wd_html; ?></tbody></table></div>
            </div>
        `,
        customers: `
            <div class="grid-2">
                <div class="card">
                    <h3>Add Customer</h3>
                    <form method="POST">
                        <input type="hidden" name="action" value="add_customer">
                        <div class="grid-2"><div><label>First Name</label><input type="text" name="name" required></div><div><label>Last Name</label><input type="text" name="lname" required></div></div>
                        <label>Email</label><input type="email" name="email" required>
                        <label>Mobile</label><input type="text" name="mobile" required>
                        <label>Address</label><input type="text" name="address" required>
                        <div class="grid-2"><div><label>City</label><input type="text" name="city"></div><div><label>State</label><input type="text" name="state"></div></div>
                        <div class="grid-2"><div><label>Zip</label><input type="text" name="zip"></div><div><label>Password</label><input type="password" name="password" required></div></div>
                        <button class="btn">Create Account</button>
                    </form>
                </div>
                <div class="card">
                    <h3>Customer Database</h3>
                    <div style="max-height:600px; overflow-y:auto"><table><thead><tr><th>Acc No</th><th>Name</th><th>Balance</th><th>Mobile</th><th>KYC</th><th>Action</th></tr></thead><tbody><?php echo $cust_html; ?></tbody></table></div>
                </div>
            </div>
        `,
        transactions: `
            <div class="grid-2">
                <div class="card" style="border-top:2px solid var(--success)">
                    <h3>Deposit Funds</h3>
                    <form method="POST">
                        <input type="hidden" name="action" value="deposit">
                        <label>Account Number</label><input type="text" name="account_id" required>
                        <label>Amount</label><input type="number" name="amount" required>
                        <label>Method</label>
                        <select name="deposit_method" onchange="toggleCheque(this)">
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                        </select>
                        <div id="cheque_fields" class="hidden" style="background:rgba(255,255,255,0.05); padding:10px; margin-bottom:10px; border-radius:6px;">
                            <div class="grid-2">
                                <div><label>Cheque No</label><input type="text" name="cheque_no"></div>
                                <div><label>Cheque Name</label><input type="text" name="cheque_name"></div>
                            </div>
                            <label>Bank Name</label><input type="text" name="bank_name">
                            <label>Deposit To Acc</label><input type="text" name="cheque_deposit_ac_no">
                        </div>
                        <label>Reference</label><input type="text" name="deposit_reference" required>
                        <button class="btn">Process Deposit</button>
                    </form>
                </div>
                <div class="card" style="border-top:2px solid var(--danger)">
                    <h3>Withdraw Funds</h3>
                    <form method="POST">
                        <input type="hidden" name="action" value="withdraw">
                        <label>Account Number</label><input type="text" name="account_id" required>
                        <label>Amount</label><input type="number" name="amount" required>
                        <label>Holder Name</label><input type="text" name="name" required>
                        <label>Method</label><input type="text" name="method" value="Cash" required>
                        <label>Reference</label><input type="text" name="ref" required>
                        <button class="btn btn-danger">Process Withdrawal</button>
                    </form>
                </div>
            </div>
            <div class="card">
                <h3>Online Transfers History</h3>
                <div style="max-height:300px; overflow-y:auto"><table><thead><tr><th>Txn ID</th><th>Flow</th><th>Amount</th><th>Date</th></tr></thead><tbody><?php echo $tf_html; ?></tbody></table></div>
            </div>
        `,
        loans: `
            <div class="card">
                <h3>Loan Management</h3>
                <p style="color:var(--muted); font-size:13px; margin-bottom:15px;">Review pending loan applications. Approving sets status to Active.</p>
                <table><thead><tr><th>Loan ID</th><th>Acc No</th><th>Amount</th><th>Type</th><th>Status</th><th>Actions</th></tr></thead><tbody><?php echo $loan_html; ?></tbody></table>
            </div>
        `,
        services: `
            <div class="grid-2">
                <div class="card">
                    <h3>Issued Cards (Debit/Credit)</h3>
                    <table><thead><tr><th>Account</th><th>Card No</th><th>Type</th><th>Expiry</th><th>Status</th></tr></thead><tbody><?php echo $card_html; ?></tbody></table>
                </div>
                <div class="card">
                    <h3>Beneficiaries & FDs</h3>
                    <p style="color:var(--muted)">View Beneficiaries and Fixed Deposits data directly from database.</p>
                    <button class="btn btn-xs st-active">View All Data</button>
                </div>
            </div>
        `,
        support: `
            <div class="card">
                <h3>Support Tickets</h3>
                <table><thead><tr><th>ID</th><th>Account</th><th>Subject</th><th>Status</th><th>Date</th></tr></thead><tbody><?php echo $tick_html; ?></tbody></table>
            </div>
        `,
        staff: `
            <div class="grid-2">
                <div class="card">
                    <h3>Add Staff</h3>
                    <form method="POST">
                        <input type="hidden" name="action" value="add_staff">
                        <label>Full Name</label><input type="text" name="name" required>
                        <label>Role</label><select name="role"><option>Manager</option><option>Teller</option><option>Support</option></select>
                        <button class="btn">Add Staff Member</button>
                    </form>
                </div>
                <div class="card"><h3>Staff Directory</h3><table><thead><tr><th>ID</th><th>Name</th><th>Role</th><th>Status</th></tr></thead><tbody><?php echo $stf_html; ?></tbody></table></div>
            </div>
        `,
        logs: `
            <div class="card">
                <h3>System Logs & Audit</h3>
                <div style="max-height:500px; overflow-y:auto; font-family:monospace;"><?php echo $log_html; ?></div>
            </div>
        `
    };

    function router(view) {
        document.getElementById('app').innerHTML = views[view];
        document.getElementById('pageTitle').innerText = view.charAt(0).toUpperCase() + view.slice(1);
        document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
        event.currentTarget.classList.add('active');
    }

    function toggleCheque(sel) {
        document.getElementById('cheque_fields').style.display = sel.value === 'cheque' ? 'block' : 'none';
    }

    // Init
    document.getElementById('app').innerHTML = views['dashboard'];
</script>
</body>
</html>
<?php

include('..\admin\adminnavbar.php');

?>

<div class="main_content">
<h4 class="para"> Dahboard</h4> <br> <hr>

</div>
