<?php
// Start session only if not already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'db_conn.php';

// Check if book ID is passed in the URL
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];

    // Fetch book details
    $query = "SELECT * FROM book WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if (!$book) {
        die("Error: Book not found.");
    }
} else {
    die("Error: No book ID provided.");
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $cart_item = [
        'id' => $book['id'],
        'title' => $book['title'],
        'price' => $book['price']
    ];

    $_SESSION['cart'][] = $cart_item;
    
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($book['title']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }
        .book-container {
            max-width: 600px;
            margin: auto;
            text-align: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 2px 2px 12px rgba(0, 0, 0, 0.1);
        }
        .book-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .button {
            display: block;
            width: 200px;
            padding: 15px;
            margin: 10px auto;
            font-size: 18px;
            text-align: center;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .back-btn {
            background-color: grey;
        }
        .back-btn:hover {
            background-color: darkgrey;
        }
    </style>
</head>
<body>

    <div class="book-container">
        <h2><?php echo htmlspecialchars($book['title']); ?></h2>

        <!-- Display Book Image -->
        <?php if (!empty($book['image_url'])): ?>
            <img src="<?php echo htmlspecialchars($book['image_url']); ?>" alt="Book Cover" class="book-image">
        <?php else: ?>
            <p><em>No image available</em></p>
        <?php endif; ?>

        <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
        <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($book['price']); ?></p>
        
        <a href="home.php" class="button back-btn">Back to Home</a>

        <form action="book_details.php?id=<?php echo $book_id; ?>" method="post">
            <button type="submit" name="add_to_cart" class="button">Add to Cart</button>
        </form>
    </div>

</body>
</html>
