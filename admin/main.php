<?php
// =================================================================================
//  PRIME BANK - INTEGRATED SINGLE FILE SYSTEM
// =================================================================================
session_start();
$host = 'localhost'; $dbname = 'primebank'; $username = 'root'; $password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) { die("DB Connection Failed: " . $e->getMessage()); }

// --- HELPERS ---
function sanitize($data) { return htmlspecialchars(stripslashes(trim($data))); }
function genID() { return time() . mt_rand(100, 999); }

$msg = ""; 

// =================================================================================
//  1. BACKEND CONTROLLER
// =================================================================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- ADD CUSTOMER ---
    if (isset($_POST['action']) && $_POST['action'] == 'add_customer') {
        $acc = mt_rand(100000000000, 999999999999);
        $pwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO login (name, lname, email, mobile_no, address, city, state, zip, password, account_no) VALUES (?,?,?,?,?,?,?,?,?,?)";
        
        try {
            if($pdo->prepare($sql)->execute([$_POST['name'], $_POST['lname'], $_POST['email'], $_POST['mobile'], $_POST['address'], $_POST['city'], $_POST['state'], $_POST['zip'], $pwd, $acc])) {
                $pdo->prepare("INSERT INTO balance (account_no, total_balance) VALUES (?, 0)")->execute([$acc]);
                $msg = "Success: Customer Created! Account #: $acc";
            }
        } catch (Exception $e) { $msg = "Error: " . $e->getMessage(); }
    }

    // --- HANDLE DEPOSIT (WITH CHEQUE LOGIC) ---
    if (isset($_POST['action']) && $_POST['action'] == 'deposit') {
        $acc_id = sanitize($_POST['account_id']);
        $depositor = sanitize($_POST['depositor_name']);
        $amount = (float)sanitize($_POST['amount']);
        $method = sanitize($_POST['deposit_method']);
        $ref = sanitize($_POST['deposit_reference']);
        $dep_id = genID();

        // Cheque details (Nullable)
        $chq_no = $chq_name = $bank = $chq_acc = null;
        if ($method == 'cheque') {
            $chq_no = sanitize($_POST['cheque_no']);
            $chq_name = sanitize($_POST['cheque_name']);
            $bank = sanitize($_POST['bank_name']);
            $chq_acc = sanitize($_POST['cheque_deposit_ac_no']);
        }

        // Verify Account Exists
        $stmt = $pdo->prepare("SELECT * FROM login WHERE account_no = ?");
        $stmt->execute([$acc_id]);
        
        if ($stmt->rowCount() == 0) {
            $msg = "Error: Account ID not found.";
        } else {
            // Insert into Deposit Table
            $sql = "INSERT INTO deposit (deposit_id, account_id, amount, deposit_method, deposit_reference, deposit_date, cheque_no, cheque_name, bank_name, cheque_deposit_ac_no) VALUES (?,?,?,?,?,NOW(),?,?,?,?)";
            $pdo->prepare($sql)->execute([$dep_id, $acc_id, $amount, $method, $ref, $chq_no, $chq_name, $bank, $chq_acc]);

            // Update Balance
            $pdo->prepare("UPDATE balance SET total_balance = total_balance + ? WHERE account_no = ?")->execute([$amount, $acc_id]);
            $msg = "Success: Deposit of $$amount processed via $method.";
        }
    }

    // --- HANDLE WITHDRAWAL ---
    if (isset($_POST['action']) && $_POST['action'] == 'withdraw') {
        $acc_id = sanitize($_POST['accountID']);
        $name = sanitize($_POST['name']);
        $amount = (float)sanitize($_POST['amount']);
        $method = sanitize($_POST['withdrawalMethod']);
        $ref = sanitize($_POST['withdrawalReference']);
        $wd_id = genID();

        // Check Balance
        $stmt = $pdo->prepare("SELECT total_balance FROM balance WHERE account_no = ?");
        $stmt->execute([$acc_id]);
        $res = $stmt->fetch();

        if (!$res) {
            $msg = "Error: Account not found.";
        } elseif ($res['total_balance'] < $amount) {
            $msg = "Error: Insufficient Funds.";
        } else {
            // Insert Withdrawal
            $sql = "INSERT INTO withdrawals (withdrawal_id, account_id, amount, withdrawal_method, withdrawal_reference, withdrawal_date, name) VALUES (?,?,?,?,?,NOW(),?)";
            $pdo->prepare($sql)->execute([$wd_id, $acc_id, $amount, $method, $ref, $name]);

            // Deduct Balance
            $pdo->prepare("UPDATE balance SET total_balance = total_balance - ? WHERE account_no = ?")->execute([$amount, $acc_id]);
            $msg = "Success: Withdrawal of $$amount processed.";
        }
    }
}

