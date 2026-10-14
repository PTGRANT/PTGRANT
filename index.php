<?php
include 'save.php';

$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_status'])) {
    $first_name = trim($_POST['login_first_name']);
    $app_number = trim($_POST['login_app_number']);
    
    $app = get_application_by_num($app_number);
    
    if ($app && strcasecmp($app['first_name'], $first_name) === 0) {
        session_start();
        $_SESSION['app_number'] = $app['app_number'];
        $_SESSION['first_name'] = $app['first_name'];
        header("Location: status.php");
        exit;
    } else {
        $login_error = "Invalid Application Number or First Name.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PT GRANT - Home</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 0; color: #333; }
        header { background: #004080; color: white; padding: 20px; text-align: center; }
        .container { width: 85%; max-width: 1100px; margin: 30px auto; display: flex; gap: 30px; }
        .main-content { flex: 2; }
        .sidebar { flex: 1; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); height: fit-content; }
        h2 { color: #004080; border-bottom: 2px solid #004080; padding-bottom: 8px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; text-decoration: none; color: #333; transition: 0.2s; display: flex; flex-direction: column; justify-content: space-between; }
        .card:hover { transform: translateY(-3px); box-shadow: 0 4px 10px rgba(0,0,0,0.15); background: #f0f4f8; }
        .card h3 { color: #004080; font-size: 16px; margin-bottom: 10px; }
        .btn { background: #004080; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; font-size: 14px; }
        .btn:hover { background: #003366; }
        input[type="text"] { width: 100%; padding: 10px; margin: 8px 0 15px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .error { color: red; font-size: 13px; margin-bottom: 10px; }
    </style>
</head>
<body>

<header>
    <h1>PT GRANT Assistance Portal</h1>
    <p>Empowering individuals and businesses with dedicated financial aid and support programs.</p>
</header>

<div class="container">
    <div class="main-content">
        <h2>Select Assistance Program</h2>
        <div class="grid">
            <a href="apply.php?type=Personal Loan Assistance" class="card">
                <h3>Personal Loan Assistance</h3>
                <span class="btn">Apply Now</span>
            </a>
            <a href="apply.php?type=Mortgage Loan Assistance" class="card">
                <h3>Mortgage Loan Assistance</h3>
                <span class="btn">Apply Now</span>
            </a>
            <a href="apply.php?type=Credit Card Payoff Assistance" class="card">
                <h3>Credit Card Payoff Assistance</h3>
                <span class="btn">Apply Now</span>
            </a>
            <a href="apply.php?type=Small Business Assistance" class="card">
                <h3>Small Business Assistance</h3>
                <span class="btn">Apply Now</span>
            </a>
            <a href="apply.php?type=Home Grant Assistance" class="card">
                <h3>Home Grant Assistance</h3>
                <span class="btn">Apply Now</span>
            </a>
            <a href="apply.php?type=Doorstep Delivery Assistance" class="card">
                <h3>Doorstep Delivery Assistance</h3>
                <span class="btn">Apply Now</span>
            </a>
        </div>
    </div>

    <div class="sidebar">
        <h2>Check Application Status</h2>
        <?php if($login_error): ?>
            <div class="error"><?= $login_error ?></div>
        <?php endif; ?>
        <form method="POST">
            <label>First Name:</label>
            <input type="text" name="login_first_name" required>
            
            <label>Application Number:</label>
            <input type="text" name="login_app_number" required placeholder="PTG-XXXXXX">
            
            <button type="submit" name="check_status" class="btn" style="width: 100%;">Check Status</button>
        </form>
    </div>
</div>

</body>
</html>
