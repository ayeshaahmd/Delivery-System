<?php
include 'db_connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Prefill values from GET
$prefill_name = $_GET['name'] ?? '';
$prefill_price = $_GET['price'] ?? '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $name         = trim($_POST['name'] ?? '');
    $description  = trim($_POST['description'] ?? '');
    $price        = intval($_POST['price'] ?? 0);
    $availability = $_POST['availability'] ?? 'available';

    if ($name && $description && $price > 0 && in_array($availability, ['available', 'unavailable'])) {
        $result = $conn->query("SELECT COALESCE(MAX(menu_id), 0) + 1 AS next_id FROM menu");
        $nextId = $result->fetch_assoc()['next_id'];

        $stmt = $conn->prepare("INSERT INTO menu (menu_id, name, description, price, availability) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issis", $nextId, $name, $description, $price, $availability);

        if ($stmt->execute()) {
            header("Location: menu.php");
            exit;
        } else {
            echo "<p style='color:red;text-align:center;'>DB Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color:red;text-align:center;'>Please fill in all fields correctly.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Menu</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #111;
            color: #eee;
            margin: 0;
        }
        header {
            background-color: #000;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid red;
        }
        header h1 {
            color: white;
            margin: 0;
        }
        nav a {
            color: red;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }
        .container {
            padding: 20px;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .item {
            background-color: #1c1c1c;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px red;
            text-align: center;
        }
        .order-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 10px 15px;
            margin-top: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        h2 {
            color: red;
        }
        form.add-form {
            background-color: #222;
            padding: 20px;
            margin-top: 40px;
            border-radius: 10px;
        }
        form.add-form input,
        form.add-form textarea,
        form.add-form select {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: none;
            border-radius: 5px;
        }
        form.add-form input[type="submit"] {
            background-color: red;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }
        label {
            font-weight: bold;
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
        <a href="order.php">Order</a>
        <a href="order_history.php">Order History</a>
        <a href="deliveries.php">Deliveries</a>
        <a href="restaurants.php">Restaurants</a>
        <a href="customers.php">Customers</a>
        <a href="payments.php">Payments</a>
    </nav>
</header>

<div class="container">
    <h2>Our Menu</h2>
    <div class="menu-grid">
        <?php
        $menu = $conn->query("SELECT * FROM menu");
        while ($row = $menu->fetch_assoc()) {
            echo "<div class='item'>
                    <h3>{$row['name']}</h3>
                    <p>{$row['description']}</p>
                    <p><strong>PKR {$row['price']}</strong></p>
                    <p>Status: {$row['availability']}</p>
                    <form method='POST' action='order.php'>
                        <input type='hidden' name='menu_id' value='{$row['menu_id']}'>
                        <button class='order-btn' type='submit'>Order Now</button>
                    </form>
                  </div>";
        }
        ?>
    </div>

    <h2>Add New Menu Item</h2>
    <form class="add-form" method="POST">
        <label>Item Name:</label>
        <input type="text" name="name" required value="<?= htmlspecialchars($prefill_name) ?>">

        <label>Description:</label>
        <textarea name="description" required><?= $prefill_name ? "Imported from items page." : "" ?></textarea>

        <label>Price (PKR):</label>
        <input type="number" name="price" required value="<?= htmlspecialchars($prefill_price) ?>">

        <label>Availability:</label>
        <select name="availability" required>
            <option value="available">Available</option>
            <option value="unavailable">Unavailable</option>
        </select>

        <input type="submit" name="add_item" value="Add Item">
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