// =================================================================================
//  2. DATA FETCHING (PREPARE HTML FOR JS)
// =================================================================================

// Fetch Deposit History (Credit List)
$credit_list_html = "";
$q = $pdo->query("SELECT * FROM deposit ORDER BY deposit_date DESC LIMIT 20");
while($r = $q->fetch()) {
    $credit_list_html .= "<tr>
        <td>{$r['deposit_id']}</td>
        <td>{$r['account_id']}</td>
        <td style='color:var(--success)'>+\${$r['amount']}</td>
        <td>{$r['deposit_method']}</td>
        <td>{$r['deposit_date']}</td>
        <td>{$r['bank_name']}</td>
        <td><button class='btn btn-danger' style='padding:4px 8px; font-size:10px;'>Delete</button></td>
    </tr>";
}

// Fetch Withdrawal History (Debit List)
$debit_list_html = "";
$q = $pdo->query("SELECT * FROM withdrawals ORDER BY withdrawal_date DESC LIMIT 20");
while($r = $q->fetch()) {
    $debit_list_html .= "<tr>
        <td>{$r['withdrawal_id']}</td>
        <td>{$r['account_id']}</td>
        <td>{$r['name']}</td>
        <td style='color:var(--danger)'>-\${$r['amount']}</td>
        <td>{$r['withdrawal_date']}</td>
        <td>{$r['withdrawal_method']}</td>
        <td><button class='btn btn-danger' style='padding:4px 8px; font-size:10px;'>Delete</button></td>
    </tr>";
}

// Fetch Customers
$cust_html = "";
$q = $pdo->query("SELECT * FROM login LIMIT 20");
while($r = $q->fetch()) {
    $cust_html .= "<tr><td>{$r['account_no']}</td><td>{$r['name']} {$r['lname']}</td><td>{$r['mobile_no']}</td><td>{$r['city']}</td></tr>";
}

