<?php                 /* customers.php */
include 'db_connect.php';   // ← must open $conn (MySQLi)

$message = "";

/* ───── Handle new-customer form ───────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_customer'])) {
    $name     = trim($_POST['name']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($name && $email && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO customer (name, email, password) VALUES (?,?,?)"
        );
        $stmt->bind_param('sss', $name, $email, $hash);

        if ($stmt->execute()) {
            $message = "✅ Customer added.";
        } else {
            $message = "❌ Database error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "❗ All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Customers • QuickBite Manager</title>
    
    <style>
        /* minimal dark styling */
        body{background:#121212;color:#eee;font-family:Arial;margin:0}
        header{background:#000;padding:20px;text-align:center;border-bottom:2px solid red}
        nav a{color:red;margin:0 10px;text-decoration:none;font-weight:bold}
        .container{max-width:700px;margin:30px auto;padding:20px}
        .card,form{background:#1e1e1e;border-radius:10px;box-shadow:0 0 8px red;padding:15px;margin-bottom:20px}
        label{display:block;margin-top:10px;font-weight:bold}
        input{width:100%;padding:8px;background:#333;border:none;border-radius:5px;color:#eee;margin-top:5px}
        button{margin-top:15px;width:100%;padding:10px;background:red;border:none;border-radius:5px;color:#fff;font-size:16px;cursor:pointer}
        .msg{background:#222;padding:10px;border-radius:10px;box-shadow:0 0 6px red;text-align:center;margin-bottom:20px}
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
    <?php if ($message) echo "<div class='msg'>$message</div>"; ?>

    <!-- ───── add customer form ─────────────────── -->
    <h2>Add New Customer</h2>
    <form method="POST">
        <label for="name">Name:</label>
        <input id="name" name="name" type="text" required>

        <label for="email">Email:</label>
        <input id="email" name="email" type="email" required>

        <label for="password">Password:</label>
        <input id="password" name="password" type="password" required>

        <button type="submit" name="add_customer">Add Customer</button>
    </form>

    <!-- ───── customer list ─────────────────────── -->
    <h2>Customer List</h2>
    <?php
    $res = $conn->query("SELECT cus_id, name, email FROM customer ORDER BY cus_id DESC");
    if ($res && $res->num_rows) {
        while ($row = $res->fetch_assoc()) {
            echo "<div class='card'>
                    <strong>ID:</strong> {$row['cus_id']}<br>
                    <strong>Name:</strong> "  . htmlspecialchars($row['name'])  . "<br>
                    <strong>Email:</strong> " . htmlspecialchars($row['email']) . "
                  </div>";
        }
    } else {
        echo "<p>No customers found.</p>";
    }
    ?>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
