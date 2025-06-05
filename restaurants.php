<?php
include 'db_connect.php'; // Make sure this connects to your MySQL database

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_restaurant'])) {
    $rest_name = trim($_POST['rest_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($rest_name && $address && $phone) {
        $stmt = $conn->prepare("INSERT INTO restaurants (rest_name, address, phone) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $rest_name, $address, $phone);

        if ($stmt->execute()) {
            $message = "✅ Restaurant added successfully.";
        } else {
            $message = "❌ Database Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "❗ Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Restaurants - QuickBite Manager</title>
     <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #121212;
            color: #eee;
            font-family: Arial, sans-serif;
            margin: 0; padding: 0;
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
        .container {
            padding: 20px;
            max-width: 700px;
            margin: 20px auto;
        }
        .item {
            background: #1e1e1e;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: 0 0 8px red;
        }
        form {
            background: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px red;
            max-width: 400px;
            margin: 30px auto;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type="text"] {
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

<div class="container">
    <h2>Restaurants List</h2>

    <?php if (isset($message)) echo "<div class='message'>$message</div>"; ?>

    <?php
    $query = "SELECT * FROM restaurants ORDER BY restaurant_id DESC";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='item'>";
            echo "<strong>Name:</strong> " . htmlspecialchars($row['rest_name']) . "<br>";
            echo "<strong>Address:</strong> " . htmlspecialchars($row['address']) . "<br>";
            echo "<strong>Phone:</strong> " . htmlspecialchars($row['phone']);
            echo "</div>";
        }
    } else {
        echo "<p>No restaurants found.</p>";
    }
    ?>

    <h2>Add New Restaurant</h2>
    <form method="POST">
        <label for="rest_name">Restaurant Name:</label>
        <input type="text" id="rest_name" name="rest_name" placeholder="e.g. Pizza House" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" placeholder="123 Main Street" required>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" placeholder="e.g. 123-456-7890" required>

        <button type="submit" name="add_restaurant">Add Restaurant</button>
    </form>
</div>
<?php include 'footer.php'; ?>
</body>
</html>

