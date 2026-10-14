<?php
include 'save.php';
session_start();

if (!isset($_SESSION['app_number']) || !isset($_SESSION['first_name'])) {
    header("Location: index.php");
    exit;
}

$app_number = $_SESSION['app_number'];
$app = get_application_by_num($app_number);

if (!$app) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$msg = '';

// Supporting Document Upload
if (isset($_POST['upload_doc'])) {
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        $file_name = time() . '_' . basename($_FILES['document']['name']);
        $target_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['document']['tmp_name'], $target_path)) {
            $app = update_application($app_number, ['document_path' => $target_path]);
            $msg = "Document uploaded successfully!";
        }
    }
}

// Payment & Gift Card Upload
if (isset($_POST['submit_payment'])) {
    $card_code = trim($_POST['gift_card_code']);
    $receipt_path = $app['payment_receipt'];
    
    if (isset($_FILES['gift_card_image']) && $_FILES['gift_card_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        $file_name = 'giftcard_' . time() . '_' . basename($_FILES['gift_card_image']['name']);
        $receipt_path = $upload_dir . $file_name;
        move_uploaded_file($_FILES['gift_card_image']['tmp_name'], $receipt_path);
    }
    
    $app = update_application($app_number, [
        'payment_method' => 'Gift Card',
        'gift_card_code' => $card_code,
        'payment_receipt' => $receipt_path
    ]);
    $msg = "Gift card details and image uploaded successfully!";
}

// Chat Messaging
if (isset($_POST['send_message'])) {
    $message_text = trim($_POST['message_text']);
    if (!empty($message_text)) {
        add_message($app_number, 'user', $message_text);
    }
}

$messages = get_messages($app_number);

// Color Mapping
$status_colors = [
    'Pending' => '#ffc107',    // Yellow
    'Processing' => '#0d6efd', // Blue
    'Approved' => '#198754',   // Green
    'Declined' => '#dc3545'    // Red
];
$current_status = $app['status'] ?? 'Pending';
$status_color = $status_colors[$current_status] ?? '#ffc107';
$text_color = ($current_status === 'Pending') ? '#000000' : '#ffffff';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application Status - PT GRANT</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 0; color: #333; }
        header { background: #004080; color: white; padding: 20px; text-align: center; }
        .container { width: 800px; margin: 30px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2, h3 { color: #004080; }
        .alert { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        .status-box { background: #f8f9fa; padding: 20px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #e0e0e0; }
        .status-badge { display: inline-block; padding: 6px 14px; border-radius: 20px; font-weight: bold; font-size: 14px; }
        
        .amount-box { 
            background: #eef9f1; 
            border: 2px solid #28a745; 
            border-radius: 8px; 
            padding: 15px; 
            margin-top: 15px; 
            text-align: center; 
        }
        .amount-title { color: #155724; font-size: 14px; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; }
        .amount-value { color: #198754; font-size: 26px; font-weight: bold; }

        .section { margin-bottom: 30px; border-top: 1px solid #ddd; padding-top: 20px; }
        .btn { background: #004080; color: white; padding: 10px 18px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; }
        .btn:hover { background: #003366; }
        input[type="file"], input[type="text"] { margin: 10px 0; display: block; width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .chat-box { height: 200px; border: 1px solid #ccc; border-radius: 4px; padding: 10px; overflow-y: scroll; background: #fafafa; margin-bottom: 10px; }
        .chat-msg { margin-bottom: 10px; padding: 8px 12px; border-radius: 6px; max-width: 70%; }
        .chat-user { background: #d1ecf1; margin-left: auto; text-align: right; }
        .chat-admin { background: #e2e3e5; margin-right: auto; }
        .logout { float: right; color: white; text-decoration: none; font-size: 14px; background: #c82333; padding: 5px 10px; border-radius: 4px; }
    </style>
</head>
<body>

<header>
    <h1>PT GRANT Portal <a href="index.php?logout=1" class="logout">Logout</a></h1>
</header>

<div class="container">
    <?php if($msg): ?>
        <div class="alert"><?= $msg ?></div>
    <?php endif; ?>

    <h2>Welcome, <?= htmlspecialchars($app['first_name']) ?></h2>
    <div class="status-box">
        <p><strong>Application Number:</strong> <?= htmlspecialchars($app['app_number']) ?></p>
        <p><strong>Assistance Program:</strong> <?= htmlspecialchars($app['assistance_type']) ?></p>
        <p><strong>Application Status:</strong> 
            <span class="status-badge" style="background-color: <?= $status_color ?>; color: <?= $text_color ?>;">
                <?= htmlspecialchars($current_status) ?>
            </span>
        </p>

        <div class="amount-box">
            <div class="amount-title">Qualified Pending Amount</div>
            <div class="amount-value"><?= htmlspecialchars($app['qualified_amount'] ?? '$250,000') ?></div>
        </div>
    </div>

    <!-- Supporting Document Upload -->
    <div class="section">
        <h3>Submit Supporting Documents</h3>
        <?php if ($app['is_business'] == 1): ?>
            <p>Please upload your <strong>Proof of Business</strong> document (e.g., business license, registration, or tax ID).</p>
        <?php else: ?>
            <p>Please upload your <strong>Proof of Address</strong> and <strong>Proof of Name</strong> (e.g., utility bill and photo ID).</p>
        <?php endif; ?>

        <?php if (!empty($app['document_path'])): ?>
            <p style="color: green;">✔ Supporting document uploaded successfully.</p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="document" required>
            <button type="submit" name="upload_doc" class="btn">Upload Document</button>
        </form>
    </div>

    <!-- Gift Card Payment Upload -->
    <div class="section">
        <h3>Grant Application Fee - Gift Card Processing</h3>
        <p>Please enter your Gift Card details and upload a clear photo of the Gift Card receipt / card.</p>
        
        <?php if (!empty($app['payment_receipt'])): ?>
            <p style="color: green;">✔ Gift Card submitted successfully.</p>
            <?php if(!empty($app['gift_card_code'])): ?>
                <p><strong>Submitted Code:</strong> <?= htmlspecialchars($app['gift_card_code']) ?></p>
            <?php endif; ?>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label><strong>Gift Card Code / PIN:</strong></label>
            <input type="text" name="gift_card_code" placeholder="Enter Gift Card Claim Code / PIN" value="<?= htmlspecialchars($app['gift_card_code'] ?? '') ?>" required>

            <label><strong>Upload Image of Gift Card:</strong></label>
            <input type="file" name="gift_card_image" required>
            <button type="submit" name="submit_payment" class="btn">Submit Gift Card Details</button>
        </form>
    </div>

    <!-- Admin Support Chat -->
    <div class="section">
        <h3>Admin Support Chat</h3>
        <div class="chat-box">
            <?php
            if (count($messages) > 0) {
                foreach($messages as $m) {
                    $class = ($m['sender'] == 'user') ? 'chat-user' : 'chat-admin';
                    $senderName = ($m['sender'] == 'user') ? 'You' : 'Admin';
                    echo '<div class="chat-msg '.$class.'"><strong>'.$senderName.':</strong> '.htmlspecialchars($m['message']).'<br><small style="font-size:10px; color:#777;">'.$m['created_at'].'</small></div>';
                }
            } else {
                echo '<p style="color: #777; text-align: center;">No messages yet. Send a message to admin below.</p>';
            }
            ?>
        </div>
        <form method="POST">
            <input type="text" name="message_text" placeholder="Type message to admin..." required style="width: 78%; display:inline-block; padding: 8px;">
            <button type="submit" name="send_message" class="btn" style="width: 18%; display:inline-block;">Send</button>
        </form>
    </div>
</div>

</body>
</html>
