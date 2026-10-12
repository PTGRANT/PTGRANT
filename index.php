<?php
// Handle feedback messages passed via URL from save.php
$status = $_GET['status'] ?? '';
$message = '';
$messageType = '';

if ($status === 'success') {
    $refId = htmlspecialchars($_GET['ref'] ?? '00000');
    $message = "Application submitted successfully! Your Application Reference ID is <strong>#GRANT-{$refId}</strong>.";
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
    <!-- Google Fonts & FontAwesome for icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --accent: #10b981;
            --bg-gradient: linear-gradient(135deg, #f0f4ff 0%, #fdf2f8 100%);
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            font-family: 'Inter', sans-serif; 
        }

        body { 
            background: var(--bg-gradient); 
            color: var(--text-main); 
            min-height: 100vh;
            padding: 40px 20px; 
            line-height: 1.6; 
        }

        .portal-wrapper { 
            max-width: 850px; 
            margin: 0 auto; 
        }

        .main-card { 
            background: var(--card-bg); 
            padding: 45px; 
            border-radius: 16px; 
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.08); 
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        header {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: #eff6ff;
            color: var(--primary);
            font-size: 24px;
            border-radius: 50%;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.1);
        }

        header h1 { 
            color: var(--text-main); 
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px; 
        }

        header p { 
            color: var(--text-muted); 
            font-size: 1.05rem;
        }

        /* Feature Cards Grid */
        .grant-highlights {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .highlight-item {
            background: #f8fafc;
            border: 1px solid var(--border-color);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .highlight-item:hover {
            border-color: var(--primary);
            background: #eff6ff;
            transform: translateY(-2px);
        }

        .highlight-item i {
            color: var(--primary);
            font-size: 22px;
            margin-bottom: 8px;
        }

        .highlight-item span {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-main);
        }

        /* Notice Box */
        .notice-box { 
            background: #f0fdf4; 
            border-left: 5px solid var(--accent); 
            padding: 20px; 
            border-radius: 8px; 
            margin-bottom: 30px; 
            font-size: 0.95rem; 
            color: #166534;
        }

        .notice-box strong {
            display: block;
            margin-bottom: 8px;
            font-size: 1rem;
        }

        .notice-box ul { 
            margin-left: 20px; 
        }

        .notice-box li {
            margin-bottom: 4px;
        }

        /* Form Controls */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group { 
            margin-bottom: 22px; 
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        label { 
            display: block; 
            font-weight: 600; 
            font-size: 0.9rem;
            margin-bottom: 8px; 
            color: #334155;
        }

        input[type="text"], 
        input[type="email"], 
        input[type="tel"], 
        input[type="number"], 
        select, 
        textarea {
            width: 100%; 
            padding: 12px 16px; 
            border: 1px solid var(--border-color); 
            border-radius: 8px; 
            font-size: 1rem; 
            background: #f8fafc;
            color: var(--text-main);
            transition: all 0.2s;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        textarea { 
            height: 120px; 
            resize: vertical; 
        }

        .checkbox-container { 
            display: flex; 
            align-items: flex-start; 
            gap: 12px; 
            margin: 25px 0; 
            font-size: 0.9rem; 
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .checkbox-container input { 
            margin-top: 3px; 
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        button[type="submit"] { 
            width: 100%; 
            padding: 15px; 
            background: var(--primary); 
            color: white; 
            border: none; 
            border-radius: 8px; 
            font-size: 1.05rem; 
            font-weight: 600; 
            cursor: pointer; 
            transition: background 0.2s, transform 0.1s; 
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        button[type="submit"]:hover { 
            background: var(--primary-dark); 
        }

        button[type="submit"]:active {
            transform: scale(0.99);
        }

        /* Alert Banners */
        .alert { 
            padding: 16px; 
            border-radius: 8px; 
            margin-bottom: 25px; 
            text-align: center; 
            font-size: 0.95rem; 
        }
        .alert.success { 
            background: #dcfce7; 
            color: #166534; 
            border: 1px solid #bbf7d0; 
        }
        .alert.error { 
            background: #fee2e2; 
            color: #991b1b; 
            border: 1px solid #fecaca; 
        }

        footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        @media (max-width: 650px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-group.full-width { grid-column: span 1; }
            .main-card { padding: 25px; }
        }
    </style>
</head>
<body>

<div class="portal-wrapper">
    <div class="main-card">
        <header>
            <div class="logo-badge">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
            <h1>PT Grant Application Portal</h1>
            <p>Secure financial backing for homeownership, loan relief, and cash grants.</p>
        </header>

        <!-- Quick Select Category Cards -->
        <div class="grant-highlights">
            <div class="highlight-item" onclick="selectCategory('Home Assistance Grant')">
                <i class="fa-solid fa-house-chimney"></i>
                <span>Home Grant</span>
            </div>
            <div class="highlight-item" onclick="selectCategory('Mortgage Payoff Grant')">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>Mortgage Help</span>
            </div>
            <div class="highlight-item" onclick="selectCategory('Loan Payoff Help')">
                <i class="fa-solid fa-credit-card"></i>
                <span>Other Loans</span>
            </div>
            <div class="highlight-item" onclick="selectCategory('Cash Grant Help')">
                <i class="fa-solid fa-wallet"></i>
                <span>Cash Help</span>
            </div>
        </div>

        <!-- Important Disclosure Box -->
        <div class="notice-box">
            <strong><i class="fa-solid fa-circle-info"></i> Important Program Disclosures:</strong>
            <ul>
                <li><strong>100% Free Application:</strong> There are no registration or processing fees to submit your application.</li>
                <li><strong>Doorstep Delivery Fee:</strong> Standard delivery fees apply exclusively if approved physical aid requires delivery to your doorstep.</li>
                <li><strong>Cash Refund Policy:</strong> Disbursed cash grants are eligible for refunds under verified administration terms.</li>
            </ul>
        </div>

        <!-- System Notification Banner -->
        <?php if (!empty($message)): ?>
            <div class="alert <?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Application Form linking to save.php -->
        <form method="POST" action="save.php">
            <div class="form-grid">
                <div class="form-group">
                    <label for="fullName">Full Name *</label>
                    <input type="text" id="fullName" name="fullName" required placeholder="e.g. Jane Doe">
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required placeholder="jane@example.com">
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" required placeholder="(555) 000-0000">
                </div>

                <div class="form-group">
                    <label for="grantType">Grant Category *</label>
                    <select id="grantType" name="grantType" required>
                        <option value="">-- Choose Category --</option>
                        <option value="Home Assistance Grant">Home Assistance Grant</option>
                        <option value="Mortgage Payoff Grant">Mortgage Payoff Grant</option>
                        <option value="Loan Payoff Help">Loan Payoff Help</option>
                        <option value="Cash Grant Help">Cash Grant Help</option>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="amountRequested">Estimated Amount Requested ($) *</label>
                    <input type="number" id="amountRequested" name="amountRequested" step="0.01" min="50" required placeholder="5000.00">
                </div>

                <div class="form-group full-width">
                    <label for="reason">Reason for Application & Financial Background *</label>
                    <textarea id="reason" name="reason" required placeholder="Briefly describe your financial situation and how this grant will support you..."></textarea>
                </div>
            </div>

            <div class="checkbox-container">
                <input type="checkbox" id="deliveryFeeAccepted" name="deliveryFeeAccepted" value="1" required>
                <label for="deliveryFeeAccepted" style="cursor: pointer; margin-bottom: 0;">
                    I verify that submitting this application is free, and I understand that doorstep delivery fees apply if physical materials are shipped. I also acknowledge cash grant refund terms.
                </label>
            </div>

            <button type="submit">
                <i class="fa-solid fa-paper-plane"></i> Submit Free Application
            </button>
        </form>
    </div>

    <footer>
        &copy; <?php echo date('Y'); ?> PT Grant Portal. All rights reserved.
    </footer>
</div>

<script>
    // Quick selector utility to map highlight cards to dropdown
    function selectCategory(categoryName) {
        const selectElement = document.getElementById('grantType');
        selectElement.value = categoryName;
        selectElement.focus();
        selectElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
</script>

</body>
</html>
