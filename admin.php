<?php
include 'save.php';
session_start();

$admin_pass = 'Admin123';
$error = '';

if (isset($_POST['login'])) {
    if ($_POST['password'] === $admin_pass) {
        $_SESSION['admin_logged'] = true;
    } else {
        $error = "Incorrect Admin Password.";
    }
}

if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged']);
    header("Location: admin.php");
    exit;
}

// Update Status and Qualified Amount
if (isset($_POST['update_app_details']) && isset($_SESSION['admin_logged'])) {
    $app_num = $_POST['app_number'];
    $new_status = $_POST['new_status'];
    $new_amount = trim($_POST['qualified_amount']);
    
    update_application($app_num, [
        'status' => $new_status,
        'qualified_amount' => $new_amount
    ]);
}

// Admin Chat Reply
if (isset($_POST['admin_reply']) && isset($_SESSION['admin_logged'])) {
    $app_number = $_POST['app_number'];
    $reply_text = trim($_POST['reply_text']);
    if (!empty($reply_text)) {
        add_message($app_number, 'admin', $reply_text);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Portal - PT GRANT</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 0; color: #333; }
        header { background: #343a40; color: white; padding: 20px; text-align: center; }
        .container { width: 95%; max-width: 1400px; margin: 30px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { color: #343a40; }
        .login-box { width: 350px; margin: 100px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; }
        input[type="password"], input[type="text"], select { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { background: #343a40; color: white; padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 13px; }
        .btn:hover { background: #23272b; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 13px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: top; }
        th { background: #343a40; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .logout { float: right; color: white; text-decoration: none; background: #c82333; padding: 5px 10px; border-radius: 4px; font-size: 14px; }
        .error { color: red; margin-bottom: 10px; }

        /* Color Code Options */
        .status-select-Pending { background-color: #ffc107; color: #000; font-weight: bold; }
        .status-select-Processing { background-color: #0d6efd; color: #fff; font-weight: bold; }
        .status-select-Approved { background-color: #198754; color: #fff; font-weight: bold; }
        .status-select-Declined { background-color: #dc3545; color: #fff; font-weight: bold; }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['admin_logged'])): ?>
    <div class="login-box">
        <h2>Admin Login</h2>
        <?php if($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
        <form method="POST">
            <input type="password" name="password" placeholder="Enter Admin Password" required>
            <button type="submit" name="login" class="btn" style="width: 100%; margin-top: 10px;">Login</button>
        </form>
    </div>
<?php else: ?>

<header>
    <h1>PT GRANT Admin Portal <a href="admin.php?logout=1" class="logout">Logout</a></h1>
</header>

<div class="container">
    <h2>All Grant Applications</h2>
    <table>
        <tr>
            <th>App #</th>
            <th>Applicant Name</th>
            <th>Assistance Type</th>
            <th>Address</th>
            <th>Details / Business</th>
            <th>Status & Qualified Amount</th>
            <th>Docs</th>
            <th>Gift Card Info</th>
            <th>Chat / Reply</th>
        </tr>
        <?php
        $apps = get_applications();
        if (empty($apps)): ?>
            <tr><td colspan="9" style="text-align:center;">No applications submitted yet.</td></tr>
        <?php else:
            foreach(array_reverse($apps) as $row):
                $current_st = $row['status'] ?? 'Pending';
        ?>
        <tr>
            <td><strong><?= htmlspecialchars($row['app_number']) ?></strong></td>
            <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']) ?></td>
            <td><?= htmlspecialchars($row['assistance_type']) ?></td>
            <td>
                <?= htmlspecialchars($row['address1']) ?><br>
                <?= !empty($row['address2']) ? htmlspecialchars($row['address2']) . '<br>' : '' ?>
                <?= htmlspecialchars($row['city'] . ', ' . $row['state'] . ' ' . $row['zip']) ?>
            </td>
            <td>
                <?php if($row['is_business']): ?>
                    <span style="background:#17a2b8; color:#fff; padding:2px 5px; border-radius:3px; font-size:10px;">Business</span><br><strong><?= htmlspecialchars($row['business_name']) ?></strong><br>
                <?php endif; ?>
                <small>Employer: <?= htmlspecialchars($row['current_employer']) ?></small><br>
                <small>Bankruptcy: <?= htmlspecialchars($row['bankruptcy']) ?> | Rent: $<?= htmlspecialchars($row['rent_amount']) ?></small>
            </td>
            <td>
                <form method="POST">
                    <input type="hidden" name="app_number" value="<?= $row['app_number'] ?>">
                    
                    <label style="font-size:10px; font-weight:bold;">Status:</label>
                    <select name="new_status" class="status-select-<?= $current_st ?>">
                        <option value="Pending" class="status-select-Pending" <?= $current_st=='Pending'?'selected':'' ?>>Pending (Yellow)</option>
                        <option value="Processing" class="status-select-Processing" <?= $current_st=='Processing'?'selected':'' ?>>Processing (Blue)</option>
                        <option value="Approved" class="status-select-Approved" <?= $current_st=='Approved'?'selected':'' ?>>Approved (Green)</option>
                        <option value="Declined" class="status-select-Declined" <?= $current_st=='Declined'?'selected':'' ?>>Declined (Red)</option>
                    </select>

                    <label style="font-size:10px; font-weight:bold; margin-top:5px; display:block;">Qualified Amount:</label>
                    <input type="text" name="qualified_amount" value="<?= htmlspecialchars($row['qualified_amount'] ?? '$250,000') ?>" style="padding:4px; font-size:12px;">

                    <button type="submit" name="update_app_details" class="btn" style="padding:4px 8px; font-size:11px; margin-top:5px; width:100%;">Update</button>
                </form>
            </td>
            <td>
                <?php if(!empty($row['document_path'])): ?>
                    <a href="<?= htmlspecialchars($row['document_path']) ?>" target="_blank" class="btn" style="padding:4px 6px; font-size:11px;">View Doc</a>
                <?php else: ?>
                    <span style="color:red; font-size:11px;">No Doc</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if(!empty($row['gift_card_code']) || !empty($row['payment_receipt'])): ?>
                    <strong style="color:green;">Gift Card Submitted</strong><br>
                    <?php if(!empty($row['gift_card_code'])): ?>
                        <small><b>Code:</b> <?= htmlspecialchars($row['gift_card_code']) ?></small><br>
                    <?php endif; ?>
                    <?php if(!empty($row['payment_receipt'])): ?>
                        <a href="<?= htmlspecialchars($row['payment_receipt']) ?>" target="_blank" class="btn" style="padding:3px 6px; font-size:10px; margin-top:3px; display:inline-block;">View Image</a>
                    <?php endif; ?>
                <?php else: ?>
                    <span style="color:red; font-size:11px;">Not Submitted</span>
                <?php endif; ?>
            </td>
            <td>
                <div style="max-height: 80px; overflow-y: scroll; font-size: 11px; border: 1px solid #ddd; padding: 4px; margin-bottom: 4px; background: #fff;">
                    <?php
                    $m_list = get_messages($row['app_number']);
                    foreach($m_list as $m) {
                        echo '<div><b>'.ucfirst($m['sender']).':</b> '.htmlspecialchars($m['message']).'</div>';
                    }
                    ?>
                </div>
                <form method="POST">
                    <input type="hidden" name="app_number" value="<?= $row['app_number'] ?>">
                    <input type="text" name="reply_text" placeholder="Type reply..." required style="padding:3px; font-size:11px; width:65%;">
                    <button type="submit" name="admin_reply" class="btn" style="padding:3px 5px; font-size:11px;">Send</button>
                </form>
            </td>
        </tr>
        <?php endforeach; endif; ?>
    </table>
</div>

<?php endif; ?>
</body>
</html>
