<?php
$host = "localhost";
$dbname = "delivery management";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = intval($_POST['order_id'] ?? 0);
    $delivery_status = trim($_POST['delivery_status'] ?? 'assigned');
    $delivery_person_id = intval($_POST['delivery_person_id'] ?? 0);

    if ($order_id > 0 && !empty($delivery_status) && $delivery_person_id > 0) {
        $stmt = $conn->prepare("INSERT INTO deliveries (order_id, delivery_status, delivery_person_id) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $order_id, $delivery_status, $delivery_person_id);

        if ($stmt->execute()) {
            $message = "✅ Delivery record added successfully!";
        } else {
            $message = "❌ Failed to save delivery: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "⚠️ All fields are required and must be valid.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Delivery Entry</title>
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
        input[type="number"],
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
        <a href="items.php">Items</a>
        <a href="menu.php">Menu</a>
        <a href="order.php">Orders</a>
        <a href="order_history.php">Order History</a>
        <a href="deliveries.php">Deliveries</a>
        <a href="restaurants.php">Restaurants</a>
        <a href="customers.php">Customers</a>
        <a href="payments.php">Payments</a>
    </nav>
</header>

<main>
    <h2 style="text-align:center; color:red;">New Delivery Entry</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="order_id">Order ID:</label>
        <input type="number" id="order_id" name="order_id" required>

        <label for="delivery_status">Delivery Status:</label>
        <select id="delivery_status" name="delivery_status" required>
            <option value="assigned">Assigned</option>
            <option value="picked_up">Picked Up</option>
            <option value="delivered">Delivered</option>
        </select>

        <label for="delivery_person_id">Delivery Person ID:</label>
        <input type="number" id="delivery_person_id" name="delivery_person_id" required>

        <button type="submit">Save Delivery</button>
    </form>
</main>

<?php include 'footer.php'; ?>

</body>
</html>