// Analytics Totals
$total_dep = $pdo->query("SELECT SUM(amount) FROM deposit")->fetchColumn() ?: 0;
$total_wd = $pdo->query("SELECT SUM(amount) FROM withdrawals")->fetchColumn() ?: 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prime Bank | Admin Console</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- MODERN UI VARIABLES --- */
        :root {
            --bg-deep: #020408; --glass-bg: rgba(20, 30, 50, 0.6); 
            --glass-border: 1px solid rgba(255, 255, 255, 0.08);
            --accent-primary: #00f2ff; --accent-secondary: #0066ff;
            --success: #00d26a; --warning: #f59e0b; --danger: #f87171;
        }
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Plus Jakarta Sans',sans-serif; }
        body { background: var(--bg-deep); color: #fff; height: 100vh; display: flex; overflow: hidden; }
        #canvas-bg { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; opacity: 0.6; }
        
        .app-container { display: flex; width: 100%; height: 100%; padding: 20px; gap: 20px; }
        .sidebar { width: 260px; background: var(--glass-bg); border: var(--glass-border); border-radius: 16px; padding: 20px; display: flex; flex-direction: column; }
        .nav-item { padding: 12px; margin: 5px 0; cursor: pointer; color: #94a3b8; border-radius: 8px; display: flex; gap: 10px; align-items: center; transition: 0.3s; }
        .nav-item:hover, .nav-item.active { background: rgba(0, 242, 255, 0.1); color: var(--accent-primary); border: 1px solid rgba(0, 242, 255, 0.2); }
        
        .main { flex: 1; background: var(--glass-bg); border: var(--glass-border); border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; }
        .header { height: 70px; border-bottom: var(--glass-border); display: flex; align-items: center; justify-content: space-between; padding: 0 30px; }
        .content { padding: 30px; overflow-y: auto; flex: 1; }
        
        /* CARDS & FORMS */
        .card { background: rgba(255,255,255,0.02); border: var(--glass-border); padding: 20px; border-radius: 12px; margin-bottom: 20px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        
        input, select { background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); padding: 10px; width: 100%; color: white; border-radius: 6px; margin-bottom: 10px; outline: none; }
        input:focus, select:focus { border-color: var(--accent-primary); }
        label { font-size: 12px; color: #94a3b8; margin-bottom: 4px; display: block; }
        
        .btn { padding: 10px 20px; background: linear-gradient(135deg, var(--accent-secondary), var(--accent-primary)); color: #000; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; width: 100%; margin-top: 10px; }
        .btn-danger { background: rgba(248, 113, 113, 0.2); color: var(--danger); border: 1px solid var(--danger); }
        
        /* TABLES */
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { text-align: left; color: #94a3b8; padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        
        .alert { padding: 15px; background: rgba(0,242,255,0.1); border: 1px solid var(--accent-primary); border-radius: 8px; margin-bottom: 20px; }
        .hidden { display: none; }
    </style>
</head>
<body>
    <canvas id="canvas-bg"></canvas>
    
    <div class="app-container">
        <nav class="sidebar">
            <h2 style="color:white; margin-bottom:30px;"><i class="fas fa-cube"></i> PRIME</h2>
            <div id="navMenu"></div>
        </nav>
        
        <main class="main">
            <header class="header">
                <h2 id="pageTitle">Overview</h2>
                <div style="display:flex; gap:10px; align-items:center;">
                    <img src="https://ui-avatars.com/api/?name=Admin" style="width:35px; border-radius:50%;">
                </div>
            </header>
            
            <div class="content" id="app">
                <?php if($msg): ?><div class="alert"><?php echo $msg; ?></div><?php endif; ?>
            </div>
        </main>
    </div>

<script>
    // --- VIEWS ---
    const views = {
        dashboard: `
            <div class="grid-2">
                <div class="card"><h3>Total Deposits</h3><h1 style="color:var(--success)">$<?php echo number_format($total_dep); ?></h1></div>
                <div class="card"><h3>Total Withdrawals</h3><h1 style="color:var(--danger)">$<?php echo number_format($total_wd); ?></h1></div>
            </div>
        `,
        customers: `
            <div class="card">
                <h3>Add New Customer</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="add_customer">
                    <div class="grid-2">
                        <div><label>First Name</label><input type="text" name="name" required></div>
                        <div><label>Last Name</label><input type="text" name="lname" required></div>
                    </div>
                    <label>Email</label><input type="email" name="email" required>
                    <label>Mobile</label><input type="text" name="mobile" required>
                    <label>Address</label><input type="text" name="address" required>
                    <div class="grid-2">
                        <div><label>City</label><input type="text" name="city"></div>
                        <div><label>State</label><input type="text" name="state"></div>
                    </div>
                    <div class="grid-2">
                        <div><label>Zip</label><input type="text" name="zip"></div>
                        <div><label>Password</label><input type="password" name="password" required></div>
                    </div>
                    <button class="btn">Create Customer</button>
                </form>
            </div>
            <div class="card">
                <h3>Customer List</h3>
                <div style="max-height:300px; overflow-y:auto">
                    <table><thead><tr><th>Acc No</th><th>Name</th><th>Mobile</th><th>City</th></tr></thead><tbody><?php echo $cust_html; ?></tbody></table>
                </div>
            </div>
        `,
        // THIS IS THE MAIN INTEGRATION YOU ASKED FOR
        banking: `
            <div class="grid-2">
                <div class="card" style="border-top:3px solid var(--success)">
                    <h3 style="color:var(--success)"><i class="fas fa-arrow-down"></i> Deposit Transaction</h3><br>
                    <form method="POST">
                        <input type="hidden" name="action" value="deposit">
                        <label>Account No</label><input type="text" name="account_id" required>
                        <label>Depositor Name</label><input type="text" name="depositor_name" required>
                        <label>Amount</label><input type="number" name="amount" required>
                        <label>Deposit Method</label>
                        <select name="deposit_method" id="deposit_method" onchange="toggleCheque()">
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                        </select>
                        
                        <div id="cheque_fields" class="hidden" style="background:rgba(255,255,255,0.05); padding:10px; border-radius:6px; margin-bottom:10px;">
                            <label>Cheque No</label><input type="text" name="cheque_no">
                            <label>Cheque Name</label><input type="text" name="cheque_name">
                            <label>Bank Name</label><input type="text" name="bank_name">
                            <label>Cheque Deposit Acc No</label><input type="text" name="cheque_deposit_ac_no">
                        </div>

                        <label>Reference</label><input type="text" name="deposit_reference" required>
                        <button class="btn">Submit Deposit</button>
                    </form>
                </div>

                <div class="card" style="border-top:3px solid var(--danger)">
                    <h3 style="color:var(--danger)"><i class="fas fa-arrow-up"></i> Withdrawal Transaction</h3><br>
                    <form method="POST">
                        <input type="hidden" name="action" value="withdraw">
                        <label>Account No</label><input type="text" name="accountID" required>
                        <label>Holder Name</label><input type="text" name="name" required>
                        <label>Amount</label><input type="number" name="amount" required>
                        <label>Method</label><input type="text" name="withdrawalMethod" value="Cash" required>
                        <label>Reference</label><input type="text" name="withdrawalReference" required>
                        <button class="btn" style="background:var(--danger); border:none; color:white;">Submit Withdrawal</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <h3>Total Credit List (Deposits)</h3>
                <div style="max-height:300px; overflow-y:auto">
                    <table>
                        <thead><tr><th>ID</th><th>Account</th><th>Amount</th><th>Method</th><th>Date</th><th>Bank</th><th>Action</th></tr></thead>
                        <tbody><?php echo $credit_list_html; ?></tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <h3>Total Debit List (Withdrawals)</h3>
                <div style="max-height:300px; overflow-y:auto">
                    <table>
                        <thead><tr><th>ID</th><th>Account</th><th>Name</th><th>Amount</th><th>Date</th><th>Method</th><th>Action</th></tr></thead>
                        <tbody><?php echo $debit_list_html; ?></tbody>
                    </table>
                </div>
            </div>
        `
    };

    // --- NAVIGATION ---
    const navItems = [
        {id:'dashboard', label:'Dashboard', icon:'fa-chart-line'},
        {id:'customers', label:'Customers', icon:'fa-users'},
        {id:'banking', label:'Banking Ops', icon:'fa-money-bill-transfer'},
    ];

    const nav = document.getElementById('navMenu');
    navItems.forEach(i => {
        const d = document.createElement('div');
        d.className = 'nav-item';
        d.innerHTML = `<i class="fas ${i.icon}"></i> ${i.label}`;
        d.onclick = () => loadView(i.id);
        if(i.id === 'dashboard') d.classList.add('active');
        nav.appendChild(d);
    });

    function loadView(id) {
        document.querySelectorAll('.nav-item').forEach(e => e.classList.remove('active'));
        const idx = navItems.findIndex(x => x.id === id);
        document.querySelectorAll('.nav-item')[idx].classList.add('active');
        document.getElementById('pageTitle').innerText = navItems[idx].label;
        document.getElementById('app').innerHTML = views[id];
        
        // Re-attach event listeners for dynamic content
        if(id === 'banking') {
            // Ensure the toggle logic works immediately if 'cheque' is pre-selected (unlikely but good practice)
            toggleCheque();
        }
    }

    // --- CHEQUE TOGGLE LOGIC (Matches your snippet) ---
    window.toggleCheque = function() {
        const val = document.getElementById('deposit_method').value;
        const fields = document.getElementById('cheque_fields');
        if(val === 'cheque') fields.classList.remove('hidden');
        else fields.classList.add('hidden');
    }

    // --- 3D BACKGROUND ---
    const scn=new THREE.Scene(),cam=new THREE.PerspectiveCamera(75,window.innerWidth/window.innerHeight,0.1,1000),ren=new THREE.WebGLRenderer({canvas:document.getElementById('canvas-bg'),alpha:true});
    ren.setSize(window.innerWidth,window.innerHeight);
    const pts=new THREE.Points(new THREE.BufferGeometry().setAttribute('position',new THREE.BufferAttribute(new Float32Array(600).map(()=>(Math.random()-0.5)*15),3)),new THREE.PointsMaterial({size:0.02,color:0x00f2ff}));
    scn.add(pts); cam.position.z=3;
    function ani(){requestAnimationFrame(ani);pts.rotation.y+=0.002;ren.render(scn,cam);}ani();

    // Init
    loadView('dashboard');
</script>
</body>
</html>