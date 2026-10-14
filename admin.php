<?php
session_start();

$dataFile = 'applications.json';

// Simple hardcoded login credentials (Change for production)
$adminUser = 'admin';
$adminPass = 'password123';

$loginError = '';

// Handle Login
if (isset($_POST['login'])) {
    if ($_POST['username'] === $adminUser && $_POST['password'] === $adminPass) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $loginError = 'Invalid username or password.';
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged_in']);
    header('Location: admin.php');
    exit;
}

// Fetch Applications from JSON file if logged in
$applications = [];
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    if (file_exists($dataFile)) {
        $jsonContent = file_get_contents($dataFile);
        $applications = json_decode($jsonContent, true) ?? [];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Grant Admin Portal</title>
    <style>
        :root {
            --primary-color: #1e3a8a;
            --bg-color: #f3f4f6;
            --text-color: #1f2937;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: var(--bg-color); color: var(--text-color); padding: 20px; line-height: 1.5; }
        .wrapper { max-width: 1100px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h1, h2 { color: var(--primary-color); margin-bottom: 20px; }
        .login-box { max-width: 400px; margin: 50px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: 600; }
        input { width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 5px; }
        button { background: var(--primary-color); color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%; }
        button:hover { background: #1e40af; }
        .error { color: #dc2626; font-size: 0.9rem; margin-bottom: 10px; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid #e5e7eb; padding-bottom: 15px; }
        .logout-btn { background: #dc2626; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 0.9rem; }
        .logout-btn:hover { background: #b91c1c; }
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 0.9rem; }
        th, td { padding: 12px; border: 1px solid #e5e7eb; text-align: left; }
        th { background: #f8fafc; color: var(--primary-color); }
        tr:nth-child(even) { background: #f9fafb; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; background: #e5e7eb; }
        .back-link { display: inline-block; margin-bottom: 15px; color: var(--primary-color); text-decoration: none; font-size: 0.9rem; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="<?php echo (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) ? '' : 'wrapper'; ?>">

<?php if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true): ?>
    <!-- Login Form -->
    <div class="login-box">
        <h2>Admin Login</h2>
        <?php if (!empty($loginError)): ?>
            <div class="error"><?php echo $loginError; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login">Log In</button>
        </form>
        <p style="margin-top: 15px; text-align: center;"><a href="index.php" class="back-link">&larr; Back to Grant Application Form</a></p>
    </div>

<?php else: ?>
    <!-- Admin Dashboard -->
    <div class="top-bar">
        <div>
            <h1>Grant Entries Admin Portal</h1>
            <a href="index.php" class="back-link">&larr; View Public Form</a>
        </div>
        <a href="admin.php?logout=true" class="logout-btn">Log Out</a>
    </div>

    <p>Total Submissions: <strong><?php echo count($applications); ?></strong> (Saved in JSON)</p>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Ref ID</th>
                    <th>Applicant Name</th>
                    <th>Contact Info</th>
                    <th>Grant Category</th>
                    <th>Amount</th>
                    <th>Delivery Fee Agreed</th>
                    <th>Reason / Background</th>
                    <th>Date Submitted</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($applications)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: #6b7280;">No applications found yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($applications as $app): ?>
                        <tr>
                            <td><strong>#GRANT-<?php echo sprintf("%05d", $app['id']); ?></strong></td>
                            <td><?php echo htmlspecialchars($app['full_name']); ?></td>
                            <td>
                                <?php echo htmlspecialchars($app['email']); ?><br>
                                <small><?php echo htmlspecialchars($app['phone']); ?></small>
                            </td>
                            <td><span class="badge"><?php echo htmlspecialchars($app['grant_type']); ?></span></td>
                            <td>$<?php echo number_format($app['amount_requested'], 2); ?></td>
                            <td style="text-align: center;"><?php echo $app['delivery_fee_accepted'] ? '✅ Yes' : '❌ No'; ?></td>
                            <td><?php echo nl2br(htmlspecialchars($app['reason'])); ?></td>
                            <td><small><?php echo $app['created_at']; ?></small></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>

</div>

</body>
</html>
