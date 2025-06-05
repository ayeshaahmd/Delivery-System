<?php
$host = "localhost";
$dbname = "delivery management";  // DB name with space
$username = "root";
$password = "";

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = intval($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? '';

    $valid_statuses = ['placed', 'confirmed', 'prepared', 'dispatched'];

    if ($order_id > 0 && in_array($status, $valid_statuses)) {
        $stmt = $conn->prepare("INSERT INTO `order_history` (order_id, status) VALUES (?, ?)");
        $stmt->bind_param("is", $order_id, $status);

        if ($stmt->execute()) {
            $message = "✅ Order ID <strong>$order_id</strong> with status <strong>$status</strong> saved successfully.";
        } else {
            $message = "❌ Error saving data: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "⚠️ Please enter a valid Order ID and Status.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="style.css">
<title>Order Status Form</title>
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
    header h1 {
        margin: 0;
        color: white;
    }
    nav {
        margin-top: 10px;
    }
    nav a {
        color: red;
        margin: 0 12px;
        text-decoration: none;
        font-weight: bold;
        font-size: 16px;
    }
    nav a:hover {
        text-decoration: underline;
    }
    main {
        padding: 20px;
    }
    form {
        background: #1e1e1e;
        padding: 20px;
        border-radius: 10px;
        max-width: 400px;
        margin: 30px auto;
        box-shadow: 0 0 10px red;
    }
    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
    }
    input[type="text"],
    select {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        border-radius: 5px;
        border: none;
        background: #333;
        color: #eee;
        font-size: 16px;
    }
    button {
        margin-top: 20px;
        width: 100%;
        padding: 10px;
        background: red;
        border: none;
        border-radius: 5px;
        color: white;
        font-size: 18px;
        cursor: pointer;
    }
    .message {
        max-width: 400px;
        margin: 20px auto;
        padding: 15px;
        background: #222;
        border-radius: 10px;
        box-shadow: 0 0 8px red;
        text-align: center;
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

<main>
    <h2 style="text-align:center; color:red;">Update Order Status</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="order_id">Order ID:</label>
        <input type="text" id="order_id" name="order_id" placeholder="Enter Order ID" required>

        <label for="status">Order Status:</label>
        <select id="status" name="status" required>
            <option value="">-- Select Status --</option>
            <option value="placed">Placed</option>
            <option value="confirmed">Confirmed</option>
            <option value="prepared">Prepared</option>
            <option value="dispatched">Dispatched</option>
        </select>

        <button type="submit">Submit</button>
    </form>
</main>
<?php include 'footer.php'; ?>

</body>
</html>
