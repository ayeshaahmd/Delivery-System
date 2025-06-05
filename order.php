<?php
$host = "localhost";
$dbname = "delivery management";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle order submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $restaurant_id = intval($_POST['restaurant_id'] ?? 0);
    $customer_id = intval($_POST['customer_id'] ?? 0);
    $status = trim($_POST['status'] ?? '');
    $price = intval($_POST['price'] ?? 0);

    if ($restaurant_id > 0 && $customer_id > 0 && !empty($status) && $price > 0) {
        $stmt = $conn->prepare("INSERT INTO orders (restaurant_id, customer_id, status, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisi", $restaurant_id, $customer_id, $status, $price);

        if ($stmt->execute()) {
            $message = "✅ Order placed successfully!";
        } else {
            $message = "❌ Failed to place order: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "⚠️ All fields are required and must be valid.";
    }
}

// Fetch restaurants and customers for dropdowns
$restaurants = $conn->query("SELECT restaurant_id FROM restaurants");
$customers = $conn->query("SELECT cus_id FROM customer");  // fixed table name
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Place Order</title>
    <link rel="stylesheet" href="style.css">
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
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: none;
            background: #333;
            color: #eee;
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
    <h2 style="text-align:center; color:red;">Place a New Order</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="restaurant_id">Restaurant ID:</label>
        <select id="restaurant_id" name="restaurant_id" required>
            <option value="">-- Select Restaurant --</option>
            <?php while ($row = $restaurants->fetch_assoc()): ?>
                <option value="<?= $row['restaurant_id'] ?>"><?= $row['restaurant_id'] ?></option>
            <?php endwhile; ?>
        </select>

        <label for="customer_id">Customer ID:</label>
        <select id="customer_id" name="customer_id" required>
            <option value="">-- Select Customer --</option>
            <?php while ($row = $customers->fetch_assoc()): ?>
                <option value="<?= $row['cus_id'] ?>"><?= $row['cus_id'] ?></option>
            <?php endwhile; ?>
        </select>

        <label for="status">Order Status:</label>
        <select id="status" name="status" required>
            <option value="">-- Select Status --</option>
            <option value="placed">Placed</option>
            <option value="confirmed">Confirmed</option>
            <option value="prepared">Prepared</option>
            <option value="dispatched">Dispatched</option>
        </select>

        <label for="price">Total Price:</label>
        <input type="number" id="price" name="price" required>

        <button type="submit">Submit Order</button>
    </form>
</main>

<?php include 'footer.php'; ?>
</body>
</html>
