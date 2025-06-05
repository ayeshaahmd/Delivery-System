<?php
include 'db_connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Items Menu</title>
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
        h2 {
            color: red;
            border-bottom: 1px solid red;
            padding-bottom: 5px;
        }
        .restaurant-section {
            margin-bottom: 30px;
        }
        .category {
            margin: 10px 0;
        }
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
        }
        .item-card {
            background-color: #1c1c1c;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 8px red;
            text-align: center;
        }
        .item-card p {
            margin: 5px 0;
        }
        .add-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 8px 12px;
            margin-top: 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .add-btn:disabled {
            background-color: gray;
            cursor: not-allowed;
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
    <h1>🍽️ QuickBite - Browse Items</h1>
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
    <?php
    $items = [
        'Burger King' => [
            'Fast Food' => [
                ['name' => 'Whopper', 'price' => 500, 'availability' => 'Available', 'order_id' => 1],
                ['name' => 'Zinger Burger', 'price' => 450, 'availability' => 'Available', 'order_id' => 2],
                ['name' => 'Cheese Fries', 'price' => 180, 'availability' => 'Unavailable', 'order_id' => 11],
            ],
            'Cold Drinks' => [
                ['name' => 'Coke', 'price' => 70, 'availability' => 'Available', 'order_id' => 3],
                ['name' => 'Sprite', 'price' => 70, 'availability' => 'Unavailable', 'order_id' => 4],
            ]
        ],
        'Lal Qila' => [
            'Desi' => [
                ['name' => 'Chicken Biryani', 'price' => 350, 'availability' => 'Available', 'order_id' => 5],
                ['name' => 'Beef Karahi', 'price' => 700, 'availability' => 'Unavailable', 'order_id' => 6],
                ['name' => 'Chicken Handi', 'price' => 600, 'availability' => 'Available', 'order_id' => 12],
            ],
            'Desserts' => [
                ['name' => 'Gulab Jamun', 'price' => 150, 'availability' => 'Available', 'order_id' => 7],
                ['name' => 'Kheer', 'price' => 120, 'availability' => 'Available', 'order_id' => 8],
            ]
        ],
        'Sweet House' => [
            'Desserts' => [
                ['name' => 'Chocolate Cake', 'price' => 200, 'availability' => 'Available', 'order_id' => 9],
                ['name' => 'Brownie', 'price' => 180, 'availability' => 'Unavailable', 'order_id' => 10],
                ['name' => 'Strawberry Cupcake', 'price' => 100, 'availability' => 'Available', 'order_id' => 13],
            ]
        ],
        'KFC' => [
            'Fast Food' => [
                ['name' => 'Mighty Zinger', 'price' => 580, 'availability' => 'Available', 'order_id' => 14],
                ['name' => 'Twister', 'price' => 420, 'availability' => 'Unavailable', 'order_id' => 15],
            ],
            'Cold Drinks' => [
                ['name' => 'Pepsi', 'price' => 60, 'availability' => 'Available', 'order_id' => 16],
            ]
        ],
        'Pizza Max' => [
            'Pizza' => [
                ['name' => 'Fajita Pizza', 'price' => 950, 'availability' => 'Available', 'order_id' => 17],
                ['name' => 'Tikka Pizza', 'price' => 990, 'availability' => 'Available', 'order_id' => 18],
                ['name' => 'Cheese Burst', 'price' => 1050, 'availability' => 'Unavailable', 'order_id' => 19],
            ]
        ]
    ];

    foreach ($items as $restaurant => $categories) {
        echo "<div class='restaurant-section'><h2>$restaurant</h2>";
        foreach ($categories as $category => $itemList) {
            echo "<div class='category'><h3 style='color: #f33;'>$category</h3><div class='items-grid'>";
            foreach ($itemList as $item) {
                $encodedName = urlencode($item['name']);
                $encodedPrice = urlencode($item['price']);
                echo "<div class='item-card'>
                        <h4>{$item['name']}</h4>
                        <p>PKR: {$item['price']}</p>
                        <p>Order ID: {$item['order_id']}</p>
                        <p>Status: {$item['availability']}</p>";
                if ($item['availability'] === 'Available') {
                    echo "<a href='menu.php?name={$encodedName}&price={$encodedPrice}'>
                            <button class='add-btn'>Add to Menu</button>
                          </a>";
                } else {
                    echo "<button class='add-btn' disabled>Unavailable</button>";
                }
                echo "</div>";
            }
            echo "</div></div>";
        }
        echo "</div>";
    }
    ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
