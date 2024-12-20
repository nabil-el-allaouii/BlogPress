<?php

$conn = mysqli_connect("localhost", "root", "hassizaio2010", "blogpress");


if (!$conn) {
    echo "error" . mysqli_connect_error();
}
$errors = array("username" => "", "password" => "", "name" => "");
$errors_2 = array("email" => "", "username" => "");
$login_error = "";

$formVisibility = "";
$profile = "";

$name = $username = $email = $password = "";

if (isset($_POST["submit"])) {
    $formVisibility = "signUp";
    if (!empty($_POST["username"])) {
        $username = $_POST["username"];
        if (!preg_match('/^[a-zA-Z][0-9a-zA-Z_]{2,23}[0-9a-zA-Z]$/', $username)) {
            $errors["username"] = "username not valid";
        }
    }
    if (!empty($_POST["password"])) {
        $password = $_POST["password"];
        if (!preg_match('/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/', $password)) {
            $errors["password"] = "your password is weak";
        }
    }
    if (!empty($_POST["name"])) {
        $name = $_POST["name"];
        if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
            $errors["name"] = "Name is invalid";
        }
    }
    if (!empty($_POST["email"])) {
        $email = $_POST["email"];
    }

    if (array_filter($errors)) {
        echo "there is errors in the form";
    } else {
        $verify_email = mysqli_query($conn, "SELECT email FROM authors WHERE email = '$email'");
        $verify_username = mysqli_query($conn, "SELECT username FROM authors WHERE username = '$username'");

        if (mysqli_num_rows($verify_email) != 0) {
            $errors_2["email"] = "email already in use, try again with another email";
        } else if (mysqli_num_rows($verify_username) != 0) {
            $errors_2["username"] = "username is already in use, please use another username";
        } else {
            echo "sign up was successful, please login now";
            $formVisibility = "";
            mysqli_query($conn, "INSERT INTO authors(author_name , username, pass_word , email) values('$name' , '$username', '$password', '$email')");
        }
    }
}

///
session_start();

if (isset($_POST["login"])) {
    $formVisibility = "sign-in";
    $e_username = mysqli_escape_string($conn, $_POST["username"]);
    $e_password = mysqli_escape_string($conn, $_POST["password"]);

    $result = mysqli_query($conn, "SELECT * FROM authors WHERE username = '$e_username' AND pass_word = '$e_password'");
    $row = mysqli_fetch_assoc($result);

    if (is_array($row) && !empty($row)) {
        $_SESSION["username"] = $row["username"];
        $_SESSION["password"] = $row["pass_word"];
        $_SESSION["id"] = $row["author_id"];
        header("location: Dashboard.php");
    } else {
        $login_error = "Wrong username or password";
    }
}

if (isset($_SESSION["username"])) {
    $profile = "exist";
} else {
    $profile = "";
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body>
    <input type="hidden" id="test" value="<?php echo $profile; ?>">
    <header>
        <div class="logo">
            <a href="index.php"><img id="logo-svg" src="images/BLOG (3).svg" alt=""></a>
        </div>
        <?php
        if (isset($_SESSION["username"])) {
            echo "<div class='dashboard-open'>
                <p>user: {$_SESSION["username"]}</p>
                <a href='Dashboard.php'><button class='dashboard-button'>Dashboard</button></a>
                </div>";
        }
        ?>
        <div class="auth">
            <h3 class="login-trigger">Sign In</h3>
            <button class="sign_up">Get started</button>
        </div>
    </header>

    <div class="main_content">
        <div class="articles">
            <div class="article-card">
                <?php
                $showR = mysqli_query($conn, "SELECT authors.author_name, articles.article_title, articles.view_count, articles.article_id, articles.article_content, articles.article_category, articles.created_at FROM articles join authors on articles.author_id = authors.author_id");

                if (mysqli_num_rows($showR) != 0) {
                    while ($row = mysqli_fetch_assoc($showR)) {
                        echo "
                            <div class='card'>
                                <div class='card-content'>
                                    <h2>" . htmlspecialchars($row['article_title']) . "</h2>
                                    <p class='category'>" . htmlspecialchars($row['article_category']) . "</p>
                                    <p class='excerpt'>" . htmlspecialchars(substr($row['article_content'], 0, 100)) . "...</p>
                                    <p class='date'>Published: " . date("F j, Y", strtotime($row['created_at'])) . "</p>
                                    <p style='color:gray'> Created by: {$row['author_name']}</p>
                                </div>
                                <div class='card-actions'>
                                    <a href='view.php?articleId={$row['article_id']}'>Read more</a>
                                </div>
                            </div>";
                    }
                } else {
                    echo "<p>No articles found.</p>";
                }
                ?>
            </div>



        </div>
        <div class="Best_picks"></div>
    </div>

    <div class="sign-up-form">
        <div style="color: red; font-family: sans-serif"><?php echo $errors_2["email"] ?></div>
        <div style="color: red; font-family: sans-serif"><?php echo $errors_2["username"] ?></div>
        <h1>Sign up</h1>
        <form action="index.php" method="POST">
            <input type="hidden" id="formVisibility" value="<?php echo $formVisibility; ?>">
            <input type="text" placeholder="Name" name="name" required value="<?php echo $name ?>">
            <div style="color: red; font-family: sans-serif"> <?php echo $errors["name"] ?></div>
            <input type="email" placeholder="Email" name="email" required value="<?php echo $email ?>">
            <input type="text" placeholder="Username" name="username" required value="<?php echo $username ?>">
            <div style="color: red; font-family: sans-serif"> <?php echo $errors["username"] ?></div>
            <input type="password" placeholder="Password" name="password" required value="<?php echo $password ?>">
            <div style="color: red; font-family: sans-serif"> <?php echo $errors["password"] ?></div>
            <input type="submit" class="submit-create" value="Create" name="submit">
        </form>
    </div>

    <div class="sign-in-form">
        <div style="color: red; font-family: sans-serif"><?php echo $login_error ?></div>
        <h1>Sign in</h1>
        <form action="index.php" method="POST">
            <input type="hidden" id="formVisibility" value="<?php echo $formVisibility; ?>">
            <input type="text" placeholder="Username" name="username">
            <input type="password" placeholder="Password" name="password">
            <input type="submit" class="submit-login" value="Login" name="login">
        </form>
    </div>


    <script src="style/index.js"></script>
</body>

</html>