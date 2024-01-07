<?php
    // Variable to pass article ID from URI.
    $article_id = $url[1];

    include("auth_session.php");
    require("db_connection.php");
    include("mysql_secure_query_functions.php");
    include("website_functions.php");

    // Check does article id is not empty.
    if (!empty($article_id)) {

        $username = $_SESSION['username'];

        $article_query = mysqli_prepare($db_connection, "SELECT * FROM `articles` 
            WHERE (article_id,username)=( ? , ? )");

        $secure_stmt_variables = array($article_id, $username);

        $article_item = secureMysqliQuerySelect($article_query, $secure_stmt_variables);

        // Check does article exists.
        if($article_item > 0){
?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Edit Article | Fews Blogs</title>
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
                <h1>Edit Article: "<?php print $article_item['topic']?>"</h1>
                <div class='info_panel'>
                    <div class='article_info'>
<?php
                        if ($article_item['edit_datetime'] != null){
                            print "<p><b>Updated:</b> ".$article_item['edit_datetime']."</p>";
                        } else {
                            print "<p><b>Created:</b> ".$article_item['create_datetime']."</p>";
                        }
?>
                        <p><b>Published:</b> <?php print $article_item['public']?> </p>
                    </div>
                </div>
                <form action="" method="post">
                    <p class='title'>Topic</p>
                        <textarea name="topic" rows="2" cols="60" required><?php print $article_item['topic']?></textarea>
                    <p class='title'>Content</p>
                    <textarea name="content" rows="20" cols="60" required><?php print $article_item['content']?></textarea>
                    <div class='add_button'>
                        <input type="submit" value="Edit" name="edit">
                        <p class='publish'>
                            Publish?<input type="checkbox" name="publish[]" value="yes">
                        </p>
                </form>
<?php 
                } else {
                    // Show the "Page not found" error, if article id isn't exists.
                    include("page_not_found.php");
                }
            } else {
                // Show "Page not found" error if article without id.
                include("page_not_found.php");
            }

            // Updated the `article` table if there is a 'topic' request. 
            if(isset($_REQUEST['topic'])) {
                $topic      = $_REQUEST['topic'];
                $content    = $_REQUEST['content'];
                $edit_datetime = date("Y-m-d H:i:s");

                // Checking the Publish checkbox value.
                if (isset($_POST['publish'])){
                    $publish = "yes";
                } else {
                    $publish = "no";
                }

                $article_update_query = mysqli_prepare(
                        $db_connection,
                        "UPDATE `articles` 
                                SET 
                                    topic= ? , 
                                    content= ? , 
                                    edit_datetime= ? , 
                                    public= ? 
                                WHERE 
                                    article_id= ? ")
                or die(mysqli_error($db_connection));

                $secure_stmt_variables = array(
                        $topic,
                        $content,
                        $edit_datetime,
                        $publish,
                        $article_id,
                );

                secureMysqliQueryExecute($article_update_query, $secure_stmt_variables);

                if (!mysqli_error($db_connection)) {
                    print "<p class='publish'>
                                The article <b>" . newlines2br($article_item['topic']) . "</b> was successfully edited!
                           </p>";
                } else {
                    print "<p class='publish'>
                                ERROR: The article <b>" . newlines2br($article_item['topic']) . "</b> wasn't edited.
                           </p>";
                }
            }
            print "</div>";
        ?>
            </body>
            <footer class="footer">
                <?php include("footer.php") ?>
            </footer>
            </html>
