<?php
    include("auth_session.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Article | Fews Blogs</title>
    <!-- <link href="styles/reset.css" rel="stylesheet" /> -->
    <link href="/styles/main.css" rel="stylesheet" />
    <link href="/styles/add_edit_article.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Space+Mono|Muli|Sofia' rel='stylesheet'>
    <!-- Icons kit. -->
    <script src="https://kit.fontawesome.com/743929e53b.js" crossorigin="anonymous"></script>
</head>
<header class="user_panel">
    <?php include("user_panel.php") ?>
</header>
<body>
    <h1>Add Article</h1>
<?php
    require('db_connection.php');
    include('mysql_secure_query_functions.php');

    if(isset($_REQUEST['topic'])) {
        $topic    = $_REQUEST['topic'];
        $content    = $_REQUEST['content'];
        $username   = $_SESSION['username'];
        $create_datetime = date("Y-m-d H:i:s");

        // Checking the 'Publish' checkbox value
        if (isset($_POST['publish'])){
            $publish = "yes";
        } else {
            $publish = "no";
        }

        $article_add_query = mysqli_prepare($db_connection,
        "INSERT
               INTO `articles` 
                   (topic, 
                    content, 
                    username, 
                    create_datetime, 
                    public) 
               VALUES ( ?, ? , ? , ? , ? )")
            or die(mysqli_error($db_connection));

        // The array for the secureMysqliQueryExecute function.
        $secure_stmt_variables = array(
            $topic,
            $content,
            $username,
            $create_datetime,
            $publish,
        );

        // Execute the prepared statement.
        secureMysqliQueryExecute($article_add_query, $secure_stmt_variables);

        if ($article_add_query) {
            print "
                <div class='congrats'>
                    <p class='congrats_title'>Congratulations!</p> 
                    <p class='congrats_text'>You added the article!</p>
                    <p><a href='/article/add'>Add another article</a></p>
                </div>";
        } else {
            echo "<p>ERROR: Something went wrong. Article wasn't add. :(</p>";
        }
    } else {
?>
        <form action="" method="post">
            <p class='title'>Topic</p>
            <div class='align_textarea>'>
                <textarea name="topic" rows="2" cols="60" required></textarea>
            </div>
            <p class='title'>Content</p>
            <textarea name="content" rows="20" cols="60" required></textarea>
            <div class='add_button'>
                <input type="submit" value="Add" name="add">
                <p class='publish'>Publish? <input type="checkbox" name="publish[]" value="yes"></p>
            </div>
        </form>
<?php 
    } 
?>
</body>
<footer class="footer">
    <?php include("footer.php") ?>
</footer>
</html>
