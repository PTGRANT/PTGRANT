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

// Update status
if (isset($_POST['update_status']) && isset($_SESSION['admin_logged'])) {
    $app_num = $_POST['app_number'];
    $new_status = $_POST['new_status'];
    update_application($app_num, ['status' => $new_status]);
}

// Reply to user message
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
        .container { width: 95%; max-width: 1300px; margin: 30px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { color: #343a40; }
        .login-box { width: 350px; margin: 100px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; }
        input[type="password"], input[type="text"], select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { background: #343a40; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 14px; }
        .btn:hover { background: #23272b; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #343a40; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .logout { float: right; color: white; text-decoration: none; background: #c82333; padding: 5px 10px; border-radius: 4px; font-size: 14px; }
        .error { color: red; margin-bottom: 10px; }
        .badge { background: #17a2b8; color: white; padding: 3px 8px; border-radius: 3px; font-size: 12px; }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['admin_logged'])): ?>
    <div class="login-box">
        <h2>Admin Login</h2>
        <?php if($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
        <form method="POST">
            <input type="password" name="password" placeholder="Enter Admin Password" required>
            <button type="submit" name="login" class="btn" style="width: 100%;">Login</button>
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
            <th>Details / Business</th>
            <th>Status</th>
            <th>Documents</th>
            <th>Payment Info</th>
            <th>Chat / Reply</th>
            <th>Submitted At</th>
        </tr>
        <?php
        $apps = get_applications();
        if (empty($apps)): ?>
            <tr><td colspan="9" style="text-align:center;">No applications submitted yet.</td></tr>
        <?php else:
            foreach(array_reverse($apps) as $row):
        ?>
        <tr>
            <td><strong><?= htmlspecialchars($row['app_number']) ?></strong></td>
            <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
            <td><?= htmlspecialchars($row['assistance_type']) ?></td>
            <td>
                <?php if($row['is_business']): ?>
                    <span class="badge">Business</span><br><strong><?= htmlspecialchars($row['business_name']) ?></strong>
                <?php else: ?>
                    <span class="badge" style="background:#6c757d;">Personal</span>
                <?php endif; ?>
                <br><small>Employer: <?= htmlspecialchars($row['current_employer']) ?></small>
                <br><small>Bankruptcy: <?= htmlspecialchars($row['bankruptcy']) ?> | Rent: $<?= htmlspecialchars($row['rent_amount']) ?></small>
            </td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="app_number" value="<?= $row['app_number'] ?>">
                    <select name="new_status" onchange="this.form.submit()" style="padding: 5px;">
                        <option value="Pending Review" <?= $row['status']=='Pending Review'?'selected':'' ?>>Pending Review</option>
                        <option value="Under Evaluation" <?= $row['status']=='Under Evaluation'?'selected':'' ?>>Under Evaluation</option>
                        <option value="Approved" <?= $row['status']=='Approved'?'selected':'' ?>>Approved</option>
                        <option value="Declined" <?= $row['status']=='Declined'?'selected':'' ?>>Declined</option>
                    </select>
                    <input type="hidden" name="update_status" value="1">
                </form>
            </td>
            <td>
                <?php if(!empty($row['document_path'])): ?>
                    <a href="<?= htmlspecialchars($row['document_path']) ?>" target="_blank" class="btn" style="padding:4px 8px; font-size:12px;">View Doc</a>
                <?php else: ?>
                    <span style="color:red; font-size:12px;">No Doc</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if(!empty($row['payment_method'])): ?>
                    <strong><?= htmlspecialchars($row['payment_method']) ?></strong><br>
                    <?php if(!empty($row['payment_receipt'])): ?>
                        <a href="<?= htmlspecialchars($row['payment_receipt']) ?>" target="_blank" class="btn" style="padding:4px 8px; font-size:12px; margin-top:3px; display:inline-block;">View Receipt</a>
                    <?php endif; ?>
                <?php else: ?>
                    <span style="color:red; font-size:12px;">Unpaid</span>
                <?php endif; ?>
            </td>
            <td>
                <div style="max-height: 100px; overflow-y: scroll; font-size: 12px; border: 1px solid #ddd; padding: 5px; margin-bottom: 5px; background: #fff;">
                    <?php
                    $m_list = get_messages($row['app_number']);
                    foreach($m_list as $m) {
                        echo '<div><b>'.ucfirst($m['sender']).':</b> '.htmlspecialchars($m['message']).'</div>';
                    }
                    ?>
                </div>
                <form method="POST">
                    <input type="hidden" name="app_number" value="<?= $row['app_number'] ?>">
                    <input type="text" name="reply_text" placeholder="Type reply..." required style="padding:4px; font-size:12px; width:70%;">
                    <button type="submit" name="admin_reply" class="btn" style="padding:4px 8px; font-size:12px;">Send</button>
                </form>
            </td>
            <td>
                <small style="color: #666;"><?= $row['created_at'] ?></small>
            </td>
        </tr>
        <?php endforeach; endif; ?>
    </table>
</div>

<?php endif; ?>
</body>
</html>
