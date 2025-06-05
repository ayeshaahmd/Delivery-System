<?php
include 'db_connect.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_payment'])) {
    $order_id = intval($_POST['order_id']);
    $amount = intval($_POST['amount']);
    $method = $_POST['method'];

    if ($order_id && $amount && in_array($method, ['cash', 'card', 'online'])) {
        $stmt = $conn->prepare("INSERT INTO payments (order_id, amount, method) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $order_id, $amount, $method);

        if ($stmt->execute()) {
            $message = "✅ Payment added successfully.";
        } else {
            $message = "❌ Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "❗ All fields are required and must be valid.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payments - QuickBite Manager</title>
    
    <style>
        body {
            background-color: #121212;
            color: #eee;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #000;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid red;
        }
        nav a {
            color: red;
            margin: 0 12px;
            text-decoration: none;
            font-weight: bold;
        }
        .container {
            max-width: 700px;
            margin: 30px auto;
            padding: 20px;
        }
        form, .item {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px red;
            margin-bottom: 20px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: none;
            background: #333;
            color: #fff;
        }
        label {
            font-weight: bold;
            margin-top: 15px;
            display: block;
        }
        button {
            margin-top: 20px;
            background: red;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .message {
            background: #222;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 8px red;
            text-align: center;
            margin-bottom: 20px;
        }
    footer {
    background-color: #000;
    color: red;
    text-align: center;
    padding: 20px 0;
    border-top: 2px solid red;
    }
    </style>
</head>
<body>

<header>
    <h1>🍽️ QuickBite Manager</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="menu.php">Menu</a>
        <a href="items.php">Items</a>
        <a href="order.php">Order</a>
        <a href="order_history.php">Order History</a>
        <a href="deliveries.php">Deliveries</a>
        <a href="restaurants.php">Restaurants</a>
        <a href="customers.php">Customers</a>
        <a href="payments.php">Payments</a>
    </nav>
</header>

<div class="container">
    <h2>Add New Payment</h2>
    <?php if (isset($message)) echo "<div class='message'>$message</div>"; ?>
    
    <form method="POST">
        <label for="order_id">Order ID:</label>
        <input type="number" name="order_id" id="order_id" placeholder="Enter Order ID" required>

        <label for="amount">Amount:</label>
        <input type="number" name="amount" id="amount" placeholder="Enter amount" required>

        <label for="method">Payment Method:</label>
        <select name="method" id="method" required>
            <option value="">-- Select --</option>
            <option value="cash">Cash</option>
            <option value="card">Card</option>
            <option value="online">Online</option>
        </select>

        <button type="submit" name="add_payment">Add Payment</button>
    </form>

    <h2>Payment Records</h2>
    <?php
    $result = $conn->query("SELECT * FROM payments ORDER BY payment_id DESC");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='item'>";
            echo "<strong>Payment ID:</strong> " . $row['payment_id'] . "<br>";
            echo "<strong>Order ID:</strong> " . $row['order_id'] . "<br>";
            echo "<strong>Amount:</strong> $" . $row['amount'] . "<br>";
            echo "<strong>Method:</strong> " . ucfirst($row['method']) . "<br>";
            // echo "<strong>Paid At:</strong> " . $row['paid_at'];
            echo "</div>";
        }
    } else {
        echo "<p>No payments found.</p>";
    }
    ?>
</div>
<?php include 'footer.php'; ?>

</body>
</html>
