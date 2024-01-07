<?php
    // Variables to passing arguments from URL.
    $article =  $url[0];
    $article_id = $url[1];

    session_start();
    require('db_connection.php');
    include('mysql_secure_query_functions.php');
    include ('website_functions.php');

    // Check does article ID is not empty.
    if(!empty($article_id)) {

        // Prepared MySQL statement to prevent SQL injection.
        $article_query =  mysqli_prepare($db_connection,
            "SELECT *
            FROM `articles` 
            WHERE ( `article_id` , `public` ) = ( ? , ? )");

        $article_id = "$url[1]";
        $public = "yes";

        // Prepared variables for the secureMysqliQuerySelect function.
        $secure_stmt_variables = array(
                        $article_id,
                        $public,
                    );

        $article_item = secureMysqliQuerySelect($article_query, $secure_stmt_variables);

        // Check does article exists.
        if($article_item) {

            $username = $article_item['username'];

            $article_user_query = mysqli_prepare($db_connection,
                "SELECT *
                FROM `users` 
                WHERE username= ?");

            $secure_stmt_variables = array(&$username);
            $article_user_item = secureMysqliQuerySelect($article_user_query, $secure_stmt_variables);
?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title><?php print $article_item['topic'] ?> | Fews Blogs</title>
                <link href="/styles/main.css" rel="stylesheet" />
                <link href="/styles/article.css" rel="stylesheet" />
                <link href='https://fonts.googleapis.com/css?family=Space+Mono|Muli|Sofia' rel='stylesheet'>
                <!-- Icons kit. -->
                <script src="https://kit.fontawesome.com/743929e53b.js" crossorigin="anonymous"></script>
            </head>
            <header class="user_panel">
                <?php include("user_panel.php") ?>
            </header>
            <body>
<?php
                print "
                <h2 class='topic'>".newlines2br($article_item['topic'])."</h2>
                <ul class='article_info'>
                    <li class='right'>
                        <p class='text'>Created by
                            <a href='/user/".$article_user_item['id']."'>".$article_item['username']."</a>
                        </p>
                    </li>";

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
            print "</ul>";
?>
                <p class="article_content"><?php print newlines2br($article_item['content']) ?></p>
                <p class="comment">Comments</p>
<?php
                // Show the "Add Comment" form for logged-in users.
                if (isset($_SESSION['username'])) {
?>
                    <form action="" method="post">
                        <textarea
                            name="comment"
                            rows="2"
                            cols="60"
                            placeholder="Start to enter a comment..."
                            required></textarea> <br>
                        <div class=comment_add_button>
                        <input type="submit" name="add_comment" value="Add">
                    </form>
<?php           }
            // Add comment block.
            if(isset($_REQUEST['comment'])) {

                $comment_username       = $_SESSION['username'];

                // SQL query to find out the username information.
                $comment_user_query     = mysqli_prepare($db_connection,
                    "SELECT * 
                    FROM `users` 
                    WHERE username= ?");

                $secure_stmt_variables = array(&$comment_username);
                $comment_user_item = secureMysqliQuerySelect($comment_user_query, $secure_stmt_variables);

                // Variables for the future INSERT query.
                $comment                = $_REQUEST['comment'];
                $create_datetime        = date("Y-m-d H:i:s");
                $article_id             = $article_item['article_id'];
                $article_topic          = $article_item['topic'];
                $comment_username_id    = $comment_user_item['id'];
                $comment_username_email = $comment_user_item['email'];

                // The INSERT query for comments adding.
                $comment_create_query = mysqli_prepare($db_connection,
                    "INSERT 
                    INTO `comments` 
                        (comment, create_datetime,
                         article_id, article_topic,
                         username, username_id, username_email)
                    VALUES 
                        (? , ? , ? , ? , ? , ? , ?)")
                    or die(mysqli_error($db_connection));

                // The array for the secureMysqliQueryExecute function.
                $secure_stmt_variables = array($comment,
                                            $create_datetime,
                                            $article_id,
                                            $article_topic,
                                            $comment_username,
                                            $comment_username_id,
                                            $comment_username_email
                                        );

                // Execute the prepared statement.
                secureMysqliQueryExecute($comment_create_query, $secure_stmt_variables);

                if ($comment_create_query){
                    print "<p class='comment_info'>The comment added!</p>";
                } else{
                    print "<p class='comment_info'>ERROR: The comment wasn't add.</p>";
                }
            }
            print "</div>";

            // Comment list.
            $comment_list_query = mysqli_prepare($db_connection,
                "SELECT * 
                FROM `comments` 
                WHERE article_id= ? 
                ORDER BY create_datetime");

            $params = array($article_id);
            $comments = secureMysqliQuerySelectForLoop($comment_list_query, $params);

            if ($comments) {
                $n = 1;
                  foreach ($comments as $commentResult) {
                    print "
                        <ul class='comment_list'>
                            <li>
                                <p> #".$n++." "."</p>
                            </li>
                            <li>
                                <p>            
                                    <a class='comment_user'
                                       href='/user/".$commentResult['username_id']."'
                                    >
                                        ".$commentResult['username']."
                                    </a>
                                </p>
                            </li>";

                    // Show edit date and time if the comment edited.
                    if ($commentResult['edit_datetime'] != null){
                        print "<li>
                                    <p>".$commentResult['edit_datetime']."</p>
                                </li>";
                    } else {
                        print "<li>
                                    <p>".$commentResult['create_datetime']."</p>
                                </li>";
                    }

                    // Show "Edit" and "Delete" links for the comment owner.
                    if(isset($_SESSION['username'])){
                        if($_SESSION['username'] == $commentResult['username']){
                            print "<li>
                                        <div class='comment_dropdown'>
                                            <button class='comment_dropdown_button'>
                                                <i class='fa-solid fa-bars'></i>
                                            </button>
                                                <div class='comment_dropdown_content'>
                                                    <a href='/comment/".$commentResult['comment_id']."/edit'>Edit</a>
                                                    <a href='#' 
                                                       onclick='comment_delete(
                                                           ".$commentResult['comment_id'].",
                                                           ".$commentResult['article_id'].")'
                                                    >
                                                        Delete
                                                    </a>
                                                </div>
                                        </div>
                                    </li>";
                        }
                    }

                    if ($commentResult['edit_datetime'] != null){
                        print "<li>
                                    <p class='comment_edited'>Edited</p>
                               </li>";
                    }

                    print "</ul>";
                    print  "<p class='comment_content'>".newlines2br($commentResult["comment"])."</p>";
                }
            // Show the message if the comment list is empty.
            } else {
                    print "<p class='comment_no_comment'>Nobody hasn't left any comment yet. Be the first one!</p>";
            }
?>
            <script>
            // Function for comments deleting.
                function comment_delete(comment_id) {
                    var r = confirm("Delete comment?");
                    if (r === true) {
                        window.location.assign("/comment/" + comment_id + "/delete");
                    }
                }
            </script>
            <br>
            </body>
            <footer class="footer">
                <?php include("footer.php") ?>
            </footer>
            </html>
<?php   
        } else {
            // Show the "Page not found" error, if an article id isn't exists.
            include("page_not_found.php");
        }
    } else {
        // Show "Page not found" error if an article without id.
        include("page_not_found.php");
    }
?>
