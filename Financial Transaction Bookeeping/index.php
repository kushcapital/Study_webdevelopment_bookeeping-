<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Transaction Book Keeping</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Financial Transaction Book Keeping</h1>

    <!-- Form to Create a New Transaction -->
    <h2>Create a New Transaction</h2>
    <form action="process.php" method="POST">
        <input type="hidden" name="action" value="create">
        <label for="transaction_name">Transaction Name:</label>
        <input type="text" name="transaction_name" required><br>

        <label for="transaction_type">Transaction Type:</label>
        <select name="transaction_type" required>
            <option value="Purchase">Purchase</option>
            <option value="Income">Income</option>
            <option value="Tax Payment">Tax Payment</option>
        </select><br>

        <label for="amount">Overall Amount:</label>
        <input type="number" step="0.01" name="amount" required><br>

        <label for="date">Date:</label>
        <input type="date" name="date" required><br>

        <label>Beneficiaries (Select all that apply):</label><br>
        <input type="checkbox" name="beneficiaries[]" value="Alice"> Alice<br>
        <input type="checkbox" name="beneficiaries[]" value="Bob"> Bob<br>
        <input type="checkbox" name="beneficiaries[]" value="Charlie"> Charlie<br>

        <button type="submit">Add Transaction</button>
    </form>

    <!-- Display Overall Balance -->
    <h2>Overall Balance</h2>
    <?php
    include 'config.php';
    $stmt = $pdo->query("SELECT transaction_details FROM transactions");
    $balance = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $details = json_decode($row['transaction_details'], true);
        $amount = $details['amount'];
        $type = $details['transaction_type'];
        if ($type == 'Income') {
            $balance += $amount;
        } else {
            $balance -= $amount;
        }
    }
    echo "<p>Current Balance: $balance</p>";
    ?>

    <!-- List All Transactions -->
    <h2>All Transactions</h2>
    <?php
    $stmt = $pdo->query("SELECT * FROM transactions ORDER BY created_at DESC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $details = json_decode($row['transaction_details'], true);
        echo "<p><strong>ID:</strong> {$row['transaction_id']} | ";
        echo "<strong>Name:</strong> {$details['transaction_name']} | ";
        echo "<strong>Type:</strong> {$details['transaction_type']} | ";
        echo "<strong>Amount:</strong> {$details['amount']} | ";
        echo "<strong>Date:</strong> {$details['date']} | ";
        echo "<strong>Beneficiaries:</strong> " . implode(", ", $details['beneficiaries']) . "</p>";
    }
    ?>

    <!-- Search Transactions by Type -->
    <h2>Search Transactions by Type</h2>
    <form action="process.php" method="POST">
        <input type="hidden" name="action" value="search_type">
        <label for="search_type">Transaction Type:</label>
        <select name="search_type">
            <option value="Purchase">Purchase</option>
            <option value="Income">Income</option>
            <option value="Tax Payment">Tax Payment</option>
        </select>
        <button type="submit">Search</button>
    </form>

    <!-- Search Transactions by Beneficiary -->
    <h2>Search Transactions by Beneficiary</h2>
    <form action="process.php" method="POST">
        <input type="hidden" name="action" value="search_beneficiary">
        <label for="search_beneficiary">Beneficiary:</label>
        <select name="search_beneficiary">
            <option value="Alice">Alice</option>
            <option value="Bob">Bob</option>
            <option value="Charlie">Charlie</option>
        </select>
        <button type="submit">Search</button>
    </form>

</body>
</html>