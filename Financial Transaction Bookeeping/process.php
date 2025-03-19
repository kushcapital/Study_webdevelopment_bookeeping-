<?php
include 'config.php';

$action = $_POST['action'];

if ($action == 'create') {
    // Collect form data
    $transaction_name = $_POST['transaction_name'];
    $transaction_type = $_POST['transaction_type'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $beneficiaries = isset($_POST['beneficiaries']) ? $_POST['beneficiaries'] : [];

    // Combine all data into a single JSON string
    $transaction_details = json_encode([
        'transaction_name' => $transaction_name,
        'transaction_type' => $transaction_type,
        'amount' => $amount,
        'date' => $date,
        'beneficiaries' => $beneficiaries
    ]);

    // Insert into the database
    $stmt = $pdo->prepare("INSERT INTO transactions (transaction_details) VALUES (?)");
    $stmt->execute([$transaction_details]);

    // Redirect back to the main page
    header("Location: index.php");
    exit();

} elseif ($action == 'search_type') {
    // Search by transaction type
    $search_type = $_POST['search_type'];
    $stmt = $pdo->query("SELECT * FROM transactions");
    echo "<h2>Search Results for Type: $search_type</h2>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $details = json_decode($row['transaction_details'], true);
        if ($details['transaction_type'] == $search_type) {
            echo "<p><strong>ID:</strong> {$row['transaction_id']} | ";
            echo "<strong>Name:</strong> {$details['transaction_name']} | ";
            echo "<strong>Type:</strong> {$details['transaction_type']} | ";
            echo "<strong>Amount:</strong> {$details['amount']} | ";
            echo "<strong>Date:</strong> {$details['date']} | ";
            echo "<strong>Beneficiaries:</strong> " . implode(", ", $details['beneficiaries']) . "</p>";
        }
    }
    echo '<a href="index.php">Back to Home</a>';

} elseif ($action == 'search_beneficiary') {
    // Search by beneficiary
    $search_beneficiary = $_POST['search_beneficiary'];
    $stmt = $pdo->query("SELECT * FROM transactions");
    echo "<h2>Search Results for Beneficiary: $search_beneficiary</h2>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $details = json_decode($row['transaction_details'], true);
        if (in_array($search_beneficiary, $details['beneficiaries'])) {
            echo "<p><strong>ID:</strong> {$row['transaction_id']} | ";
            echo "<strong>Name:</strong> {$details['transaction_name']} | ";
            echo "<strong>Type:</strong> {$details['transaction_type']} | ";
            echo "<strong>Amount:</strong> {$details['amount']} | ";
            echo "<strong>Date:</strong> {$details['date']} | ";
            echo "<strong>Beneficiaries:</strong> " . implode(", ", $details['beneficiaries']) . "</p>";
        }
    }
    echo '<a href="index.php">Back to Home</a>';
}
?>