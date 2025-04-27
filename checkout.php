<?php
session_start();

// Check if cart total is set, otherwise redirect to cart
if (!isset($_SESSION['cart_total']) || $_SESSION['cart_total'] == 0) {
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .checkout-container {
            width: 50%;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px #ccc;
        }
        .checkout-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .confirm-button {
            width: 100%;
            padding: 12px;
            background-color: green;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .confirm-button:hover {
            background-color: darkgreen;
        }
    </style>
</head>
<body>

<div class="checkout-container">
    <h2>Checkout</h2>

    <div class="total">
        Total Amount: $<?php echo number_format($_SESSION['cart_total'], 2); ?>
    </div>

    <form method="POST" action="confirm_order.php">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone Number:</label>
            <input type="tel" name="phone" id="phone" required pattern="[0-9]{10}" title="Enter a 10-digit phone number">
        </div>

        <div class="form-group">
            <label for="address1">Address Line 1:</label>
            <input type="text" name="address1" id="address1" required>
        </div>

        <div class="form-group">
            <label for="address2">Address Line 2:</label>
            <input type="text" name="address2" id="address2">
        </div>

        <div class="form-group">
            <label for="address3">Address Line 3:</label>
            <input type="text" name="address3" id="address3">
        </div>

        <button type="submit" class="confirm-button">Confirm Order</button>
    </form>
</div>

</body>
</html>
