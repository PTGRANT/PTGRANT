<?php
// save.php - Processes and saves grant applications to MySQL

// Database Credentials
$dbHost = 'localhost';
$dbName = 'pt_grant_db';
$dbUser = 'root';       // Change to your MySQL username
$dbPass = '';           // Change to your MySQL password

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and collect input values
    $fullName            = trim($_POST['fullName'] ?? '');
    $email               = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $phone               = trim($_POST['phone'] ?? '');
    $grantType           = trim($_POST['grantType'] ?? '');
    $amountRequested     = filter_var($_POST['amountRequested'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $deliveryFeeAccepted = isset($_POST['deliveryFeeAccepted']) ? 1 : 0;
    $reason              = trim($_POST['reason'] ?? '');

    // Form validation check
    if (empty($fullName) || !$email || empty($phone) || empty($grantType) || empty($reason) || $amountRequested <= 0) {
        header("Location: index.php?status=error&msg=" . urlencode("Please fill in all required fields with valid information."));
        exit;
    }

    try {
        // Establish PDO Database Connection
        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        // Prepare SQL Statement (Prevents SQL Injection)
        $sql = "INSERT INTO applications 
                (full_name, email, phone, grant_type, amount_requested, delivery_fee_accepted, reason) 
                VALUES (:full_name, :email, :phone, :grant_type, :amount_requested, :delivery_fee_accepted, :reason)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':full_name'            => $fullName,
            ':email'                => $email,
            ':phone'                => $phone,
            ':grant_type'           => $grantType,
            ':amount_requested'     => $amountRequested,
            ':delivery_fee_accepted'=> $deliveryFeeAccepted,
            ':reason'               => $reason
        ]);

        $applicationId = $pdo->lastInsertId();
        $refId = sprintf("%05d", $applicationId);

        // Redirect back to index with success status
        header("Location: index.php?status=success&ref=" . $refId);
        exit;

    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        header("Location: index.php?status=error&msg=" . urlencode("A system error occurred while processing your application. Please try again later."));
        exit;
    }
} else {
    // If accessed directly without POST, redirect back home
    header("Location: index.php");
    exit;
}