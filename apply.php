<?php
include 'save.php';

$assistance_type = isset($_GET['type']) ? $_GET['type'] : 'General Assistance';
$submitted = false;
$app_number = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $middle_name = trim($_POST['middle_name']);
    
    // Address Breakdown
    $address1 = trim($_POST['address1']);
    $address2 = trim($_POST['address2']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $zip = trim($_POST['zip']);
    
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
        'address1' => $address1,
        'address2' => $address2,
        'city' => $city,
        'state' => $state,
        'zip' => $zip,
        'is_business' => $is_business,
        'business_name' => $business_name,
        'current_employer' => $current_employer,
        'bankruptcy' => $bankruptcy,
        'rent_amount' => $rent_amount,
        'status' => 'Pending',
        'qualified_amount' => '$250,000',
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
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #0a192f 0%, #112240 50%, #020c1b 100%); 
            min-height: 100vh;
            margin: 0; 
            padding: 0; 
            color: #e6f1ff; 
        }
        header { 
            background: rgba(10, 25, 47, 0.85); 
            backdrop-filter: blur(10px);
            color: #ffffff; 
            padding: 25px 20px; 
            text-align: center; 
            border-bottom: 1px solid #233554;
        }
        header h1 {
            margin: 0;
            font-size: 26px;
            letter-spacing: 1px;
            color: #64ffda;
        }
        .container { 
            width: 100%;
            max-width: 650px; 
            background: #112240; 
            margin: 40px auto; 
            padding: 35px 40px; 
            border-radius: 12px; 
            box-shadow: 0 10px 30px -10px rgba(2, 12, 27, 0.7); 
            border: 1px solid #233554;
            box-sizing: border-box;
        }
        h2 { 
            color: #64ffda; 
            margin-top: 0; 
            border-bottom: 2px solid #233554;
            padding-bottom: 12px;
            font-size: 22px;
        }
        label { 
            display: block; 
            margin-top: 18px; 
            font-weight: 600; 
            font-size: 14px;
            color: #ccd6f6;
        }
        input[type="text"], input[type="number"], select { 
            width: 100%; 
            padding: 12px 14px; 
            margin-top: 6px; 
            background: #0a192f;
            border: 1px solid #233554; 
            border-radius: 6px; 
            box-sizing: border-box; 
            color: #e6f1ff;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus, input[type="number"]:focus, select:focus {
            outline: none;
            border-color: #64ffda;
            box-shadow: 0 0 8px rgba(100, 255, 218, 0.2);
        }
        select option {
            background: #0a192f;
            color: #e6f1ff;
        }
        .row { display: flex; gap: 15px; }
        .row > div { flex: 1; }
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            margin-top: 20px;
            color: #64ffda;
            font-size: 15px;
        }
        .checkbox-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #64ffda;
            cursor: pointer;
        }
        .btn { 
            background: #64ffda; 
            color: #0a192f; 
            padding: 14px 20px; 
            border: none; 
            border-radius: 6px; 
            cursor: pointer; 
            margin-top: 25px; 
            font-size: 16px; 
            font-weight: bold;
            width: 100%; 
            transition: all 0.3s ease;
        }
        .btn:hover { 
            background: #52d1b2; 
            box-shadow: 0 5px 15px rgba(100, 255, 218, 0.3);
            transform: translateY(-2px);
        }
        .success-box { text-align: center; padding: 20px 10px; }
        .success-box h3 { color: #64ffda; font-size: 26px; margin-top: 0; }
        .app-num { 
            background: #0a192f; 
            padding: 18px; 
            font-size: 22px; 
            font-weight: bold; 
            border-radius: 8px; 
            margin: 20px 0; 
            color: #64ffda; 
            border: 1px dashed #64ffda;
            letter-spacing: 2px;
        }
        .back-link { 
            display: inline-block; 
            margin-top: 20px; 
            text-decoration: none; 
            color: #8892b0; 
            font-size: 14px;
            transition: color 0.2s;
        }
        .back-link:hover { color: #64ffda; }
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
            <p style="color: #8892b0;">Your Generated Application Number:</p>
            <div class="app-num"><?= $app_number ?></div>
            <p style="color: #8892b0;">Please save this number and your first name to check your application status, upload documents, and process payments.</p>
            <a href="index.php" class="btn" style="text-decoration:none; display:inline-block; width:auto; padding: 12px 25px;">Go to Home & Check Status</a>
        </div>
    <?php else: ?>
        <h2>Application Form: <?= htmlspecialchars($assistance_type) ?></h2>
        <form method="POST">
            <div class="row">
                <div>
                    <label>First Name:</label>
                    <input type="text" name="first_name" required>
                </div>
                <div>
                    <label>Middle Name:</label>
                    <input type="text" name="middle_name">
                </div>
                <div>
                    <label>Last Name:</label>
                    <input type="text" name="last_name" required>
                </div>
            </div>

            <label>Address Line 1:</label>
            <input type="text" name="address1" placeholder="Street Address" required>

            <label>Address Line 2:</label>
            <input type="text" name="address2" placeholder="Apt, Suite, Unit, etc. (Optional)">

            <div class="row">
                <div>
                    <label>City:</label>
                    <input type="text" name="city" required>
                </div>
                <div>
                    <label>State:</label>
                    <input type="text" name="state" required>
                </div>
                <div>
                    <label>Zip Code:</label>
                    <input type="text" name="zip" required>
                </div>
            </div>

            <label class="checkbox-label">
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
