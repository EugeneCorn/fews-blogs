<?php
session_start();
require("db_connection.php");
include("mysql_secure_query_functions.php");
include("website_functions.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Fews Blogs</title>
    <!-- <link href="styles/reset.css" rel="stylesheet" /> -->
    <link href="/styles/main.css" rel="stylesheet" />
    <link href="/styles/homepage.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Space+Mono|Muli|Sofia' rel='stylesheet'>
    <!-- Icons kit. -->
    <script src="https://kit.fontawesome.com/743929e53b.js" crossorigin="anonymous"></script>
</head>
<header class="user_panel">
    <?php include("user_panel.php") ?>
</header>
<body>
<h1 class="sitename">Fews Blogs</h1>
<h2 class="slogan">Share us any of your thoughts</h2>
<?php

$public = "yes";

$article_query = mysqli_prepare($db_connection,
    "SELECT *
    FROM `articles`
    WHERE public= ? 
    ORDER BY create_datetime 
    DESC")
    or die(mysqli_error($db_connection));

$params = array($public);
$articles = secureMysqliQuerySelectForLoop($article_query, $params);

print "<div class='article'>";
foreach ($articles as $article_item) {

    $username = $article_item['username'];

    $user_query = mysqli_prepare($db_connection,
        "SELECT *
        FROM `users`
        WHERE username= ? ");

    $secure_stmt_variables = array($public);

    $user_item = secureMysqliQuerySelect($user_query, $secure_stmt_variables);
?>
<h3 class="topic">
    <a href="/article/<?php print $article_item['article_id'] ?>">
        <?php print newlines2br($article_item['topic']) ?>
    </a>
</h3>
<ul class="article_info">
    <li class="right">
        <p class="text">Created by
            <a class="user" href="/user/<?php print $user_item['id']?>">
                <?php print $article_item['username'] ?>
            </a>
        </p>
    </li>
    <?php
    // Show "Edited" time if article was edit.
    if ($article_item['edit_datetime'] != null){
        print "
                <li class='left'>
                        <p class='text'>
                            Last Edit: 
                        </p>
                        <p class='datetime'>
                            " . $article_item['edit_datetime'] . "
                        </p>
                    </li>";
    } else {
        print "
                <li class='left'>
                    <p class='text'>
                        Create: 
                    </p>
                    <p class='datetime'>
                        ".$article_item['create_datetime']."
                    </p>
                </li>";
    }
    print "</ul>
                <br>";
    print "<p class='article_content'>".newlines2br($article_item['content'])."</p>";

    $comment_query = mysqli_prepare($db_connection,
        "SELECT
        COUNT(comment_id)
        AS comment_number
        FROM `comments` 
        WHERE article_id= ? ");

    $secure_stmt_variables = array ($article_item['article_id']);
    $comment_count = secureMysqliQuerySelect($comment_query, $secure_stmt_variables);

    print "<a class='comment' href='/article/".$article_item['article_id']."'>
                Comments(".$comment_count['comment_number'].")
           </a>";
    }
    ?>
    </div>
</body>
<footer class="footer">
    <?php include("footer.php") ?>
</footer>
</html>
