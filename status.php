<?php
include 'config.php';
session_start();

if (!isset($_SESSION['app_number']) || !isset($_SESSION['first_name'])) {
    header("Location: index.php");
    exit;
}

$app_number = $_SESSION['app_number'];
$first_name = $_SESSION['first_name'];

// Fetch application details
$stmt = $conn->prepare("SELECT * FROM applications WHERE app_number = ?");
$stmt->bind_param("s", $app_number);
$stmt->execute();
$app = $stmt->get_result()->fetch_assoc();

if (!$app) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$msg = '';

// Handle Document Upload
if (isset($_POST['upload_doc'])) {
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        $file_name = time() . '_' . basename($_FILES['document']['name']);
        $target_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['document']['tmp_name'], $target_path)) {
            $stmt = $conn->prepare("UPDATE applications SET document_path = ? WHERE app_number = ?");
            $stmt->bind_param("ss", $target_path, $app_number);
            $stmt->execute();
            $msg = "Document uploaded successfully!";
            $app['document_path'] = $target_path;
        }
    }
}

// Handle Payment Submission
if (isset($_POST['submit_payment'])) {
    $payment_method = $_POST['payment_method'];
    $receipt_path = $app['payment_receipt'];
    
    if (isset($_FILES['payment_receipt_file']) && $_FILES['payment_receipt_file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        $file_name = 'receipt_' . time() . '_' . basename($_FILES['payment_receipt_file']['name']);
        $receipt_path = $upload_dir . $file_name;
        move_uploaded_file($_FILES['payment_receipt_file']['tmp_name'], $receipt_path);
    }
    
    $stmt = $conn->prepare("UPDATE applications SET payment_method = ?, payment_receipt = ? WHERE app_number = ?");
    $stmt->bind_param("sss", $payment_method, $receipt_path, $app_number);
    $stmt->execute();
    $msg = "Payment & receipt submitted successfully!";
    $app['payment_method'] = $payment_method;
    $app['payment_receipt'] = $receipt_path;
}

// Handle Chat Message Submission
if (isset($_POST['send_message'])) {
    $message_text = trim($_POST['message_text']);
    if (!empty($message_text)) {
        $stmt = $conn->prepare("INSERT INTO messages (app_number, sender, message) VALUES (?, 'user', ?)");
        $stmt->bind_param("ss", $app_number, $message_text);
        $stmt->execute();
    }
}

// Fetch Messages
$msg_stmt = $conn->prepare("SELECT * FROM messages WHERE app_number = ? ORDER BY created_at ASC");
$msg_stmt->bind_param("s", $app_number);
$msg_stmt->execute();
$messages = $msg_stmt->get_result();
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
        .status-box { background: #e9ecef; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .section { margin-bottom: 30px; border-top: 1px solid #ddd; padding-top: 20px; }
        .btn { background: #004080; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 14px; }
        .btn:hover { background: #003366; }
        input[type="file"], select { margin: 10px 0; display: block; }
        .chat-box { height: 200px; border: 1px solid #ccc; border-radius: 4px; padding: 10px; overflow-y: scroll; background: #fafafa; margin-bottom: 10px; }
        .chat-msg { margin-bottom: 10px; padding: 8px 12px; border-radius: 6px; max-width: 70%; }
        .chat-user { background: #d1ecf1; margin-left: auto; text-align: right; }
        .chat-admin { background: #e2e3e5; margin-right: auto; }
        .logout { float: right; color: white; text-decoration: none; font-size: 14px; background: #c82333; padding: 5px 10px; border-radius: 4px; }
    </style>
</head>
<body>

<header>
    <h1>PT GRANT Portal <a href="index.php?logout=1" class="logout" onclick="sessionStorage.clear();">Logout</a></h1>
</header>

<div class="container">
    <?php if($msg): ?>
        <div class="alert"><?= $msg ?></div>
    <?php endif; ?>

    <h2>Welcome, <?= htmlspecialchars($app['first_name']) ?></h2>
    <div class="status-box">
        <p><strong>Application Number:</strong> <?= htmlspecialchars($app['app_number']) ?></p>
        <p><strong>Assistance Type:</strong> <?= htmlspecialchars($app['assistance_type']) ?></p>
        <p><strong>Current Status:</strong> <span style="color: #d9534f; font-weight: bold;"><?= htmlspecialchars($app['status']) ?></span></p>
    </div>

    <!-- Document Upload Section -->
    <div class="section">
        <h3>Submit Supporting Documents</h3>
        <?php if ($app['is_business'] == 1): ?>
            <p>Please upload your <strong>Proof of Business</strong> documents (e.g., business license, registration, or tax ID).</p>
        <?php else: ?>
            <p>Please upload your <strong>Proof of Address</strong> and <strong>Proof of Name</strong> (e.g., utility bill and government ID).</p>
        <?php endif; ?>

        <?php if (!empty($app['document_path'])): ?>
            <p style="color: green;">✔ Document uploaded successfully.</p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="document" required>
            <button type="submit" name="upload_doc" class="btn">Upload Document</button>
        </form>
    </div>

    <!-- Payment Section -->
    <div class="section">
        <h3>Grant Application Fee / Processing Payment</h3>
        <p>Select your preferred payment method (Gift Card or PayPal), complete the transfer, and upload your gift card details / payment receipt.</p>
        
        <?php if (!empty($app['payment_receipt'])): ?>
            <p style="color: green;">✔ Payment receipt submitted via <?= htmlspecialchars($app['payment_method']) ?>.</p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Payment Method:</label>
            <select name="payment_method" required>
                <option value="PayPal" <?= $app['payment_method']=='PayPal'?'selected':'' ?>>PayPal</option>
                <option value="Gift Card" <?= $app['payment_method']=='Gift Card'?'selected':'' ?>>Gift Card (Enter Card Details in Receipt / File)</option>
            </select>

            <label>Upload Payment Receipt / Gift Card Details Image:</label>
            <input type="file" name="payment_receipt_file" required>
            <button type="submit" name="submit_payment" class="btn">Submit Payment</button>
        </form>
    </div>

    <!-- Chat / Message Section -->
    <div class="section">
        <h3>Admin Support Chat</h3>
        <div class="chat-box">
            <?php
            if ($messages->num_rows > 0) {
                while($m = $messages->fetch_assoc()) {
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
            <input type="text" name="message_text" placeholder="Type your message to admin..." required style="width: 78%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            <button type="submit" name="send_message" class="btn" style="width: 18%;">Send</button>
        </form>
    </div>
</div>

</body>
</html>
