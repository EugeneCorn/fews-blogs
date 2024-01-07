<?php
    include('auth_session.php');
    require('db_connection.php');
    include("mysql_secure_query_functions.php");

    // Variables to passing arguments from URL.
    $comment_id = $url[1];

    // Check does comment id is not empty.
    if (!empty($comment_id)) {

        $username = $_SESSION['username'];

        $comment_query = mysqli_prepare($db_connection,
    "SELECT * 
           FROM `comments` 
           WHERE ( `comment_id` , `username` ) = ( ? , ? )")
        or die(mysqli_error($db_connection));

        $secure_stmt_variables = array($comment_id, $username);

        $comment_item = secureMysqliQuerySelect($comment_query, $secure_stmt_variables);
        
        // Check does article exists.
        if($comment_item > 0){
?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Edit Comment | Fews Blogs</title>
                <!-- <link href="styles/reset.css" rel="stylesheet" /> -->
                <link href="/styles/main.css" rel="stylesheet" />
                <link href="/styles/comment_edit.css" rel="stylesheet" />
                <link href='https://fonts.googleapis.com/css?family=Space+Mono|Muli|Sofia' rel='stylesheet'>
                <!-- Icons kit. -->
                <script src="https://kit.fontawesome.com/743929e53b.js" crossorigin="anonymous"></script>
            </head>
            <header class="user_panel">
                <?php include("user_panel.php") ?>
            </header>
            <body>
                <h3>Edit Comment</h3>
                <form action="" method="post">
                    <textarea 
                        name="edit_comment"
                        rows="2"
                        cols="60"
                        placeholder="Text here"
                        required><?php print $comment_item['comment'] ?></textarea></br>
                        <div class=comment_add_button>
                    <input type="submit" value="Edit" name="edit">
                </form>
<?php
                // Edit comment block.
                if (isset($_REQUEST['edit_comment'])) {

                    $comment        = $_REQUEST['edit_comment'];
                    $edit_datetime  = date("Y-m-d H:i:s");

                    $comment_edit_query = mysqli_prepare($db_connection,
                "UPDATE `comments`
                       SET 
                           comment= ? ,
                           edit_datetime= ?
                       WHERE 
                           comment_id= ? ")
                    or die(mysqli_error($db_connection));

                    $secure_stmt_variables = array(
                        &$comment,
                        &$edit_datetime,
                        &$comment_id,
                    );

                    secureMysqliQueryExecute($comment_edit_query, $secure_stmt_variables);

                    if ($comment_edit_query) {
                        print "<p class='comment_info'>Comment was successfully updated!</p>";
                    } else {
                        print "<p class='comment_info'>ERROR: Comment wasn't updated.</p>";
                    }
                }
                print "</div>"
?>
            </body>
            <footer class="footer">
                <?php include("footer.php") ?>
            </footer>
            </html>
<?php
        } else {
            // Show the "Page not found" error, if comment id isn't exists.
            include('page_not_found.php');
        }
    } else {
        // Show "Page not found" error if article without id.
        include('page_not_found.php');
    }
