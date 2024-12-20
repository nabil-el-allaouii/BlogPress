<?php
$conn = mysqli_connect("localhost", "root", "hassizaio2010", "blogpress");

$articleId = $_GET['articleId'];


$articleQuery = mysqli_query($conn, "
    SELECT articles.*, authors.author_name 
    FROM articles 
    JOIN authors ON articles.author_id = authors.author_id 
    WHERE articles.article_id = '$articleId'
");
$article = mysqli_fetch_assoc($articleQuery);


$commentsQuery = mysqli_query($conn, "
    SELECT comment_owner, comment_content, comment_date 
    FROM comments 
    WHERE comments.article_id = '$articleId'
");

mysqli_query($conn, "UPDATE articles SET view_count = view_count + 1 WHERE article_id = '$articleId'");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'])) {
    $updateLikes = mysqli_query($conn, "UPDATE articles SET like_count = like_count + 1 WHERE article_id = '$articleId'");
    header("Location: view.php?articleId=$articleId");
    exit();
}
if (isset($_POST["comment"])) {
    $TheComment = $_POST["comment_text"];
    $ThePlacer = $_POST["comment_placer"];
    $sendComment = mysqli_query($conn, "INSERT INTO comments (comment_content , comment_owner , article_id ,comment_date) values('$TheComment' , '$ThePlacer', '$articleId' , now())");
    header("Location: view.php?articleId=$articleId");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['article_title']); ?></title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <header>
        <div class="logo">
            <a href="index.php"><img id="logo-svg" src="images/BLOG (3).svg" alt=""></a>
        </div>
    </header>


    <div class="container">
        <h1 class="article-title"><?php echo htmlspecialchars($article['article_title']); ?></h1>
        <p class="article-meta">By <?php echo htmlspecialchars($article['author_name']); ?> on <?php echo date("F j, Y", strtotime($article['created_at'])); ?></p>
        <div class="article-content"><?php echo nl2br(htmlspecialchars($article['article_content'])); ?></div>

        <div class="stats">
            <span>Views: <?php echo $article['view_count']; ?></span>
            <span>Likes: <?php echo $article['like_count']; ?></span>
            <form class="prevent" method="POST" style="display: inline;">
                <button type="submit" name="like" class="like-button">Like</button>
            </form>
        </div>

        <div class="comments-section">
            <h3>Comments</h3>
            <?php
            while ($comment = mysqli_fetch_assoc($commentsQuery)) {
                echo "
                    <div class='comment'>
                        <p class='comment-username'>" . htmlspecialchars($comment['comment_owner']) . "</p>
                        <p class='comment-date'>" . date("F j, Y, g:i a", strtotime($comment['comment_date'])) . "</p>
                        <p class='comment-text'>" . htmlspecialchars($comment['comment_content']) . "</p>
                    </div>";
            }
            ?>

            <h3>Leave a Comment</h3>
            <form method="POST" action="view.php?articleId=<?php echo $articleId; ?>" class="comment-form">
                <input class="commenter-name" type="text" placeholder="Your name" name="comment_placer" required>
                <textarea name="comment_text" placeholder="Write your comment here..." required></textarea>
                <button type="submit" name="comment">Submit</button>
            </form>
        </div>
    </div>

    <script>
        // let prevent = document.querySelector("");
        // document.addEventListener("DOMContentLoaded", () => {
        //     const likeButton = document.querySelector(".like-button");

        //     // likeButton.addEventListener("click", function() {
        //     //     // setTimeout(() => {
        //     //     likeButton.style.display = "none"
        //     //     // }, 50);
        //     // });
        // });
    </script>
</body>

</html>