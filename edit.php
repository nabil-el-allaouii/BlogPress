<?php
$conn = mysqli_connect("localhost", "root", "hassizaio2010", "blogpress");

$editedArticle = $_GET["editedId"];

$editQuery = mysqli_query($conn, "SELECT * from articles where article_id = $editedArticle");
$editresult = mysqli_fetch_assoc($editQuery);

if(isset($_POST['update-it'])){
    $Utitle = $_POST['article_title'];
    $Ucontent = $_POST['article_content'];
    $Ucategory = $_POST['article_category'];
    mysqli_query($conn, "UPDATE articles SET article_content = '$Ucontent' , article_title = '$Utitle', article_category = '$Ucategory' where article_id = $editedArticle");
    header("location: dashboard.php");
    exit();
}

?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <header>
        <div class="logo">
            <a href="index.php"><img id="logo-svg" src="images/BLOG (3).svg" alt=""></a>
        </div>
    </header>

    <div class="modify-article-form">
        <h2>Modify Article</h2>
        <form method="POST" action="edit.php?editedId=<?php echo $editedArticle;?>">
            <input type="hidden" name="article_id" value="<?php echo $editresult['article_id']; ?>">

            <div class="form-group">
                <label for="article-title">Title</label>
                <input type="text" id="article-title" name="article_title" value="<?php echo htmlspecialchars($editresult['article_title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="article-content">Content</label>
                <textarea id="article-content" name="article_content" rows="8" required><?php echo htmlspecialchars($editresult['article_content']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="article-category">Category</label>
                <select id="article-category" name="article_category" required>
                    <option value="Technology" <?php echo $editresult['article_category'] === 'Technology' ? 'selected' : ''; ?>>Technology</option>
                    <option value="Health" <?php echo $editresult['article_category'] === 'Health' ? 'selected' : ''; ?>>Health</option>
                    <option value="Education" <?php echo $editresult['article_category'] === 'Education' ? 'selected' : ''; ?>>Education</option>
                    <option value="Lifestyle" <?php echo $editresult['article_category'] === 'Lifestyle' ? 'selected' : ''; ?>>Lifestyle</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-submit" name="update-it">Update Article</button>
            </div>
        </form>
    </div>

</body>

</html>