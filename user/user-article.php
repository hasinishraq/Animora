<?php
session_start();
include '../config/db.php';  // Adjust the path if needed

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission for posting a new article
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title']) && isset($_POST['content'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $authorID = $_SESSION['user_id'];  // Get the logged-in user's ID

    // Insert article into the database
    $sql = "INSERT INTO articles (AuthorID, Title, Content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $authorID, $title, $content);

    if ($stmt->execute()) {
        echo "Article posted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch all articles from the database
$sql = "SELECT a.ArticleID, a.Title, a.Content, a.PostedAt, u.Name AS Author
        FROM articles a
        JOIN users u ON a.AuthorID = u.UserID
        ORDER BY a.PostedAt DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles & Post New Article</title>
    <link rel="stylesheet" href="style.css">  <!-- Optional: External CSS -->
</head>
<body>

    <h1>Articles</h1>
    
    <!-- Display Articles -->
    <div class="articles">
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<div class='article'>";
                echo "<h2>" . htmlspecialchars($row['Title']) . "</h2>";
                echo "<p><strong>By: " . htmlspecialchars($row['Author']) . " | Posted on: " . $row['PostedAt'] . "</strong></p>";
                echo "<p>" . nl2br(htmlspecialchars($row['Content'])) . "</p>";
                echo "</div><hr>";
            }
        } else {
            echo "<p>No articles found.</p>";
        }
        ?>
    </div>

    <!-- Form to Post a New Article -->
    <h2>Post a New Article</h2>
    <form method="POST" action="articles_post.php">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br><br>

        <label for="content">Content:</label><br>
        <textarea id="content" name="content" rows="10" required></textarea><br><br>

        <button type="submit">Post Article</button>
    </form>

    <a href="logout.php">Logout</a>  <!-- Add logout link -->
</body>
</html>
