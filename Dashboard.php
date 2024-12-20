<?php
session_start();
$conn = mysqli_connect("localhost", "root", "hassizaio2010", "blogpress");

if (!isset($_SESSION["username"])) {
    header("location: index.php");
}
if (isset($_POST["publish"])) {
    $articleTitle = $_POST["title"];
    $articleContent = $_POST["content"];
    $articleCategory = $_POST["category"];
    mysqli_query($conn, "INSERT INTO articles (article_content,article_title,author_id, article_category , created_at) values('$articleContent' , '$articleTitle' , {$_SESSION['id']}, '$articleCategory', now())");
    header("location: " . $_SERVER["PHP_SELF"]);
    exit();
}

if (isset($_GET["articleId"])) {
    $deletedArticle = $_GET["articleId"];
    $deleteQuery = mysqli_query($conn, "DELETE from articles where article_id = $deletedArticle");
}






/////////
$totalsQuery = mysqli_query($conn, "
    select 
        sum(articles.view_count) as totalviews,
        authors.author_id as author,
        count(articles.article_id) as total_articles,
        sum(articles.like_count) as total_likes,
        count(comments.comment_id) as total_comments
        from authors
        join articles on articles.author_id = authors.author_id
        join comments on comments.article_id = articles.article_id
    WHERE authors.author_id = {$_SESSION['id']}
");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <div class="header">
        <div class="dashboard-logo">
            <a href="index.php"><img id="logo-svg" src="images/BLOG (3).svg" alt=""></a>
        </div>
        <div class="hamburger-menu">
            <button onclick="test()" class="hamburger-icon">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
            </button>
            <div class="hamburger-options">
                <button class="menu-option" onclick="addArticle()">Add Article</button>
                <button class="menu-option" onclick="manageArticle()">Manage Article</button>
                <a id="logout-dec" href="logout.php"><button class="menu-option">Logout</button></a>
            </div>
        </div>
    </div>


    <div class="blog-creation">
        <h3>Create a New Blog</h3>
        <form action="Dashboard.php" method="POST" class="blog-form">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" placeholder="Enter blog title" required>

            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="8" placeholder="Write your blog content here..." required></textarea>

            <label for="category">Category:</label>
            <select id="category" name="category">
                <option value="technology">Technology</option>
                <option value="lifestyle">Lifestyle</option>
                <option value="education">Education</option>
                <option value="health">Health</option>
                <option value="business">Business</option>
            </select>
            <button type="submit" class="btn-submit" name="publish">Publish Blog</button>
        </form>
    </div>
    <div class="management">
        <div class="article-list">
            <h3>Manage Your Articles</h3>
            <div class="article-list">
                <?php
                $article_author = mysqli_query($conn, "SELECT article_title, article_id , article_category FROM articles where author_id = '{$_SESSION['id']}'");
                while ($result = mysqli_fetch_assoc($article_author)) {
                    echo "<div class='article-item'>
                    <div class='article-info'>
                        <h4>" . htmlspecialchars($result['article_title']) . "</h4>
                        <p>Category: " . htmlspecialchars($result['article_category']) . "</p>
                    </div>
                    <div class='article-actions'>
                        <a href='edit.php?editedId={$result['article_id']}'><button class='btn-edit'>Edit</button></a>
                        <a href='dashboard.php?articleId={$result['article_id']}'><button name='delete' class='btn-delete'>Delete</button></a>
                    </div>
                  </div>";
                }
                ?>
            </div>

        </div>
    </div>
    <div class="dashboard-content">
        <div class="my-articles">
            <div class="welcome">
                <h3 class="hello-user">Hello, <?php echo $_SESSION["username"]; ?>!</h3>
            </div>
            <div class="dashboard">
                <h1>Author Statistics</h1>
                <div class="stats-badges">
                    <?php
                    while ($row = mysqli_fetch_assoc($totalsQuery)) {
                        echo "
                <div class='stats-badge'>
                    <span class='badge-label'>Total Articles</span>
                    <span class='badge-value'>" . $row['total_articles'] . "</span>
                </div>
                <div class='stats-badge'>
                    <span class='badge-label'>Total Likes</span>
                    <span class='badge-value'>" . ($row['total_likes'] ?? 0) . "</span>
                </div>
                <div class='stats-badge'>
                    <span class='badge-label'>Total Comments</span>
                    <span class='badge-value'>" . ($row['total_comments'] ?? 0) . "</span>
                </div>
                <div class='stats-badge'>
                    <span class='badge-label'>Total Views</span>
                    <span class='badge-value'>" . ($row['totalviews'] ?? 0) . "</span>
                </div>";
                    }
                    ?>
                </div>
            </div>
        </div>



        <div class="my_stats"></div>
    </div>

    <script src="style/index.js"></script>
</body>

</html>