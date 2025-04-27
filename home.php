<?php
session_start();
require 'db_conn.php'; // Now handles errors internally


// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php?action=login");
    exit;
}

$user = $_SESSION['user']; // Retrieve user session data

// Sanitize search input
$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
$searchParam = "%$search%";


#binding for search 
$sql = "SELECT * FROM book WHERE title LIKE ? OR author LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Store</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; }
        .book { display: flex; align-items: center; border-bottom: 1px solid #ddd; padding: 10px; }
        .book img { width: 80px; height: 120px; margin-right: 15px; object-fit: cover; }
        .book a { text-decoration: none; color: #333; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($user['name'] ?? "User"); ?></h2> 
        
        <!-- Search form -->
        <form method="GET">
    <input type="text" name="search" placeholder="Search books..." value="<?php echo htmlspecialchars($search); ?>" 
           style="width: 80%; padding: 12px; font-size: 16px; border-radius: 8px;">
           <button type="submit" style="padding: 12px 20px; font-size: 16px; border-radius: 8px; background-color: green; color: white; border: none; cursor: pointer;">
    Search
</button>
</form>

        
        <h3>Available Books</h3>
        
<?php while ($book = $result->fetch_assoc()): ?>
    <div class="book">
        <?php 
            $imageUrl = isset($book['image_url']) ? htmlspecialchars($book['image_url']) : 'default.jpg'; 
        ?>
        <img src="<?php echo $imageUrl; ?>" alt="Book Cover">
        <a href="book_details.php?id=<?php echo $book['id']; ?>">
            <h4><?php echo htmlspecialchars($book['title']); ?></h4>
            <p>By <?php echo htmlspecialchars($book['author']); ?></p>
        </a>
    </div>
<?php endwhile; ?>

 
</div>   
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>

