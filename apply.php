<?php
include 'save.php';

$assistance_type = isset($_GET['type']) ? $_GET['type'] : 'General Assistance';
$submitted = false;
$app_number = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $middle_name = trim($_POST['middle_name']);
    $address = trim($_POST['address']);
    $is_business = isset($_POST['is_business']) ? 1 : 0;
    $business_name = $is_business ? trim($_POST['business_name']) : '';
    $current_employer = trim($_POST['current_employer']);
    $bankruptcy = trim($_POST['bankruptcy']);
    $rent_amount = floatval($_POST['rent_amount']);
    
    $app_number = 'PTG-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
    
    $new_app = [
        'app_number' => $app_number,
        'assistance_type' => $assistance_type,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'middle_name' => $middle_name,
        'address' => $address,
        'is_business' => $is_business,
        'business_name' => $business_name,
        'current_employer' => $current_employer,
        'bankruptcy' => $bankruptcy,
        'rent_amount' => $rent_amount,
        'status' => 'Pending Review',
        'document_path' => '',
        'payment_method' => '',
        'payment_receipt' => '',
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    save_new_application($new_app);
    $submitted = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apply - PT GRANT</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 0; color: #333; }
        header { background: #004080; color: white; padding: 20px; text-align: center; }
        .container { width: 600px; background: white; margin: 40px auto; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { color: #004080; margin-top: 0; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input[type="text"], input[type="number"], select, textarea { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        textarea { resize: vertical; height: 80px; }
        .btn { background: #004080; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin-top: 20px; font-size: 16px; width: 100%; }
        .btn:hover { background: #003366; }
        .success-box { text-align: center; padding: 20px; }
        .success-box h3 { color: #28a745; font-size: 24px; }
        .app-num { background: #e9ecef; padding: 15px; font-size: 20px; font-weight: bold; border-radius: 4px; margin: 20px 0; color: #004080; }
        .back-link { display: block; margin-top: 15px; text-decoration: none; color: #004080; }
    </style>
    <script>
        function toggleFields(isBusiness) {
            document.getElementById('business_field').style.display = isBusiness ? 'block' : 'none';
        }
    </script>
</head>
<body>

<header>
    <h1>PT GRANT Application Portal</h1>
</header>

<div class="container">
    <?php if ($submitted): ?>
        <div class="success-box">
            <h3>Thank You!</h3>
            <p>Your application has been successfully submitted for <strong><?= htmlspecialchars($assistance_type) ?></strong>.</p>
            <p>Your Generated Application Number:</p>
            <div class="app-num"><?= $app_number ?></div>
            <p>Please save this number and your first name to check your application status, upload documents, and process payments.</p>
            <a href="index.php" class="btn" style="text-decoration:none; display:inline-block; line-height:normal; width:auto; padding: 10px 20px;">Go to Home & Check Status</a>
        </div>
    <?php else: ?>
        <h2>Application Form: <?= htmlspecialchars($assistance_type) ?></h2>
        <form method="POST">
            <label>First Name:</label>
            <input type="text" name="first_name" required>

            <label>Middle Name:</label>
            <input type="text" name="middle_name">

            <label>Last Name:</label>
            <input type="text" name="last_name" required>

            <label>Current Address:</label>
            <textarea name="address" required></textarea>

            <label style="margin-top:20px;">
                <input type="checkbox" name="is_business" value="1" onchange="toggleFields(this.checked)"> Is this application for a business?
            </label>

            <div id="business_field" style="display:none;">
                <label>Business Name:</label>
                <input type="text" name="business_name">
            </div>

            <label>Current Employer:</label>
            <input type="text" name="current_employer" required>

            <label>Have you ever filed for Bankruptcy?</label>
            <select name="bankruptcy" required>
                <option value="No">No</option>
                <option value="Yes">Yes</option>
            </select>

            <label>Current Rent Amount ($):</label>
            <input type="number" step="0.01" name="rent_amount" required>

            <button type="submit" class="btn">Submit Application</button>
        </form>
        <a href="index.php" class="back-link">&larr; Back to Home</a>
    <?php endif; ?>
</div>

</body>
</html>
