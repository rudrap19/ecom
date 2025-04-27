<?php
session_start();
include 'db_conn.php';

// Initialize the cart session if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Initialize the cart total if not set
if (!isset($_SESSION['cart_total'])) {
    $_SESSION['cart_total'] = 0;
}

// Function to calculate the cart total
function calculateCartTotal() {
    $total = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'];
        }
    }
    return $total;
}

// Update the global cart total
$_SESSION['cart_total'] = calculateCartTotal();

// Handle discarding the cart
if (isset($_GET['action']) && $_GET['action'] == "discard") {
    unset($_SESSION['cart']);
    unset($_SESSION['cart_total']);
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }
        .cart-container {
            width: 50%;
            margin: auto;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }
        .cart-item {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 15px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-right: 10px;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .discard {
            background-color: red;
        }
        .discard:hover {
            background-color: darkred;
        }
        .checkout {
            background-color: green;
        }
        .checkout:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>

    <div class="cart-container">
        <h2>Your Shopping Cart</h2>

        <?php if (!empty($_SESSION['cart'])): ?>
            <ul>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <li class="cart-item">
                        <strong><?php echo htmlspecialchars($item['title']); ?></strong> - $<?php echo htmlspecialchars(number_format($item['price'], 2)); ?>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="total">
                Total: $<?php echo number_format($_SESSION['cart_total'], 2); ?>
            </div>

            <a href="cart.php?action=discard" class="button discard">Discard Cart</a>
            <a href="home.php" class="button">Back to Home</a>
            <a href="checkout.php" class="button checkout">Checkout</a>

        <?php else: ?>
            <p>Your cart is empty.</p>
            <a href="home.php" class="button">Continue Shopping</a>
        <?php endif; ?>

    </div>

</body>
</html>
