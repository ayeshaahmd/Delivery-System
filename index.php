<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Home - QuickBite Manager Food</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
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
    </style>
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

<div class="container home">
    <div class="text-section">
        <h2>Welcome to QuickBite!</h2>
        <p>Hungry? We’ve got you covered. Browse our delicious menu and order in seconds.</p>
        <a href="items.php" class="btn">View Menu 🍕</a>
    </div>
    <div class="image-section">
        <img src="images/im.jpg" alt="Food Banner">
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
