<?php
$submissionMessage = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = htmlspecialchars($_POST['fullName']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $grantType = htmlspecialchars($_POST['grantType']);
    $amountRequested = htmlspecialchars($_POST['amountRequested']);
    $reason = htmlspecialchars($_POST['reason']);

    // Basic validation / processing simulation
    if (!empty($fullName) && !empty($email) && !empty($grantType)) {
        // In a live environment, you would save this data to a database or send an email.
        $submissionMessage = "<div class='alert success'>Thank you, <strong>$fullName</strong>! Your application for the <strong>$grantType</strong> has been successfully submitted. We will contact you via email shortly.</div>";
    } else {
        $submissionMessage = "<div class='alert error'>Please fill in all required fields.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Grant Application Portal</title>
    <style>
        :root {
            --primary-color: #1e3a8a;
            --secondary-color: #3b82f6;
            --accent-color: #10b981;
            --bg-color: #f3f4f6;
            --text-color: #1f2937;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        header {
            text-align: center;
            margin-bottom: 30px;
        }
        header h1 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        header p {
            color: #4b5563;
        }
        .notice-box {
            background-color: #eff6ff;
            border-left: 4px solid var(--secondary-color);
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 4px;
            font-size: 0.95rem;
        }
        .notice-box ul {
            margin-left: 20px;
            margin-top: 5px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
        }
        textarea {
            resize: vertical;
            height: 100px;
        }
        button {
            background-color: var(--primary-color);
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            transition: background 0.3s ease;
        }
        button:hover {
            background-color: #1e40af;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
        }
        .alert.success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .alert.error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.85rem;
            color: #6b7280;
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>PT Grant Application Portal</h1>
        <p>Apply for home assistance, mortgage/loan relief, and cash grants.</p>
    </header>

    <!-- Important Notes Box -->
    <div class="notice-box">
        <strong>Important Application Terms:</strong>
        <ul>
            <li><strong>Application is 100% Free:</strong> There are no charges to submit or process your application.</li>
            <li><strong>Delivery Fee:</strong> A delivery fee applies only if you select or require doorstep delivery for approved physical aid packages.</li>
            <li><strong>Cash Grants:</strong> Cash awards can be refunded or disbursed based on verification criteria.</li>
        </ul>
    </div>

    <?php echo $submissionMessage; ?>

    <!-- Application Form -->
    <form action="index.php" method="POST">
        <div class="form-group">
            <label for="fullName">Full Name *</label>
            <input type="text" id="fullName" name="fullName" required placeholder="Enter your full name">
        </div>

        <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" required placeholder="name@example.com">
        </div>

        <div class="form-group">
            <label for="phone">Phone Number *</label>
            <input type="tel" id="phone" name="phone" required placeholder="(123) 456-7890">
        </div>

        <div class="form-group">
            <label for="grantType">Select Grant Type *</label>
            <select id="grantType" name="grantType" required>
                <option value="">-- Choose an Option --</option>
                <option value="Home Assistance Grant">Home Assistance Grant</option>
                <option value="Mortgage Payoff Help">Mortgage Payoff Help</option>
                <option value="Other Loan Assistance">Other Loan Assistance</option>
                <option value="Cash Grant Help">Cash Grant Help</option>
            </select>
        </div>

        <div class="form-group">
            <label for="amountRequested">Estimated Amount Needed ($) *</label>
            <input type="text" id="amountRequested" name="amountRequested" required placeholder="e.g., $5,000">
        </div>

        <div class="form-group">
            <label for="reason">Reason for Application & Financial Background *</label>
            <textarea id="reason" name="reason" required placeholder="Briefly describe why you need this grant..."></textarea>
        </div>

        <button type="submit">Submit Free Application</button>
    </form>

    <footer>
        &copy; 2026 PT Grant Program. All rights reserved.
    </footer>
</div>

</body>
</html>